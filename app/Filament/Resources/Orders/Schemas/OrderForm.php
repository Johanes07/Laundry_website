<?php
// ══════════════════════════════════════════════════════════════════
//  File: app/Filament/Resources/Orders/Schemas/OrderForm.php
// ══════════════════════════════════════════════════════════════════

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\StoreAccount;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                // ── Customer Info ──
                TextInput::make('order_code')
                    ->label('Kode Order')
                    ->disabled()
                    ->columnSpan(1),

                TextInput::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('customer_phone')
                    ->label('WhatsApp')
                    ->tel()
                    ->required()
                    ->columnSpan(1),

                TextInput::make('customer_email')
                    ->label('Email')
                    ->email()
                    ->columnSpan(1),

                Textarea::make('customer_address')
                    ->label('Alamat Penjemputan')
                    ->required()
                    ->columnSpanFull(),

                // ── Order Detail ──
                Select::make('service_id')
                    ->label('Layanan')
                    ->relationship('service', 'name')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('quantity')
                    ->label('Berat (kg)')
                    ->required()
                    ->numeric()
                    ->suffix('kg')
                    ->columnSpan(1),

                TextInput::make('total_price')
                    ->label('Total Tagihan')
                    ->numeric()
                    ->prefix('Rp')
                    ->columnSpan(1),

                DatePicker::make('estimated_done')
                    ->label('Estimasi Selesai')
                    ->columnSpan(1),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),

                // ── Order Status ──
                Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending'    => 'Menunggu Konfirmasi',
                        'confirmed'  => 'Diterima',
                        'processing' => 'Sedang Dicuci',
                        'ready'      => 'Siap Diantar',
                        'completed'  => 'Selesai',
                    ])
                    ->required()
                    ->native(false)
                    ->columnSpan(1),

                DateTimePicker::make('picked_up_at')
                    ->label('Dijemput Pada')
                    ->columnSpan(1),

                // ── Payment ──
                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                        'cod'      => 'COD',
                    ])
                    ->native(false)
                    ->live()
                    ->columnSpan(1),

                Select::make('store_account_id')
                    ->label('Rekening Tujuan')
                    ->options(
                        StoreAccount::active()->get()->mapWithKeys(
                            fn ($a) => [$a->id => "{$a->bank_name} — {$a->account_number} (a.n. {$a->account_holder})"]
                        )
                    )
                    ->native(false)
                    ->visible(fn ($get) => in_array($get('payment_method'), ['transfer', 'e_wallet']))
                    ->columnSpan(1),

                Select::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => '🔴 Belum Dibayar',
                        'paid'   => '🟢 Sudah Dibayar',
                        'cod'    => '🟡 COD',
                    ])
                    ->required()
                    ->native(false)
                    ->columnSpan(1),

                DateTimePicker::make('paid_at')
                    ->label('Waktu Pembayaran')
                    ->visible(fn ($get) => $get('payment_status') === 'paid')
                    ->columnSpan(1),
            ]);
    }
}