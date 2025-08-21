<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialistResource\Pages;
use App\Filament\Resources\SpecialistResource\RelationManagers;
use App\Models\Specialist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialistResource extends Resource
{
    protected static ?string $model = Specialist::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Специалисты';
    protected static ?string $pluralLabel = 'Специалисты';
    protected static ?string $modelLabel = 'Специалиста';
    protected static ?string $navigationGroup = 'Специалисты';
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'on_moderation')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Заголовок')->required(),
                Forms\Components\Textarea::make('description')->label('Описание')->required(),
                Forms\Components\TextInput::make('video_link')->label('Ссылка на видео'),
                Forms\Components\TextInput::make('price')->label('Цена')->numeric()->required(),
                Forms\Components\Select::make('price_type')->label('Тип цены')
                    ->options([
                        'per_hour' => 'За час',
                        'per_day' => 'За день',
                        'per_service' => 'За услугу',
                    ])->required(),
                Forms\Components\TextInput::make('phone')->label('Телефон')->required(),
                Forms\Components\TextInput::make('email')->label('Почта')->email()->required(),
                Forms\Components\TextInput::make('vkontacte')->label('Вконтакте'),
                Forms\Components\TextInput::make('telegram')->label('Телеграм'),
                Forms\Components\TextInput::make('website')->label('Сайт'),
                Forms\Components\Textarea::make('price_text')->label('Текст цены')->required(),

                Forms\Components\Textarea::make('skills')->label('Навыки или приемущества'),
                Forms\Components\Textarea::make('equipment')->label('Оборудование'),
                Forms\Components\Textarea::make('languages')->label('Языки')->default('Русский'),

                Forms\Components\Select::make('experience')->label('Опыт')
                    ->options([
                        'less_than_1' => 'Менее 1 года',
                        '1_3_years' => 'От 1 до 3 лет',
                        '3_5_years' => 'От 3 до 5 лет',
                        'more_than_5' => 'Более 5 лет',
                    ])->required(),

                Forms\Components\Select::make('subject_type')->label('Тип специалиста')
                    ->options([
                        'individual' => 'Частное лицо',
                        'company' => 'Компания',
                    ])->required(),

                Forms\Components\Toggle::make('is_contract')->label('Работа по договору'),

                Forms\Components\DateTimePicker::make('promoted_until')->label('Промо до'),

                Forms\Components\Select::make('status')->label('Статус')
                    ->options([
                        'on_moderation' => 'На модерации',
                        'verify' => 'Верефицирован',
                        'canceled' => 'Отменен',
                    ])->required(),

                Forms\Components\DateTimePicker::make('documents_verified_at')->label('Документы проверены'),

                Forms\Components\Select::make('city_id')->relationship('city', 'name')->required()->label('Город'),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required()->label('Пользователь')->searchable(),
                Forms\Components\Select::make('category_id')->relationship('category', 'name')->required()->label('Категория')->searchable(),
                Forms\Components\Select::make('subcategory_id')->relationship('subcategory', 'name')->required()->label('Подкатегория')->searchable(),

                // Фотки (до 10)
                Forms\Components\Repeater::make('specImages')->label('Фотографии')
                    ->relationship('specImages')
                    ->maxItems(10)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->directory('spec_upload')
                            ->image()
                            ->required(),
                    ]),

                // Портфолио (до 30)
                Forms\Components\Repeater::make('portfolios')->label('Портфолио')
                    ->relationship('portfolios')
                    ->maxItems(30)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->directory('portfolio_upload')
                            ->image()
                            ->nullable(), // теперь не обязательно
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('specImages.0.image')
                    ->label('Фото')
                    ->disk('public') // если используешь диск public
                    ->size(50),      // ширина в px
                Tables\Columns\TextColumn::make('title')->label('Заголовок')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->label('Цена')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->sortable()
                    ->badge()
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
                Tables\Columns\TextColumn::make('city.name')->label('Город')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Категория')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Обновлён')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'on_moderation' => 'На модерации',
                    'verify' => 'Подтвержден',
                    'canceled' => 'Отклонен',
                ])->label('Статус'),
                // по договору
                // Tables\Filters\SelectFilter::make('is_contract')->options([
                //     '1' => 'Да',
                //     '0' => 'Нет',
                // ])->label('Работа по договору'),
                // компания
                // Tables\Filters\SelectFilter::make('subject_type')->options([
                //     'individual' => 'Частное лицо',
                //     'company' => 'Компания',
                // ])->label('Тип специалиста'),
                // опыт работы

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSpecialists::route('/'),
            'create' => Pages\CreateSpecialist::route('/create'),
            'edit' => Pages\EditSpecialist::route('/{record}/edit'),
        ];
    }
}
