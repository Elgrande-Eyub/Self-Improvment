<?php

namespace App\Filament\Resources\ProgressLogsResource\Pages;

use App\Filament\Resources\ProgressLogsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgressLogs extends ListRecords
{
    protected static string $resource = ProgressLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
