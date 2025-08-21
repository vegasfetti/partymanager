<?php

namespace App\Filament\Resources\SmartOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class SmartOrderSpecialistsRelationManager extends RelationManager
{
    protected static string $relationship = 'specialists';
    protected static ?string $title = 'Исполнители заявки';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('specialist_id')
                    ->label('Исполнитель')
                    ->relationship('specialist', 'title')
                    ->required()->searchable(),

                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        'waiting' => 'Ожидает',
                        'verify' => 'Подтверждено',
                        'canceled' => 'Отменено',
                    ])
                    ->default('waiting'),

                Forms\Components\Textarea::make('comment')->label('Комментарий'),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('specialist.id')->label('ID'),
                Tables\Columns\ImageColumn::make('specialist.specImages.0.image')
                    ->label('Фото')
                    ->square()
                    ->getStateUsing(fn($record) => optional($record->specialist->specImages->first())->image),
                Tables\Columns\TextColumn::make('specialist.title')->label('Исполнитель'),
                Tables\Columns\TextColumn::make('specialist.phone')->label('Телефон'),
                Tables\Columns\TextColumn::make('specialist.email')->label('Почта'),
                Tables\Columns\TextColumn::make('specialist.telegram')->label('Telegram'),
                Tables\Columns\TextColumn::make('specialist.vkontakte')->label('ВКонтакте'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->colors([
                        'warning' => 'waiting',
                        'success' => 'verify',
                        'danger' => 'canceled',
                    ]),
                Tables\Columns\TextColumn::make('comment')->label('Комментарий'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
