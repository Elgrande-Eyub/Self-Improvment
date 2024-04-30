<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'iconsax-bro-category';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Category Creation')
                ->description('Enter and submit details related to a category.')
                ->icon('iconsax-bro-category')
                ->schema([
                        TextInput::make('title')->label('Category Title')->helperText('Enter the name of the category.')->required()->placeholder('Health'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->icon('iconsax-bro-category')->label('Category Title')->searchable()->copyable(),
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
        DatePicker::make('created_from'),
        DatePicker::make('created_until')->default(now()),
                   ])
            ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                             fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                          )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
            })
            ])
            ->actions([

                Tables\Actions\EditAction::make()->hidden(fn($record)=>$record->trashed()),
                Tables\Actions\ViewAction::make()->hidden(fn($record)=>$record->trashed()),
                Tables\Actions\DeleteAction::make()->hidden(fn($record)=>$record->trashed())
                ->action(function($record){

                    if($record->Goals()->exists()  || $record->Resources()->exists()){
                        Notification::make()
                        ->danger()
                        ->title('Deletion Not Possible')
                        ->body('Deletion is not possible because there are Goals or Resources associated with this record.')
                        ->send();
                        return ;
                    }

                    return $record->delete();

             }),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
