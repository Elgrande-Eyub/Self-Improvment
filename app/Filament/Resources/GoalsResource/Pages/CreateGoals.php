<?php

namespace App\Filament\Resources\GoalsResource\Pages;

use App\Filament\Resources\GoalsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGoals extends CreateRecord
{
    protected static string $resource = GoalsResource::class;

    protected function getRedirectUrl(): string
    {
    return $this->getResource()::getUrl('index');
    }
}
