<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Пользователи';

    public static function getModelLabel(): string
    {
        return 'Пользователя';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Пользователи';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('image')
                    ->label('Аватар')
                    ->image()
                    ->directory('profile_upload')   // где хранятся файлы
                    ->disk('public')                // использовать storage/app/public
                    ->default('profile_upload/default-img.png') // путь относительно диска
                    ->required(),                   // если нужно, можно убрать

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true),

                DateTimePicker::make('email_verified_at')
                    ->label('Верифицирован')
                    ->nullable(),


                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state)) // сохраняем только если что-то введено
                    ->maxLength(255)
                    ->rule('nullable') // чтобы валидация не ругалась
                    ->afterStateHydrated(function ($component, $state) {
                        $component->state(null); // очищаем поле при редактировании
                    }),

            ]);
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // оставляем старый пароль
        }

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('name')->label('Имя')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('image')->label('Аватар'),
                Tables\Columns\TextColumn::make('email')->label('Email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->label('Верифицирован')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Создан')->dateTime()->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
