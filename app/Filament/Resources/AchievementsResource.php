<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementsResource\Pages;
use App\Filament\Resources\AchievementsResource\RelationManagers;
use App\Models\Achievements;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AchievementsResource extends Resource
{
    protected static ?string $model = Achievements::class;

    protected static ?string $navigationIcon = 'gmdi-auto-awesome-o';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Goal Creation')
                ->description('Enter and submit details related to a Goal.')
                ->icon('phosphor-target-duotone')
                ->schema([
                    TextInput::make('title')->prefixIcon('phosphor-target-duotone')->label('Achievement Title')->helperText('Enter the Title of the Achievements.')->required()->placeholder('Passive Income'),
                TextInput::make('description')->prefixIcon('phosphor-target-duotone')->label('Achievement Title')->helperText('Enter the Title of the Achievements.')->required()->placeholder('Passive Income'),

            ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->icon('fas-child-reaching')->label('Goal Title')->searchable(),
                TextColumn::make('description')->icon('fluentui-text-description-20-o')->limit(50)->label('Goal Description')->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievements::route('/create'),
            'edit' => Pages\EditAchievements::route('/{record}/edit'),
        ];
    }
}
