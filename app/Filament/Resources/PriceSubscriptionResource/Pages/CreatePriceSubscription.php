<?php

namespace App\Filament\Resources\PriceSubscriptionResource\Pages;

use App\Filament\Resources\PriceSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePriceSubscription extends CreateRecord
{
    protected static string $resource = PriceSubscriptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }
}
