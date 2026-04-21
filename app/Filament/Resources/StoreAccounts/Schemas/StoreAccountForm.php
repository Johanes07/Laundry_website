<?php

namespace App\Filament\Resources\StoreAccounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class StoreAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')
                    ->required(),
                TextInput::make('account_number')
                    ->required(),
                TextInput::make('account_holder')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                FileUpload::make('logo')
                    ->image()
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                    ->directory('store-accounts/logos')
                    ->disk('public')
                    ->visibility('public')
                    ->fetchFileInformation(false)
                    ->previewable(true)
                    ->imageEditor()
                    ->nullable(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}