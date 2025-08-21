<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Простое бронирование';
    protected static ?string $pluralModelLabel = 'Простое бронирование';
    protected static ?string $navigationGroup = 'Заявки';
    protected static ?string $modelLabel = 'Простое бронирование';
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'waiting')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('specialist_id')
                    ->label('Исполнитель')
                    ->relationship('specialist', 'title')
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        'waiting' => 'Ожидает',
                        'verify' => 'Подтверждено',
                        'canceled' => 'Отменено',
                    ])
                    ->default('waiting'),

                Forms\Components\TextInput::make('phone')->label('Телефон')->required(),
                Forms\Components\Textarea::make('comment')->label('Комментарий')->nullable(),
                //дата создания
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Пользователь')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('specialist.title')->label('Исполнитель')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('specialist.phone')->label('Телефон исполнителя')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'warning' => 'waiting',
                        'success' => 'verify',
                        'danger' => 'canceled',
                    ]),
                Tables\Columns\TextColumn::make('comment')->label('Комментарий')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Создан')->dateTime()->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'waiting' => 'Ожидает',
                    'verify' => 'Подтверждено',
                    'canceled' => 'Отменено',
                ])->label('Статус'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
