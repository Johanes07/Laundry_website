<?php

namespace App\Filament\Resources\StoreAccounts\Pages;

use App\Filament\Resources\StoreAccounts\StoreAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStoreAccount extends EditRecord
{
    protected static string $resource = StoreAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
