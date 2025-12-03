@php
    $ratings = is_array($getState()) ? $getState() : [];
@endphp

<div style="display:flex; gap:10px; flex-wrap:wrap;">
    @foreach ($ratings as $key => $value)
        <div style="background:#111827; color:#e2e8f0; padding:6px 10px; border-radius:10px; font-size:12px;">
            <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}/5
        </div>
    @endforeach
</div>
