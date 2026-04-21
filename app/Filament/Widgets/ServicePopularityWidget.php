<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Service;
use Filament\Widgets\ChartWidget;

class ServicePopularityWidget extends ChartWidget
{
    protected ?string $heading = 'Layanan Terpopuler';
protected static ?int $sort = 4;
protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $data = Order::selectRaw('service_id, COUNT(*) as total')
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->with('service')
            ->limit(6)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#185FA5',
                        '#378ADD',
                        '#85B7EB',
                        '#639922',
                        '#97C459',
                        '#C0DD97',
                    ],
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $data->map(fn ($d) => $d->service?->name ?? 'Layanan #'.$d->service_id)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]],
            ],
        ];
    }
}