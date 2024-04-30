<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\category;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected static string $model =  category::class;

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
