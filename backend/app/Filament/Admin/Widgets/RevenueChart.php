<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Grafik Tren Omzet Penjualan (30 Hari Terakhir)';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '7' => '7 Hari Terakhir',
            '14' => '14 Hari Terakhir',
            '30' => '30 Hari Terakhir',
        ];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth('web')->user();

        // Hanya Administrator yang dapat melihat grafik finansial
        return $user && $user->hasRole('Administrator');
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 30);
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $orders = Order::whereIn('payment_status', ['paid'])
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->all();

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $displayDate = Carbon::now()->subDays($i)->format('d M');
            $labels[] = $displayDate;
            $data[] = (int) ($orders[$date] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet (IDR)',
                    'data' => $data,
                    'borderColor' => '#b85d38',
                    'backgroundColor' => 'rgba(184, 93, 56, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
