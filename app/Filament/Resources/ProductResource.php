<?php

namespace App\Filament\Resources;

use App\Models\Tag;
use Filament\Forms;
use Filament\Tables;
use App\Models\Licence;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\ProductStatus;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sku')
                    ->required()
                    ->maxLength(255),

                Textarea::make('excerpt'),

                RichEditor::make('body')
                    ->columnSpan('full'),

                Select::make('licence_id')
                    ->label('Licence')
                    ->options(Licence::all()->pluck('short_description', 'id'))
                    ->searchable(),

                TextInput::make('stock')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100),

                TextInput::make('price')
                    ->numeric(),

                TextInput::make('sale_price')
                    ->numeric(),

                Select::make('status')
                    ->options([
                        'published' => ProductStatus::PUBLISHED->value,
                        'draft' => ProductStatus::DRAFT->value
                    ])
                    ->searchable()
                    ->selectablePlaceholder(false)
                    ->default(ProductStatus::DRAFT->value),

                Toggle::make('is_featured')
                    ->label('Featured'),

                Toggle::make('is_virtual')
                    ->label('Virtual')
                    ->default(true),

                Toggle::make('is_downloadable')
                    ->label('Downloadable')
                    ->default(true),

                Toggle::make('is_printable')
                    ->label('Printable')
                    ->default(true),

                Toggle::make('is_parametric')
                    ->label('Parametric'),

                Fieldset::make('Raft and Suport')
                    ->relationship('print_supports_rafts')
                    ->schema([
                        Toggle::make('has_supports')
                            ->label('Supports'),

                        Toggle::make('has_raft')
                            ->label('Raft'),
                    ]),

                Select::make('related_parametric')
                    ->options(Product::where('is_parametric')->pluck('title', 'id'))
                    ->searchable(),

                Fieldset::make('Tags')
                    ->relationship('tags')
                    ->schema([
                        TagsInput::make('tags')
                        // ->suggestions(Tag::all()->pluck('name', 'id')),
                    ]),

                Select::make('categories')
                    ->label('Categories')
                    ->multiple()
                    ->relationship(name: 'categories', titleAttribute: 'name')
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),

                TextColumn::make('slug'),

                TextColumn::make('price')
                    ->money('EUR'),

                TextColumn::make('sale_price')
                    ->money('EUR'),

                TextColumn::make('stock'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('')
                    ->trueColor('primary'),

                IconColumn::make('is_virtual')
                    ->label('Virtual')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('primary'),

                IconColumn::make('is_downloadable')
                    ->label('Downloadable')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('primary'),

                IconColumn::make('is_printable')
                    ->label('Printable')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('primary'),

                IconColumn::make('is_parametric')
                    ->label('Parametric')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('')
                    ->trueColor('primary'),

                TextColumn::make('related_parametric'),

                TextColumn::make('downloads'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
