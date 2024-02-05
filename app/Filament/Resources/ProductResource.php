<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Tables;
use App\Models\Licence;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Enums\ProductStatus;
use App\Models\PrintSetting;
use App\Models\PrintingMaterial;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()
                    ->schema([

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live()
                            ->dehydrateStateUsing(fn (string $state) => ucwords($state))
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            })
                            ->columnspan('full'),

                        TextInput::make('slug')
                            ->label('Permalink')
                            ->required()
                            // ->url()
                            ->prefix(env('APP_URL') . '/products/')
                            ->suffixIcon('heroicon-m-globe-alt')
                            ->maxLength(255)
                            // ->disabled()
                            ->columnSpanFull(),

                        TextInput::make('sku')
                            ->required()
                            ->maxLength(255),

                        Select::make('licence_id')
                            ->label('Licence')
                            ->required()
                            ->options(Licence::all()->pluck('short_description', 'id'))
                            // ->relationship('licence','short_description')
                            // ->preload()
                            ->searchable(),

                        RichEditor::make('excerpt')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('body')
                            ->required()
                            ->columnSpanFull(),

                        Repeater::make('bill_of_materials')
                            ->relationship()
                            ->schema([
                                TextInput::make('qty')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->required(),

                                TextInput::make('item')
                                    ->required()
                                    ->minLength(3),
                            ])
                            ->addActionLabel('Add new item')
                            ->defaultItems(0)
                            ->cloneable()
                            ->reorderable()
                            ->reorderableWithDragAndDrop()
                            ->columnSpanFull()
                            ->columns(2)
                    ])
                    ->columnSpan(3)
                    ->columns(2),

                Section::make()
                    ->schema([

                        Select::make('status')
                            ->options([
                                'Published' => ProductStatus::PUBLISHED->value,
                                'Draft' => ProductStatus::DRAFT->value
                            ])
                            ->searchable()
                            ->selectablePlaceholder(false)
                            ->default(ProductStatus::DRAFT->value)
                            ->live(),

                        Group::make()
                            ->schema([

                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),

                                Toggle::make('is_downloadable')
                                    ->label('Downloadable')
                                    ->default(true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('is_printable', false))
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('is_free', false))
                                    ->live(),

                                Toggle::make('is_free')
                                    ->label('Free Model')
                                    ->default(true)
                                    ->live()
                                    ->hidden(fn (Get $get): bool => !$get('is_downloadable'))
                                    ->disabled(fn (Get $get): bool => !$get('is_downloadable')),

                                Toggle::make('is_parametric')
                                    ->label('Parametric')
                                    ->live(),

                                Toggle::make('is_printable')
                                    ->label('Printable')
                                    ->default(true)
                                    ->live(),

                                Group::make()
                                    ->relationship('print_supports_rafts')
                                    ->schema([
                                        Toggle::make('has_supports')
                                            ->label('Supports')
                                            ->default(false),

                                        Toggle::make('has_raft')
                                            ->label('Raft')
                                            ->default(false),
                                    ])
                                    ->hidden(fn (Get $get): bool => !$get('is_printable'))
                                    ->disabled(fn (Get $get): bool => !$get('is_printable'))
                                    ->columns(2), //maybe is a bad idea to have 2 columns here

                            ])->columns(2),

                        Select::make('printing_materials')
                            ->options(PrintingMaterial::all()->pluck('name', 'id'))
                            ->relationship(name: 'printing_materials', titleAttribute: 'name')
                            ->hidden(fn (Get $get): bool => !$get('is_printable'))
                            ->disabled(fn (Get $get): bool => !$get('is_printable'))
                            ->multiple()
                            ->searchable()
                            ->preload(),

                        Select::make('print_settings')
                            ->label('Recommended Print Settings')
                            ->options(PrintSetting::all()->pluck('print_strength', 'id'))
                            ->relationship(
                                name: 'print_settings',
                                titleAttribute: 'description',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('id')
                            )
                            ->hidden(fn (Get $get): bool => !$get('is_printable'))
                            ->disabled(fn (Get $get): bool => !$get('is_printable'))
                            ->searchable()
                            ->preload(),

                        Select::make('related_parametric')
                            ->options($products = Product::where('status', 'published')->where('is_parametric', 1)->pluck('title', 'id'))
                            ->hidden(fn (Get $get): bool => $get('is_parametric'))
                            ->disabled(fn (Get $get): bool => $get('is_parametric') || $products->isEmpty())
                            ->searchable()
                            ->preload(),

                        Group::make()
                            ->relationship('seos')
                            ->schema([

                                TextInput::make('title')
                                    ->label('SEO Title')
                                    ->required(),

                                Textarea::make('meta_description')
                                    ->required(),

                                TagsInput::make('meta_keywords')
                                    ->placeholder('New meta keyword')
                                    ->splitKeys(['Tab', ' ']),

                            ])->columns(1),

                        Group::make()
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('images_names')
                                    ->label('Images')
                                    ->directory(function ($livewire) {
                                        $dir = 'product-images';
                                        $subdir = $livewire->data['slug'];
                                        return  $dir . DIRECTORY_SEPARATOR . $subdir;
                                    })
                                    ->preserveFilenames()
                                    ->image()
                                    ->reorderable()
                                    ->imageEditor()
                                    ->multiple()
                                    ->minFiles(2)
                                    ->maxFiles(4)
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $livewire) {
                                        $slug = ($livewire->data['slug']);
                                        return $slug !== null || $slug !== '' ? (string)str($file->getClientOriginalName())->prepend($slug . '_') : (string)$file->getClientOriginalName();
                                    }),
                            ])->columns(1),

                        Group::make()
                            ->relationship('files')
                            ->schema([
                                FileUpload::make('files_names')
                                    ->label('Files')
                                    ->directory(function ($livewire) {
                                        $dir = 'product-files';
                                        $subdir = $livewire->data['slug'];
                                        return  $dir . DIRECTORY_SEPARATOR . $subdir;
                                    })
                                    ->visibility('private')
                                    ->preserveFilenames()
                                    ->reorderable()
                                    ->multiple()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $livewire) {
                                        $slug = ($livewire->data['slug']);
                                        return $slug !== null || $slug !== '' ? (string)str($file->getClientOriginalName())->prepend($slug . '_') : (string)$file->getClientOriginalName();
                                    }),
                            ])->columns(1),

                        TextInput::make('stock')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->hidden(fn (Get $get): bool => $get('is_downloadable'))
                            ->disabled(fn (Get $get): bool => $get('is_downloadable')),

                        Group::make()->schema([

                            TextInput::make('price')
                                ->numeric(),

                            TextInput::make('sale_price')
                                ->numeric(),

                        ])
                            ->hidden(fn (Get $get): bool => $get('is_free'))
                            ->disabled(fn (Get $get): bool => $get('is_free'))
                            ->columns(2),

                        Select::make('categories')
                            ->label('Categories')
                            ->multiple()
                            ->minItems(1)
                            ->maxItems(4)
                            ->relationship(name: 'categories', titleAttribute: 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->live()
                                    ->minLength(3)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                Hidden::make('slug')
                                    ->required()
                            ])
                            ->searchable()
                            ->preload(),

                        Select::make('tags')
                            ->label('Tags')
                            ->multiple()
                            ->maxItems(4)
                            ->optionsLimit(15)
                            ->relationship(name: 'tags', titleAttribute: 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->live()
                                    ->minLength(3)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                Hidden::make('slug')
                                    ->required()
                            ])
                            ->searchable()
                            ->preload(),
                    ])
                    // ->extraAttributes(['style' => 'width: 25%'])
                    ->columnSpan(1),

            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->limit(40)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('price')
                    ->placeholder(fn (Product $record) => $record->price ?? 'Free')
                    ->money('EUR'),

                TextColumn::make('sale_price')
                    ->placeholder(fn (Product $record) => $record->price ?? 'Free')
                    ->money('EUR'),

                TextColumn::make('stock')
                    ->placeholder(fn (Product $record) => (int)$record->stock === 0 ? 'Out Stock' : $record->stock)
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'gray',
                        'Published' => 'success',
                    }),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('')
                    ->trueColor('primary')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_downloadable')
                    ->label('Downloadable')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('primary')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_printable')
                    ->label('Printable')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('primary')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_parametric')
                    ->label('Parametric')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('')
                    ->trueColor('primary')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('downloads')
                    ->placeholder(fn ($record) => $record->downloads ?? '-')
                    ->toggleable(),

                TextColumn::make('categories.name')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('tags.name')
                    ->badge()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([

                SelectFilter::make('status')
                    ->options([
                        'Published' => ProductStatus::PUBLISHED->value,
                        'Draft' => ProductStatus::DRAFT->value
                    ]),

                SelectFilter::make('categories')
                    ->relationship(name: 'categories', titleAttribute: 'name'),

                SelectFilter::make('tags')
                    ->relationship(name: 'tags', titleAttribute: 'name'),

                TernaryFilter::make('is_featured')
                    ->label('Featured Products')
                    ->trueLabel('Only Featured')
                    ->falseLabel('Not Featured'),

                TernaryFilter::make('is_downloadable')
                    ->label('Downloadable Products')
                    ->trueLabel('Only Downloadables')
                    ->falseLabel('Not Downloadables'),

                TernaryFilter::make('is_printable')
                    ->label('Printable Products')
                    ->trueLabel('Only Printables')
                    ->falseLabel('Not Printables'),

                TernaryFilter::make('is_parametric')
                    ->label('Parametric Products')
                    ->trueLabel('Only Parametrics')
                    ->falseLabel('Not Parametric'),
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
        return [];
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
