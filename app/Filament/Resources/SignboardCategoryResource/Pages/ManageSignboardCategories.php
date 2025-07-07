<?php

namespace App\Filament\Resources\SignboardCategoryResource\Pages;

use App\Filament\Resources\SignboardCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSignboardCategories extends ManageRecords
{
    protected static string $resource = SignboardCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
