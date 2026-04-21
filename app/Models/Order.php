<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'service_id',
        'quantity',
        'total_price',
        'notes',
        'status',
        'estimated_done',
        'picked_up_at',
        'payment_method',
        'store_account_id',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'estimated_done' => 'date',
        'picked_up_at'   => 'datetime',
        'paid_at'        => 'datetime',
        'total_price'    => 'decimal:2',
    ];

    // ── Relationships ──
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatus::class)->latest();
    }

    public function storeAccount()
    {
        return $this->belongsTo(StoreAccount::class);
    }

    // ── Accessors ──
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'transfer' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'cod'      => 'Bayar di Tempat (COD)',
            default    => '-',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Belum Dibayar',
            'paid'   => 'Sudah Dibayar',
            'cod'    => 'COD',
            default  => '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => '⏳ Menunggu',
            'confirmed'  => '✅ Diterima',
            'processing' => '🧺 Dicuci',
            'ready'      => '📦 Siap Antar',
            'completed'  => '🎉 Selesai',
            default      => $this->status,
        };
    }

    // ── Boot ──
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_code ??= 'LDR-' . date('Ymd') . '-' . str_pad(
                self::whereDate('created_at', today())->count() + 1,
                3, '0', STR_PAD_LEFT
            );

            $service = Service::find($order->service_id);
            if ($service) {
                $order->picked_up_at   ??= now();
                $order->estimated_done   = Carbon::parse($order->picked_up_at)
                    ->addDays($service->estimated_days);
            }
        });

        static::created(function ($order) {
            $order->statusHistories()->create([
                'status' => $order->status,
                'note'   => 'Pesanan baru diterima.',
            ]);
        });

        static::updated(function ($order) {
            if ($order->isDirty('status') || $order->isDirty('payment_status')) {
                broadcast(new \App\Events\OrderStatusUpdated($order));
                
                $order->statusHistories()->create([
                    'status' => $order->status,
                    'note'   => $order->_statusNote ?? 'Status diperbarui melalui sistem admin.',
                ]);

                // Langsung broadcast tanpa queue
                broadcast(new \App\Events\OrderStatusUpdated($order));
            }

            if ($order->isDirty('payment_status') && $order->payment_status === 'paid') {
                $order->updateQuietly(['paid_at' => now()]);
            }
        });
    }
}