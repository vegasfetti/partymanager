<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestResource\Pages;
use App\Filament\Resources\RequestResource\RelationManagers;
use App\Models\Request;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;
    protected static ?string $navigationLabel = 'Обращения';
    protected static ?string $pluralModelLabel = 'Обращения';
    protected static ?string $navigationGroup = 'Заявки';
    protected static ?string $modelLabel = 'Обращение';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                    ->required()->searchable(),

                Forms\Components\Select::make('specialist_id')
                    ->label('Исполнитель')
                    ->relationship('specialist', 'title')
                    ->required()->searchable(),

                Forms\Components\Select::make('type')
                    ->label('Тип запроса')
                    ->options([
                        'promo' => 'Промо',
                        'verification' => 'Верификация',
                    ])
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        'waiting' => 'Ожидает',
                        'verify' => 'Подтверждено',
                        'canceled' => 'Отменено',
                    ])
                    ->default('waiting'),

                Forms\Components\Textarea::make('admin_comment')
                    ->label('Заметка'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Пользователь')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('specialist.title')->label('Исполнитель')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Тип запроса')->sortable()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'promo' => 'Промо',
                            'verification' => 'Верификация',
                            default => $state,
                        };
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'warning' => 'waiting',
                        'success' => 'verify',
                        'danger' => 'canceled',
                    ]),
                Tables\Columns\TextColumn::make('admin_comment')->label('Заметка')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'waiting' => 'Ожидает',
                    'verify' => 'Подтверждено',
                    'canceled' => 'Отменено',
                ]),
                Tables\Filters\SelectFilter::make('type')->options([
                    'promo' => 'Промо',
                    'verification' => 'Верификация',
                ]),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
        ];
    }
}
