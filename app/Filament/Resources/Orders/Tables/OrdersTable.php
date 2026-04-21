<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s') // ← tambahkan baris ini
            ->columns([
                TextColumn::make('order_code')
                    ->label('Kode Order')
                    ->searchable()
                    ->copyable()        // bisa klik untuk copy
                    ->weight('bold'),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->description(fn ($record) => $record->customer_phone), // nomor HP di bawah nama

                // customer_phone & customer_email disembunyikan karena sudah ada di description
                TextColumn::make('customer_phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('service.name')  // relasi, bukan service_id
                    ->label('Layanan')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Berat')
                    ->numeric()
                    ->suffix(' kg')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending'    => 'Menunggu Konfirmasi',
                        'confirmed'  => 'Diterima',
                        'processing' => 'Sedang Dicuci',
                        'ready'      => 'Siap Diantar',
                        'completed'  => 'Selesai',
                    ])
                    ->sortable(),

                TextColumn::make('estimated_done')
                    ->label('Estimasi Selesai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('picked_up_at')
                    ->label('Dijemput')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc') // order terbaru di atas
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}