<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Отзывы';
    protected static ?string $pluralLabel = 'Отзывы';
    protected static ?string $modelLabel = 'Отзыв';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Специалисты';
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'on_moderation')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Заголовок')
                    ->required(),

                Forms\Components\Textarea::make('text')
                    ->label('Текст')
                    ->required(),

                Forms\Components\Select::make('rating')
                    ->label('Оценка')
                    ->options([
                        1 => '1 ★',
                        2 => '2 ★★',
                        3 => '3 ★★★',
                        4 => '4 ★★★★',
                        5 => '5 ★★★★★',
                    ])
                    ->required(),

                Forms\Components\Select::make('specialist_id')
                    ->label('Специалист')
                    ->relationship('specialist', 'title')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        'on_moderation' => 'На модерации',
                        'verify' => 'Подтвержден',
                        'canceled' => 'Отклонен',
                    ])
                    ->default('on_moderation')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable(),

                Tables\Columns\TextColumn::make('specialist.title')
                    ->label('Специалист')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Оценка')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'warning' => 'on_moderation',
                        'success' => 'verify',
                        'danger' => 'canceled',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'on_moderation' => 'На модерации',
                        'verify' => 'Подтвержден',
                        'canceled' => 'Отклонен',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'on_moderation' => 'На модерации',
                    'verify' => 'Подтвержден',
                    'canceled' => 'Отклонен',
                ])->label('Статус'),
                Tables\Filters\SelectFilter::make('rating')->options([
                    1 => '1 ★',
                    2 => '2 ★★',
                    3 => '3 ★★★',
                    4 => '4 ★★★★',
                    5 => '5 ★★★★★',
                ])->label('Оценка'),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
