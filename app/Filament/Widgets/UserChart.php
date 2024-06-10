<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Carbon\Carbon;
class UserChart extends ChartWidget
{
    protected static ?string $heading = 'Users Registration';

    protected function getData(): array
    {
        $data = $this->getUserPerMonth();
        
        return [
            'datasets' => [
                [
                    'label' => 'Monthly User joining',
                    'data' => $data['UserPerMonth']
                ]
            ],
            'labels' => $data['months']
        ];
    }
    private function getUserPerMonth(): array
    {
        $now = Carbon::now();

        $UserPerMonth = [];
        $months = collect(range(1, 12))->map(function ($month) use ($now, &$UserPerMonth) {
            $count = User::whereMonth('created_at', Carbon::parse($now->month($month)->format('Y-m')))->count();
            $UserPerMonth[] = $count;

            return $now->month($month)->format('M');
        })->toArray();

        return [
            'UserPerMonth' => $UserPerMonth,
            'months' => $months,
        ];
    }
    protected function getType(): string
    {
        return 'line';
    }
}
