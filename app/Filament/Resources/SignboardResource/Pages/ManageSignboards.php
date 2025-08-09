<?php

namespace App\Filament\Resources\SignboardResource\Pages;

use App\Filament\Resources\SignboardResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSignboards extends ManageRecords
{
    protected static string $resource = SignboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
