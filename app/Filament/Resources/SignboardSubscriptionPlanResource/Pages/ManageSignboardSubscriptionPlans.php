<?php

namespace App\Filament\Resources\SignboardSubscriptionPlanResource\Pages;

use App\Filament\Resources\SignboardSubscriptionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSignboardSubscriptionPlans extends ManageRecords
{
    protected static string $resource = SignboardSubscriptionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
