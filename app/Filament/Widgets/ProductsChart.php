<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Products Per Month';
    protected static ?int $sort= 3;

    protected function getData(): array
    {
        $data = $this->getProductPerMonth();

        return [
            // Dataset for the chart
            'datasets' => [
                [
                    'label' => 'Products', // Title for the dataset
                    'data' => $data['productsPerMonth'], // Fixed key to match returned array
                    'backgroundColor' => 'rgba(75, 192, 192, 0.4)', // Optional styling
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'fill' => false,
                ],
            ],
            // Corresponding labels for the data points
            'labels' => $data['months'], // Fixed key to match returned array
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Type of chart
    }

    private function getProductPerMonth(): array
    {
        $now = Carbon::now();
        $productsPerMonth = [];

        // Collect data for all 12 months
        $months = collect(range(1, 12))->map(function ($month) use ($now, &$productsPerMonth) {
            // Get the month name
            $monthName = $now->copy()->month($month)->format('M');

            // Count products created in this month of the current year
            $count = Product::whereYear('created_at', $now->year)
                            ->whereMonth('created_at', $month)
                            ->count();

            // Append the count to the data array
            $productsPerMonth[] = $count;

            return $monthName; // Return month name for labels
        })->toArray();

        return [
            'productsPerMonth' => $productsPerMonth,
            'months' => $months,
        ];
    }
}
