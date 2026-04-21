<?php

namespace App\Filament\Resources\StoreAccounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

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
                Placeholder::make('logo_preview')
                    ->label('Logo saat ini')
                    ->content(fn ($record) => $record?->logo
                        ? new HtmlString('<img src="'.asset('storage/'.$record->logo).'" height="60">')
                        : 'Belum ada logo'
                    )
                    ->visibleOn('edit'),
                FileUpload::make('logo')
                    ->image()
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                    ->directory('store-accounts/logos')
                    ->disk('public')
                    ->visibility('public')
                    ->fetchFileInformation(false)
                    ->previewable(false)   // ← ini
                    ->imageEditor()
                    ->nullable(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}