<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Group::make()->schema([
                    Section::make()->schema([
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
                    ])->columns(2)
                    ]),
                    Group::make()->schema([
                        Section::make('Status')->schema([
                            Toggle::make('is_visible')
                            ->default(true)
                            ->label('on and oof for visibility'),
                            Select::make('parent_id')
                            ->relationship('parent','name')
                        ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')->label('Product Name'), // Product name
                TextColumn::make('slug')->label('Slug'), // Slug
                TextColumn::make('parent.name')->label('Parent Product') // Display the parent product's name
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_visible')->label('Visibility') // Visibility as an icon
                    ->boolean(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
