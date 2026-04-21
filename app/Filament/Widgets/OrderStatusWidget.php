<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusWidget extends ChartWidget
{
    protected ?string $heading = 'Pesanan per Bulan';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '280px';


    protected function getData(): array
    {
        $statuses = [
            'pending'    => 'Menunggu Konfirmasi',
            'confirmed'  => 'Diterima',
            'processing' => 'Sedang Dicuci',
            'ready'      => 'Siap Diantar',
            'completed'  => 'Selesai',
        ];

        $counts = collect($statuses)->map(
            fn ($label, $key) => Order::where('status', $key)->count()
        );

        return [
            'datasets' => [
                [
                    'data' => $counts->values()->toArray(),
                    'backgroundColor' => [
                        '#EF9F27', // pending - amber
                        '#378ADD', // confirmed - blue
                        '#7F77DD', // processing - purple
                        '#1D9E75', // ready - teal
                        '#639922', // completed - green
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $counts->keys()->map(fn ($k) => $statuses[$k])->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'bottom'],
            ],
            'cutout' => '65%',
        ];
    }
}