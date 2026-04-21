<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
                TextColumn::make('order_code')
                    ->label('Kode Order')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->description(fn ($record) => $record->customer_phone),

                TextColumn::make('customer_phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('service.name')
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('print_receipt')
                    ->label('Print Struk')
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('gray')
                    ->url(fn (Order $record) => route('orders.receipt', $record))
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}