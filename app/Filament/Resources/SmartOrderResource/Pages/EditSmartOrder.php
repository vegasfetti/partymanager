<?php

namespace App\Filament\Resources\SmartOrderResource\Pages;

use App\Filament\Resources\SmartOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmartOrder extends EditRecord
{
    protected static string $resource = SmartOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
