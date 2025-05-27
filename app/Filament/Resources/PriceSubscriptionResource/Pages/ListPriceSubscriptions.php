<?php

namespace App\Filament\Resources\PriceSubscriptionResource\Pages;

use App\Filament\Resources\PriceSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPriceSubscriptions extends ListRecords
{
    protected static string $resource = PriceSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
