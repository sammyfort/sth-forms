<?php

namespace App\Filament\Resources\SignboardSubscriptionResource\Pages;

use App\Filament\Resources\SignboardSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSignboardSubscriptions extends ManageRecords
{
    protected static string $resource = SignboardSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
