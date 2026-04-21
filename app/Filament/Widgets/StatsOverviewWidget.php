<?php
namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // ← tambahkan ini
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();

        $thisMonth = Order::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year);
        $lastMonth = Order::whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year);

        $totalThisMonth = (clone $thisMonth)->count();
        $totalLastMonth = (clone $lastMonth)->count();

        $orderTrend = $totalLastMonth > 0
            ? round((($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100)
            : 0;

        $revenueThisMonth = (clone $thisMonth)->whereIn('status', ['completed'])->sum('total_price');
        $revenueLastMonth = (clone $lastMonth)->whereIn('status', ['completed'])->sum('total_price');

        $revenueTrend = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : 0;

        $activeOrders = Order::whereIn('status', ['confirmed', 'processing', 'ready'])->count();

        $topService = Order::selectRaw('service_id, count(*) as total')
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->with('service')
            ->first();

        return [
            Stat::make('Pesanan Bulan Ini', $totalThisMonth)
                ->description($orderTrend >= 0
                    ? "{$orderTrend}% lebih banyak dari bulan lalu"
                    : abs($orderTrend)."% lebih sedikit dari bulan lalu")
                ->descriptionIcon($orderTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($orderTrend >= 0 ? 'success' : 'danger')
                ->chart(
                    Order::selectRaw('COUNT(*) as count, EXTRACT(MONTH FROM created_at) as month') // ← fix di sini
                        ->whereYear('created_at', $now->year)
                        ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
                        ->orderBy('month')
                        ->pluck('count')
                        ->toArray()
                ),
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
                ->description($revenueTrend >= 0
                    ? "{$revenueTrend}% lebih tinggi dari bulan lalu"
                    : abs($revenueTrend)."% lebih rendah dari bulan lalu")
                ->descriptionIcon($revenueTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueTrend >= 0 ? 'success' : 'danger'),
            Stat::make('Pesanan Aktif', $activeOrders)
                ->description('Dikonfirmasi, dicuci, siap antar')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('Layanan Terlaris', $topService?->service?->name ?? '-')
                ->description($topService ? $topService->total . ' pesanan total' : 'Belum ada data')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}