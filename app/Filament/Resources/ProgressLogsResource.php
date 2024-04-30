<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgressLogsResource\Pages;
use App\Filament\Resources\ProgressLogsResource\RelationManagers;
use App\Models\ProgressLogs;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;

class ProgressLogsResource extends Resource
{
    protected static ?string $model = ProgressLogs::class;

    protected static ?string $navigationIcon = 'fluentui-steps-20-o';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sub Goal')
                ->icon('fluentui-steps-20-o')
                ->schema([
                    TextInput::make('activity_description')->label('Progress Title')->required()->placeholder('Progress Title'),
                TextInput::make('progress_notes')->label('Progress Note')->helperText('Enter the Note of the Progress.')->required()->placeholder('Note'),
             ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('activity_description')->icon('fluentui-steps-20-o')->badge()->limit(50)->label('Sub Goal Title')->searchable(),
                TextColumn::make('progress_notes')->icon('fluentui-text-description-20-o')->label('Sub Goals Description')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\EditAction::make()->hidden(fn($record)=>$record->trashed()),
                Tables\Actions\ViewAction::make()->hidden(fn($record)=>$record->trashed()),
                Tables\Actions\DeleteAction::make()->hidden(fn($record)=>$record->trashed()),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultGroup('Goals.title');
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
            'index' => Pages\ListProgressLogs::route('/'),
            'create' => Pages\CreateProgressLogs::route('/create'),
            'edit' => Pages\EditProgressLogs::route('/{record}/edit'),
        ];
    }
}
