<?php

namespace App\Filament\Resources\ShippingAgents\Pages;

use App\Filament\Resources\ShippingAgents\ShippingAgentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShippingAgent extends EditRecord
{
    protected static string $resource = ShippingAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
