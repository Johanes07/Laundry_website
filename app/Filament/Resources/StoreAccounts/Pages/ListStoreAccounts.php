<?php

namespace App\Filament\Resources\StoreAccounts\Pages;

use App\Filament\Resources\StoreAccounts\StoreAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStoreAccounts extends ListRecords
{
    protected static string $resource = StoreAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
