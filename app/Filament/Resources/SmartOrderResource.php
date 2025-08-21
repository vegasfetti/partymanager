<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmartOrderResource\Pages;
use App\Filament\Resources\SmartOrderResource\RelationManagers;
use App\Models\SmartOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmartOrderResource extends Resource
{
    protected static ?string $model = SmartOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationLabel = 'Умное бронирование';
    protected static ?string $pluralModelLabel = 'Умное бронирование';
    protected static ?string $navigationGroup = 'Заявки';
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'waiting')->count();
    }
    protected static ?string $modelLabel = 'Умное бронирование';
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('phone')->label('Телефон')->required(),
                Forms\Components\TextInput::make('social_network')->label('Соц. сеть')->required(),
                Forms\Components\TextInput::make('current_date')->label('Дата')->required(),
                Forms\Components\Textarea::make('comment')->label('Комментарий'),
                Forms\Components\Select::make('status')->label('Статус')
                    ->options([
                        'waiting' => 'Ожидает',
                        'verify' => 'Подтверждено',
                        'canceled' => 'Отменено',
                    ])->default('waiting'),
                Forms\Components\Select::make('user_id')->label('Пользователь')->relationship('user', 'name')->required()->searchable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Телефон')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('social_network')->label('Соц. сеть')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('current_date')->label('Дата')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Статус')->sortable()
                    ->badge()
                    ->colors([
                        'warning' => 'waiting',
                        'success' => 'verify',
                        'danger' => 'canceled',
                    ]),
                Tables\Columns\TextColumn::make('user.name')->label('Пользователь')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->sortable()->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'waiting' => 'Ожидает',
                    'verify' => 'Подтверждено',
                    'canceled' => 'Отменено',
                ])->label('Статус'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SmartOrderResource\RelationManagers\SmartOrderSpecialistsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSmartOrders::route('/'),
            'create' => Pages\CreateSmartOrder::route('/create'),
            'edit' => Pages\EditSmartOrder::route('/{record}/edit'),
        ];
    }
}
