<?php

namespace App\Filament\Resources\TasksResource\Pages;

use App\Filament\Resources\TasksResource;
use App\Models\Tasks;
use App\priority;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListTasks extends ListRecords
{
    protected static string $resource = TasksResource::class;

    protected static string $model =  Tasks::class;

    public function getTabs():array {
        return[
            'all' => Tab::make('All')->icon('codicon-list-ordered')->badge(self::$model::query()->count()),


            'High' => Tab::make('High')->icon(priority::High->getIcon())->badge(self::$model::query()->where('priority', priority::High->value)->count())->modifyQueryUsing(function($query){
                return $query->where('priority', priority::High->value);
            }),

            'Medium' => Tab::make('Medium')->icon(priority::Medium->getIcon())->badge(self::$model::query()->where('priority', priority::Medium->value)->count())->modifyQueryUsing(function($query){
                return $query->where('priority', priority::Medium->value);
            }),

            'Low' => Tab::make('Low')->icon(priority::Low->getIcon())->badge(self::$model::query()->where('priority', priority::Low->value)->count())
            ->modifyQueryUsing(function($query){
                return $query->where('priority', priority::Low->value);
            }),


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
