<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Filament\Resources\BrandResource\RelationManagers\ProductsRelationManager;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Schema;
use Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static ?string $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
              Group::make()->schema([
                Section::make()
                ->schema([
                    TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $set('slug', Str::slug($state)); // Automatically set slug whenever name is updated
                    }),

                TextInput::make('slug')
                    ->dehydrated()

                    ->required() // Ensure it's always populated
                    ->unique(),


                    TextInput::make('url')
                    ->columnSpanFull()
                    ->label('Website URL')
                    ->required(),
                    MarkdownEditor::make('description')->columnSpanFull()
                ])->columns(2)
                ]),
            Group::make()->schema([
                Section::make('Status')
                ->schema([
                    Toggle::make('is_visible')->helperText('on and off for product visibility')->default(true)
                ]),
                Group::make()->schema([
                    Section::make('Color')
                    ->schema([
                        ColorPicker::make('primary_hex')->label('Primary Color')
                    ])
                ])
                ]),

                ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                ->searchable()
                ->sortable(),
                TextColumn::make('slug'),
                ColorColumn::make('primary_hex')->label('Primary Color'),
                IconColumn::make('is_visible')->boolean(),
                TextColumn::make('updated_at')->date()->sortable()

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
            ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
