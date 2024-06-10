<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\MiningSession;

class AllCount extends BaseWidget
{
    protected function getStats(): array
    {
        $user_count = User::count();
        $activeUserCount = MiningSession::where('created_at', '>=', now()->subHours(24))
            ->distinct('user_id')
            ->count('user_id');
        $TotalClicks = MiningSession::whereDate('created_at', Carbon::today())
            ->selectRaw('SUM(coin) as total_coin, COUNT(*) as count')->first();
        return [
            Stat::make('Total Users', $user_count)
                ->description('increase in Users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([2, 5, 2, 1, 5, 0]),

            Stat::make('Active User ', $activeUserCount)
                ->description('Last Mined In 24 Hours')
                //->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),

            Stat::make('Total Clicks', $TotalClicks->count)
            ->description('Todays Minings Sessions')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([2, 5, 2, 1, 5, 0]),
            Stat::make('Coin Mined Today', !$TotalClicks->total_coin?0:$TotalClicks->total_coin)
                ->description('increase in Coins')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([2, 5, 2, 1, 5, 0])
        ];
    }
}
