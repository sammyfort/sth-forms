<?php

namespace App\Filament\Resources\ContactUsMessageResource\Pages;

use App\Filament\Resources\ContactUsMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageContactUsMessages extends ManageRecords
{
    protected static string $resource = ContactUsMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
