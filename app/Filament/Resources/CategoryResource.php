<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationLabel = 'Категории';
    protected static ?string $navigationGroup = 'Категории';

    public static function getModelLabel(): string
    {
        return 'Категорию';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Категории';
    }

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->reactive()
                    ->label('Название'),
                //->afterStateUpdated(fn($state, callable $set) => $set('slug', \Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                FileUpload::make('image')
                    ->label('Изображение')
                    ->directory('cat_upload') // будет храниться в storage/app/public/categories
                    ->image()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Название')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),
                Tables\Columns\ImageColumn::make('image')->label('Фото'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
