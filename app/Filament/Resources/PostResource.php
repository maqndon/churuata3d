<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\PostStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
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
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                            ->dehydrateStateUsing(fn(string $state) => ucwords($state))
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            })
                            ->columnspan('full'),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->columnspan('full'),

                        RichEditor::make('excerpt')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('body')
                            ->required()
                            ->columnSpanFull(),

                        Repeater::make('bill_of_materials')
                            ->relationship('bill_of_materials')
                            ->schema([
                                TextInput::make('qty')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('item')
                                    ->required(),
                            ])
                            ->addActionLabel('Add new item')
                            ->columnSpanFull()
                            ->columns(2)
                    ])
                    ->columnSpan(3)
                    ->columns(2),

                Section::make()
                    ->schema([

                        Select::make('status')
                            ->options([
                                'Published' => PostStatus::PUBLISHED->value,
                                'Pending' => PostStatus::PENDING->value,
                                'Draft' => PostStatus::DRAFT->value
                            ])
                            ->searchable()
                            ->selectablePlaceholder(false)
                            ->default(PostStatus::DRAFT->value)
                            ->live(),

                        Group::make()
                            ->schema([

                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),

                            ])->columns(2),

                        Select::make('related_product')
                            ->options($products = Product::where('status', 'Published')->pluck('title', 'id'))
                            ->disabled(fn (): bool => $products->isEmpty())
                            ->searchable()
                            ->preload(),

                        Group::make()
                            ->relationship('seos')
                            ->schema([

                                TextInput::make('title')
                                    ->label('SEO Title'),

                                Textarea::make('meta_description'),

                                TagsInput::make('meta_keywords')
                                    ->placeholder('New meta keyword')
                                    ->splitKeys(['Tab', ' ']),

                            ])->columns(1),

                        Group::make()
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('images_names')
                                    ->label('Images')
                                    ->directory('post-images')
                                    ->preserveFilenames()
                                    ->image()
                                    ->reorderable()
                                    ->imageEditor()
                                    ->multiple()
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
                                    ->directory('post-files')
                                    ->visibility('private')
                                    ->preserveFilenames()
                                    ->reorderable()
                                    ->multiple()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $livewire) {
                                        $slug = ($livewire->data['slug']);
                                        return $slug !== null || $slug !== '' ? (string)str($file->getClientOriginalName())->prepend($slug . '_') : (string)$file->getClientOriginalName();
                                    }),
                            ])->columns(1),

                        Select::make('categories')
                            ->label('Categories')
                            ->multiple()
                            ->relationship(name: 'categories', titleAttribute: 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                TextInput::make('slug')
                                    ->required()
                            ])
                            ->searchable()
                            ->preload(),

                        Select::make('tags')
                            ->label('Tags')
                            ->multiple()
                            ->relationship(name: 'tags', titleAttribute: 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                TextInput::make('slug')
                                    ->required()
                            ])
                            ->searchable()
                            ->preload(),
                    ])
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

                TextColumn::make('author.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'gray',
                        'Pending' => 'warning',
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

                SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship(name: 'author', titleAttribute: 'name'),

                SelectFilter::make('status')
                    ->options([
                        'Published' => PostStatus::PUBLISHED->value,
                        'Pending' => PostStatus::PENDING->value,
                        'Draft' => PostStatus::DRAFT->value
                    ]),

                SelectFilter::make('categories')
                    ->relationship(name: 'categories', titleAttribute: 'name'),

                SelectFilter::make('tags')
                    ->relationship(name: 'tags', titleAttribute: 'name'),

                TernaryFilter::make('is_featured')
                    ->label('Featured Products')
                    ->trueLabel('Only Featured')
                    ->falseLabel('Not Featured'),

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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
