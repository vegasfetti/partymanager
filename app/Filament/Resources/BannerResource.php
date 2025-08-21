<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Баннеры';
    protected static ?string $pluralLabel = 'Баннеры';
    protected static ?string $modelLabel = 'Баннер';
    protected static ?string $navigationGroup = 'Контент';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Заголовок')->required(),
                TextInput::make('subtitle')->label('Подзаголовок'),
                TextInput::make('link')->label('Ссылка'),
                FileUpload::make('image')
                    ->image()
                    ->label('Изображение')
                    ->required()
                    ->imageEditor()
                    ->directory('banner_upload'),
                Toggle::make('status')->label('Активен')->default(true),
                Toggle::make('is_promo')->label('Промо')->default(false),
                Select::make('type')
                ->label('Отображение на')
                    ->options([
                        'main' => 'Главный',
                        'specialists' => 'Специалисты',
                    ])->required()
                    ->default('main'),
                Select::make('city_id')
                    ->label('Город')
                    ->relationship('city', 'name')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                ImageColumn::make('image'),
                BooleanColumn::make('status')->label('Активен'),
                BooleanColumn::make('is_promo')->label('Промо'),
                TextColumn::make('type'),
                TextColumn::make('city.name')->label('Город'),
                TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
