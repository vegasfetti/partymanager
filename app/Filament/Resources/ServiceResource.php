<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Услуги';
    protected static ?string $pluralModelLabel = 'Услуги';
    protected static ?string $modelLabel = 'Услуга';
    protected static ?string $navigationGroup = 'Специалисты';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Описание'),

                Forms\Components\TextInput::make('price')
                    ->label('Цена')
                    ->numeric()
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('Изображение')
                    ->directory('service_upload')
                    ->image()
                    ->required(),

                Forms\Components\Select::make('specialist_id')
                    ->label('Специалист')
                    ->relationship('specialist', 'title')
                    ->required()->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Фото'),
                Tables\Columns\TextColumn::make('title')->label('Название')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->label('Цена')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('specialist.title')->label('Специалист')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->sortable()->searchable(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
