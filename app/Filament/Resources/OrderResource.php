<?php

namespace App\Filament\Resources;

use App\Enums\orderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Doctrine\DBAL\Query;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 4;


    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Wizard::make(
                    [
                        Wizard\Step::make('order detail')->schema([

                            TextInput::make('number')
                                ->default(fn() => 'OR-' . random_int(100000, 999999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Select::make('customer_id')
                                ->relationship('customer', 'name')
                                ->required()
                            ,

                            Select::make('type')
                                ->options([
                                    'completed' => orderStatus::COMPLETED->value,
                                    'pending' => orderStatus::PENDING->value,
                                    'processing' => orderStatus::PROCESSING->value,
                                    'declined' => orderStatus::DECLINED->value,

                                ])->columnSpanFull(),

                            MarkdownEditor::make('notes')->columnSpanFull()

                        ])->columns(2),
                    Wizard\Step::make('item detail')->schema([
                           Repeater::make('items')
                           ->relationship()
                           ->schema([
                            Select::make('product_id')
                            ->label('Product ID')
                            ->options(Product::query()->pluck('name', 'id')),

                        TextInput::make('quantity')
                            ->default(1)
                            ->numeric()
                            ->required(),

                        TextInput::make('unit_price')->numeric()
                            ->required(),

                           ])
                        ])
                    ]

                )->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('customer.id')->searchable()->sortable(),
                TextColumn::make('status')->searchable()->sortable(),

                TextColumn::make('total_price')
                    ->searchable()
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()->money()
                    ]),
                TextColumn::make('created_at')->label('order date')->date()

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    \Filament\Tables\Actions\ViewAction::make(),
                    \Filament\Tables\Actions\DeleteAction::make(),
                    \Filament\Tables\Actions\EditAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
