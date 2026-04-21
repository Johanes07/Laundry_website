<?php

namespace App\Filament\Resources\StoreAccounts\Pages;

use App\Filament\Resources\StoreAccounts\StoreAccountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreAccount extends CreateRecord
{
    protected static string $resource = StoreAccountResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}


