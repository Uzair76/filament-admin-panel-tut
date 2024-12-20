<?php

namespace App\Filament\Resources;

use App\Enums\ProductTypeEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;



    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Shop';
    public static function getNavigationBadge(): ?string
    {
            return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Group::make()
                ->schema([
                    Section::make()
                    ->schema(
                        [
                            TextInput::make('name')
                            ->required()->live(onBlur:true)
                            ->unique()
                            ->afterStateUpdated(function(string $operation, $state, Forms\Set $set){

                                if($operation !== 'create'){
                                    return;
                                }
                                $set('slug',Str::slug($state));
                            }),
                            TextInput::make('slug')
                            ->dehydrated()
                            ->unique(Product::class,'slug',ignoreRecord:true),
                            MarkdownEditor::make('description')
                            ->columnSpanFull()

                    ])->columns(2),

                    Section::make()
                    ->schema(
                        [
                            TextInput::make('sku')
                            ->label("SKU (Stock Keeping Unit)")
                                    ->required(),
                            TextInput::make('price')->numeric()
                            ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                            ->required(),
                            TextInput::make('quantity')
                            ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->required(),
                            Select::make('type')->options([
                                'downloadable'=>ProductTypeEnum::DOWNLOADABLE->value,
                                'deliverable'=>ProductTypeEnum::DELIVERABLE->value
                            ])


                    ])->columns(2)

                ]),
                Group::make()
                ->schema([
                    Section::make('Status')
                    ->schema(
                        [
                            Toggle::make('is_visible')
                            ->label('Visibility')
                            ->helperText('Enable or disable product visibility')
                            ->default(true),
                            Toggle::make('is_feature')->label('Featured')
                            ->helperText('Enable or disable products featured status'),
                            DatePicker::make('published_at')
                            ->label('Availability')
                                    ->default(now())

                        ]),
                        Section::make('Image')
                        ->schema(
                            [
                               FileUpload::make('image')
                               ->directory('form-attachments')
                               ->preserveFilenames()
                               ->image()
                               ->imageEditor()
                       ])->collapsible(),
                        Section::make('Association')
                        ->schema(
                            [
                                Select::make('brand_id')->relationship('brand','name')->required(),

                                Select::make('categories')
                                ->relationship('categories', 'name') // Reference the categories relationship in Product model
                                ->required()
                                ->multiple(),

                        ])

                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('image'),
                TextColumn:: make('name')
                ->searchable()
                ->sortable(),
                TextColumn::make('slug'),
                TextColumn::make('brand.name')
                ->searchable()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_visible') ->sortable()
                ->toggleable()
                ->label('Visibility')
                ->boolean(),
                IconColumn::make('is_feature')->boolean(),
                TextColumn::make('quantity')->sortable()
                ->toggleable(),
                TextColumn::make('price') ->sortable()
                ->toggleable(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
