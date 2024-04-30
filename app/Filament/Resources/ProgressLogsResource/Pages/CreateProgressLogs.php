<?php

namespace App\Filament\Resources\ProgressLogsResource\Pages;

use App\Filament\Resources\ProgressLogsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProgressLogs extends CreateRecord
{
    protected static string $resource = ProgressLogsResource::class;

    protected function getRedirectUrl(): string
    {
    return $this->getResource()::getUrl('index');
    }


}
