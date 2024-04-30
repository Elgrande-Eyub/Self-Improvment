<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TasksResource\Pages;
use App\Filament\Resources\TasksResource\RelationManagers;
use App\Models\Tasks;
use App\priority;
use DateTime;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\ToggleColumn;

class TasksResource extends Resource
{
    protected static ?string $model = Tasks::class;

    protected static ?string $navigationIcon = 'carbon-task-add';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Task Creation')
                ->description('Enter and submit details related to a Task.')
                ->icon('carbon-task-add')
                ->schema([


                    TextInput::make('title')->prefixIcon('carbon-task-add')->label('Task Title')->helperText('Enter the name of the Task.')->required()->placeholder('Health'),
                    TextInput::make('description')->label('Task Description')->helperText('Enter the description of the task.')->required()->placeholder('Details about the task entered'),
                    DatePicker::make('deadline')->prefixIcon('fontisto-date')->native(false)->label('Due Date')->helperText('Enter the due date for the task.')->required()->placeholder('2024/05/05'),

                    ToggleButtons::make('priority')
                    ->label('Priority')
                    ->helperText('the priority level of the task')
                    ->inline()
                    ->options(priority::class)
                    ->required(),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->icon('carbon-task-add')->label('Task Title')->searchable(),
                TextColumn::make('description')->icon('fluentui-text-description-20-o')->limit(50)->label('Task Description')->searchable(),
                TextColumn::make('priority')->sortable()
                ->badge(),
                TextColumn::make('deadline')->icon('fontisto-date')->label('Due Date')->searchable()->date(),
                IconColumn::make('completed')->label('isCompleted')
                ->boolean(),
                ToggleColumn::make('completed')
                ->afterStateUpdated(function ($record, $state) {
                    $notificationTitle = $state ? " Completed" : " Marked as Uncompleted";
                    self::performCompletekAction($record, $state, $notificationTitle);
                })
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
            ]);
    }

   public static function performCompletekAction(tasks $record,bool $completed, string $title): void {
        Notification::make()
            ->title("Task T". $record->id . $title )
            ->body($completed ? "Congratulations on completing your ".$record->title." task 🥇" : "The task '".$record->title."' has been marked as uncompleted.")
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTasks::route('/create'),
            'edit' => Pages\EditTasks::route('/{record}/edit'),
        ];
    }
}
