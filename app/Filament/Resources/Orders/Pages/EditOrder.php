<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Services\OrderStatusNotifier;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    private ?string $originalStatus = null;

    protected function beforeFill(): void
    {
        $this->originalStatus = $this->record->status;
    }

    protected function afterSave(): void
    {
        if ($this->originalStatus !== $this->record->status) {
            $this->record->status_updated_at = now();
            $this->record->save();
            app(OrderStatusNotifier::class)->send($this->record);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
