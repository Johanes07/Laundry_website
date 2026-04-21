<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Pendapatan Harian (Bulan Ini)';
    protected static ?int $sort = 3;
    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $now = Carbon::now();
        $daysInMonth = $now->daysInMonth;

        $days = collect(range(1, $daysInMonth))->map(
            fn($d) => Carbon::create($now->year, $now->month, $d)
        );

        $revenue = $days->map(
            fn($day) => (int) Order::whereDate('created_at', $day)
                ->whereIn('status', ['completed'])
                ->sum('total_price')
        );

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $revenue->values()->toArray(),
                    'borderColor' => '#378ADD',
                    'backgroundColor' => 'rgba(55, 138, 221, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $days->map(fn($d) => $d->format('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }",
                    ],
                ],
                'x' => [
                    'ticks' => ['maxRotation' => 45],
                ],
            ],
        ];
    }
}