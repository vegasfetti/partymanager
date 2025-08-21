<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;
    protected static ?string $navigationLabel = 'Обратная связь';
    protected static ?string $pluralModelLabel = 'Обратная связь';
    protected static ?string $navigationGroup = 'Заявки';
    protected static ?string $modelLabel = 'Обратную связь';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center';
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Имя')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('Телефон'),
                Forms\Components\TextInput::make('telegram')
                    ->label('Telegram'),
                Forms\Components\Textarea::make('message')
                    ->label('Сообщение')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Пользователь')
                    ->searchable()
                    ->relationship('user', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Имя')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Телефон')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('telegram')->label('Telegram')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('message')->label('Сообщение')->limit(50)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Пользователь')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Создано')->searchable()->sortable()->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}
