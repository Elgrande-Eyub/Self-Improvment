<?php

namespace App\Filament\Resources\ProgressLogsResource\Pages;

use App\Filament\Resources\ProgressLogsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgressLogs extends EditRecord
{
    protected static string $resource = ProgressLogsResource::class;

    protected function getRedirectUrl(): string
    {
    return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
