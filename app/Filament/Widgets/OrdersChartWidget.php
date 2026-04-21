<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OrdersChartWidget extends ChartWidget
{
    protected ?string $heading = 'Pesanan per Bulan';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '280px';


    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i));

        $masuk = $months->map(fn ($m) => Order::whereYear('created_at', $m->year)
            ->whereMonth('created_at', $m->month)
            ->count()
        );

        $selesai = $months->map(fn ($m) => Order::whereYear('created_at', $m->year)
            ->whereMonth('created_at', $m->month)
            ->where('status', 'completed')
            ->count()
        );

        return [
            'datasets' => [
               [
        'label' => 'Pesanan masuk',
        'data' => $masuk->values()->toArray(),
        'backgroundColor' => '#0D9488', // teal
        'borderRadius' => 4,
    ],
    [
        'label' => 'Selesai',
        'data' => $selesai->values()->toArray(),
        'backgroundColor' => '#22D3EE', // cyan
        'borderRadius' => 4,
    ],
            ],
            'labels' => $months->map(fn ($m) => $m->translatedFormat('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top'],
            ],
            'scales' => [
                'y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]],
            ],
        ];
    }
}