<?php

namespace App\Filament\Widgets;

use App\Enums\orderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort= 2;

    protected static ?string $pollingInterval = '15s'; // Fixed typo
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            // Total Customers Stat
            Stat::make('Total Customers', Customer::count())
                ->description('Total number of customers')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([1, 2, 3, 4, 6, 7, 8, 9]),

            // Total Products Stat
            Stat::make('Total Products', Product::count())
                ->description('Total number of products')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart([9, 8, 7, 6, 5, 4, 3, 2, 1]),

            // Pending Orders Stat
            Stat::make(
                'Pending Orders',
                Order::where('status', orderStatus::PENDING->value)->count()
            )
                ->description('Total number of pending orders')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([1, 2, 3, 4, 6, 7, 8, 9]),
        ];
    }
}
