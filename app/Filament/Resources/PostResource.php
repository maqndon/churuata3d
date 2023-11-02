<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
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
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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
                            ->schema([
                                TextInput::make('item')
                                    ->required()
                                    ->live(onBlur: true),
                            ])
                            ->relationship('bill_of_materials')
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null)
                            ->columnSpanFull()
                            ->grid(2),
                    ])
                    ->columnSpan(3)
                    ->columns(2),

                Section::make()
                    ->schema([

                        Select::make('status')
                            ->options([
                                'published' => PostStatus::PUBLISHED->value,
                                'pending' => PostStatus::PENDING->value,
                                'draft' => PostStatus::DRAFT->value
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

                                Toggle::make('is_printable')
                                    ->label('Printable')
                                    ->default(true)
                                    ->live(),

                                Toggle::make('is_parametric')
                                    ->label('Parametric')
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
                                    ->disabled(fn (Get $get): bool => !$get('is_printable')),

                            ])->columns(2),

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
                                    ->preserveFilenames()
                                    ->image()
                                    ->reorderable()
                                    ->imageEditor()
                                    ->multiple(),
                            ])->columns(1),

                        Group::make()
                            ->relationship('files')
                            ->schema([
                                FileUpload::make('files_names')
                                    ->label('Files')
                                    ->preserveFilenames()
                                    ->reorderable()
                                    ->multiple(),
                            ])->columns(1),

                        TextInput::make('stock')
                            ->numeric()
                            ->minValue(1)
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
                    // ->extraAttributes(['style' => 'width: 25%'])
                    ->columnSpan(1),

            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
