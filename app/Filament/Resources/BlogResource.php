<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Блоги';
    protected static ?string $pluralLabel = 'Блоги';
    protected static ?string $modelLabel = 'Блог';
    protected static ?string $navigationGroup = 'Контент';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('subtitle')
                    ->required()
                    ->label('Подзаголовок'),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\FileUpload::make('image')
                    ->label('Изображение')
                    ->directory('blog_upload')
                    ->image()
                    ->required()
                    ->imageEditor()
                    ->default('no-photo.png'),

                Forms\Components\RichEditor::make('text')
                    ->label('Текст')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public') // диск (по умолчанию 'public')
                    ->fileAttachmentsDirectory('blog_upload'), // папка внутри диска

                Forms\Components\DateTimePicker::make('published_at')
                    ->required()
                    ->label('Дата публикации'),

                Forms\Components\TextInput::make('meta_title')
                    ->label('Meta Title'),

                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Изображение')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Опубликовано')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
