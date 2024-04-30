<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoalsResource\Pages;
use App\Filament\Resources\GoalsResource\RelationManagers;
use App\Models\Goals;
use App\priority;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
class GoalsResource extends Resource
{
    protected static ?string $model = Goals::class;

    protected static ?string $navigationIcon = 'phosphor-target-duotone';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Goal Creation')
                ->description('Enter and submit details related to a Goal.')
                ->icon('phosphor-target-duotone')
                ->schema([

                    TextInput::make('title')->prefixIcon('phosphor-target-duotone')->label('Goal Title')->helperText('Enter the Title of the Goal.')->required()->placeholder('Passive Income'),
                    TextInput::make('description')->label('Goal Description')->helperText('Enter the description of the Goal.')->required()->placeholder('Details about the Goal entered'),
                    Select::make('category_id')
                    ->prefix('Category Goals')
                    ->hiddenLabel()
                    ->required()
                    ->relationship(name: 'category', titleAttribute: 'title')
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->optionsLimit(5)
                    ->live()
                    ->editOptionForm([
                        TextInput::make('title')->type('text')->unique(ignoreRecord:true)->required(),
                    ])
                    ->createOptionForm([
                        TextInput::make('title')->type('text')->unique(ignoreRecord:true)->required(),
                    ]),
                    DatePicker::make('deadline')->prefixIcon('fontisto-date')->native(false)->label('Due Date')->helperText('Enter the due date for the Goal.')->required()->placeholder('2024/05/05'),

                ]),
                Section::make('Setup Sub Goals')
                ->description('Enter and submit details related to a sub Goal Progress.')
                ->icon('fluentui-steps-20-o')
                ->schema([
                    Repeater::make('Progresslogs')
                    ->relationship()
                    ->label('Sub Goals')
                    ->live(onBlur: true)
                    ->schema([
                        TextInput::make('activity_description')->label('Progress Title')->required()->placeholder('Progress Title'),
                        TextInput::make('progress_notes')->label('Progress Note')->helperText('Enter the Note of the Progress.')->required()->placeholder('Note'),
                        ])->columns(2)->cloneable()->itemLabel(fn (array $state): ?string => 'Sub Goal Title: '.$state['activity_description'] ?? null),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->icon('fas-child-reaching')->label('Goal Title')->searchable(),
                TextColumn::make('description')->icon('fluentui-text-description-20-o')->limit(50)->label('Goal Description')->searchable(),
                TextColumn::make('Progresslogs.activity_description')->badge()->label('Sub Goals')->searchable(),
                ToggleColumn::make('completed')
                ->afterStateUpdated(function ($record, $state) {
                    $notificationTitle = $state ? " Completed" : " Marked as Uncompleted";
                    self::performCompletekAction($record, $state, $notificationTitle);
                }),
                TextColumn::make('deadline')->icon('fontisto-date')->label('Due Date')->searchable()->date(),
            ])
            ->filters([
                //
            ])
            ->actions([

                ActionGroup::make([
                    Tables\Actions\EditAction::make()->hidden(fn($record)=>$record->trashed()),
                    Tables\Actions\ViewAction::make()->hidden(fn($record)=>$record->trashed()),
                    Tables\Actions\DeleteAction::make()->hidden(fn($record)=>$record->trashed()),
                ]),

                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->groups([
                Group::make('category.title')
                    ->collapsible(),
            ]);
    }

    public static function performCompletekAction(Goals $record,bool $completed, string $title): void {
        Notification::make()
            ->title("GOAL G". $record->id . $title )
            ->body($completed ? "Congratulations on completing your ".$record->title." GOAL 🥇" : "The GOAL '".$record->title."' has been marked as uncompleted.")
            ->icon('iconsax-out-status')
            ->send();
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
            'index' => Pages\ListGoals::route('/'),
            'create' => Pages\CreateGoals::route('/create'),
            'edit' => Pages\EditGoals::route('/{record}/edit'),
        ];
    }
}
