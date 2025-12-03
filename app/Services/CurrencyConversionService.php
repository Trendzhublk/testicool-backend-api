<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyConversionService
{
    private string $baseCurrency;
    private array $available;
    private int $cacheTtl;
    private ?array $resolvedRates = null;

    public function __construct()
    {
        $this->baseCurrency = strtoupper(config('currency.base', 'GBP'));
        $this->available = config('currency.available', []);
        $this->cacheTtl = (int) (config('currency.provider.cache_ttl', 360) ?: 360);
    }

    public function base(): string
    {
        return $this->baseCurrency;
    }

    /**
     * Codes we expose to the frontend.
     */
    public function supportedCodes(): array
    {
        $codes = array_values(array_unique(array_map('strtoupper', array_keys($this->available))));

        if (!in_array($this->baseCurrency, $codes, true)) {
            $codes[] = $this->baseCurrency;
        }

        return $codes;
    }

    public function normalize(?string $code): string
    {
        $code = strtoupper(trim($code ?? ''));

        return in_array($code, $this->supportedCodes(), true)
            ? $code
            : $this->baseCurrency;
    }

    public function symbol(string $code): string
    {
        $code = strtoupper($code);

        return $this->available[$code]['symbol'] ?? '';
    }

    /**
     * Rates keyed by currency code relative to the base currency.
     */
    public function rates(): array
    {
        if ($this->resolvedRates !== null) {
            return $this->resolvedRates;
        }

        $providerUrl = (string) config('currency.provider.url');
        $cacheKey = 'currency.rates.' . $this->baseCurrency . '.' . md5($providerUrl . implode(',', $this->supportedCodes()));

        $this->resolvedRates = Cache::remember(
            $cacheKey,
            now()->addMinutes($this->cacheTtl),
            function () use ($providerUrl) {
                $symbols = array_filter(
                    $this->supportedCodes(),
                    fn($code) => $code !== $this->baseCurrency
                );

                if (empty($symbols)) {
                    return [$this->baseCurrency => 1.0];
                }

                // Support both placeholder and query-parameter style providers.
                if (str_contains($providerUrl, '{base}')) {
                    $url = str_replace('{base}', $this->baseCurrency, $providerUrl);
                    $response = Http::timeout(8)->get($url);
                } else {
                    $response = Http::timeout(8)->get($providerUrl, [
                        'base' => $this->baseCurrency,
                        'symbols' => implode(',', $symbols),
                    ]);
                }

                $rates = [$this->baseCurrency => 1.0];

                if ($response->successful() && isset($response['rates']) && is_array($response['rates'])) {
                    foreach ($response['rates'] as $code => $rate) {
                        $code = strtoupper($code);
                        $rates[$code] = is_numeric($rate) ? (float) $rate : 1.0;
                    }
                } else {
                    // Fallback with neutral rates to avoid breaking the API.
                    foreach ($symbols as $code) {
                        $rates[$code] = 1.0;
                    }
                }

                return $rates;
            }
        );

        return $this->resolvedRates;
    }

    public function rate(string $code): float
    {
        $normalized = $this->normalize($code);
        $rates = $this->rates();

        return (float) ($rates[$normalized] ?? 1.0);
    }

    public function fromBase(float $amount, string $to): float
    {
        $rate = $this->rate($to);

        return round($amount * $rate, 2);
    }

    public function toBase(float $amount, string $from): float
    {
        $normalized = $this->normalize($from);
        $rate = $this->rate($normalized);

        if ($normalized === $this->baseCurrency || $rate === 0.0) {
            return $amount;
        }

        return round($amount / $rate, 4);
    }

    public function convert(float $amount, string $from, string $to): float
    {
        $base = $this->toBase($amount, $from);

        return $this->fromBase($base, $to);
    }

    public function currenciesWithRates(): array
    {
        $codes = $this->supportedCodes();
        $rates = $this->rates();
        // Guarantee base currency rate is present even if provider fails.
        $rates[$this->baseCurrency] = $rates[$this->baseCurrency] ?? 1.0;

        return array_map(function ($code) use ($rates) {
            return [
                'code' => $code,
                'symbol' => $this->symbol($code),
                'rate_to_base' => (float) ($rates[$code] ?? 1.0),
                'is_base' => $code === $this->baseCurrency,
            ];
        }, $codes);
    }
}
