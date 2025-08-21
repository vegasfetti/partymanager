<?php

namespace App\Filament\Resources\SmartOrderResource\Pages;

use App\Filament\Resources\SmartOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmartOrders extends ListRecords
{
    protected static string $resource = SmartOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
