<?php

namespace App\Filament\Resources\GoalsResource\Pages;

use App\Filament\Resources\GoalsResource;
use App\Models\Goals;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListGoals extends ListRecords
{
    protected static string $resource = GoalsResource::class;

    protected static string $model =  Goals::class;

    public function getTabs():array {
        return[
            'all' => Tab::make('All')->icon('codicon-list-ordered')->badge(self::$model::query()->count()),



            'archivedRecords' => Tab::make('Trashed Records')
            ->badge(self::$model::query()->onlyTrashed()->count())
            ->icon('heroicon-s-trash')->modifyQueryUsing(function($query){
                return $query->onlyTrashed();
            })
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
