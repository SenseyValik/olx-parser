<?php

namespace App\Filament\Resources\PriceSubscriptionResource\Pages;

use App\Filament\Resources\PriceSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceSubscription extends EditRecord
{
    protected static string $resource = PriceSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
