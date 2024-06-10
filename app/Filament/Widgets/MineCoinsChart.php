<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\MiningSession;
use Illuminate\Support\Facades\DB;

class MineCoinsChart extends ChartWidget
{
    protected static ?string $heading = 'Weekly Coins Report';

    protected function getData(): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
    
        $data = DB::table('mining_sessions')
        ->selectRaw('DATE(created_at) as date, SUM(coin) as total_coins')
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get()
        ->keyBy(function ($item) {
            return Carbon::parse($item->date)->format('D');
        });

    $labels = array_fill_keys(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 0);
   //$labels = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],0);
    foreach ($data as $day => $item) {
        $labels[$day] = $item->total_coins;
    }
        return [
            'datasets' => [
                [
                    'label' => 'Total Coins Mined',
                    'data' => array_values($labels),
                ],
            ],
            'labels' => array_keys($labels),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
