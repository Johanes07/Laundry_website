<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Kode')
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->description(fn ($record) => $record->customer_phone),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Layanan'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Berat')
                    ->suffix(' kg'),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'confirmed',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'gray'    => 'ready',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'    => 'Menunggu',
                        'confirmed'  => 'Diterima',
                        'processing' => 'Dicuci',
                        'ready'      => 'Siap Antar',
                        'completed'  => 'Selesai',
                        default      => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M, H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}