<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort= 4;
    protected int|string|array $columnSpan='full';

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at','desc')
            ->columns([
                // ...
                   //
                   TextColumn::make('number')->searchable()->sortable(),
                   TextColumn::make('customer.id')->searchable()->sortable(),
                   TextColumn::make('status')->searchable()->sortable(),

                   TextColumn::make('unit_price')
                       ->searchable()
                       ->sortable()
                       ->summarize([
                           Tables\Columns\Summarizers\Sum::make()->money()
                       ]),
                   TextColumn::make('created_at')->label('order date')->date()

            ]);
    }
}