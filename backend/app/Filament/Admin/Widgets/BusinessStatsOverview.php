<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class BusinessStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth('web')->user();

        // Roaster dan Staf Gudang/CS tidak melihat angka omzet keuangan
        return $user && ($user->hasRole('Administrator') || $user->can('view orders'));
    }

    protected function getStats(): array
    {
        /** @var \App\Models\User|null $user */
        $user = auth('web')->user();
        $isAdmin = $user && $user->hasRole('Administrator');

        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $yesterdayStart = $now->copy()->subDay()->startOfDay();
        $yesterdayEnd = $now->copy()->subDay()->endOfDay();

        // Omzet Hari Ini vs Kemarin
        $revenueToday = (int) Order::whereIn('payment_status', ['paid'])
            ->where('created_at', '>=', $todayStart)
            ->sum('grand_total');

        $revenueYesterday = (int) Order::whereIn('payment_status', ['paid'])
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('grand_total');

        $revDiff = $revenueToday - $revenueYesterday;
        $revTrend = $revDiff >= 0 ? "+ Rp " . number_format($revDiff, 0, ',', '.') . " vs kemarin" : "- Rp " . number_format(abs($revDiff), 0, ',', '.') . " vs kemarin";

        // Omzet 30 Hari Ini
        $revenueMonth = (int) Order::whereIn('payment_status', ['paid'])
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->sum('grand_total');

        // Pesanan Perlu Diproses (status = processing / paid)
        $ordersProcessing = Order::whereIn('status', ['processing', 'paid'])->count();

        // Pesanan Menunggu Pembayaran
        $ordersPending = Order::where('status', 'pending_payment')->count();

        // Stok Menipis
        $lowStockCount = ProductVariant::where('is_active', true)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->count();

        $stats = [];

        if ($isAdmin) {
            $stats[] = Stat::make('Omzet Hari Ini', 'Rp ' . number_format($revenueToday, 0, ',', '.'))
                ->description($revTrend)
                ->descriptionIcon($revDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revDiff >= 0 ? 'success' : 'danger');

            $stats[] = Stat::make('Omzet 30 Hari', 'Rp ' . number_format($revenueMonth, 0, ',', '.'))
                ->description('Total pesanan berstatus lunas')
                ->color('primary');
        }

        $stats[] = Stat::make('Perlu Diproses', $ordersProcessing . ' Pesanan')
            ->description('Segera sangrai & kemas')
            ->descriptionIcon('heroicon-m-clock')
            ->color($ordersProcessing > 0 ? 'warning' : 'gray');

        $stats[] = Stat::make('Menunggu Bayar', $ordersPending . ' Pesanan')
            ->description('Menunggu verifikasi QRIS/VA')
            ->color('info');

        $stats[] = Stat::make('Varian Stok Menipis', $lowStockCount . ' SKU')
            ->description('Perlu restock roasting')
            ->descriptionIcon('heroicon-m-exclamation-triangle')
            ->color($lowStockCount > 0 ? 'danger' : 'success');

        return $stats;
    }
}
