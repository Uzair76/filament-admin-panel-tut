<?php

namespace App\Filament\Widgets;

use App\Enums\orderStatus;
use App\Models\Order;
use DB;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort= 3;
    protected static ?string $heading = 'Orders';

    protected function getData(): array
    {
        $data= Order::select('status',DB::raw('count(*) as count'))
        ->groupBy('status')
        ->pluck('count','status')
        ->toArray();
        return [
            //
            'datasets'=>[
                [
                    'label'=>'Orders Stuatus',
                    'data'=>array_values($data)
                ]
                ],
            'labels'=>orderStatus::cases()

        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
