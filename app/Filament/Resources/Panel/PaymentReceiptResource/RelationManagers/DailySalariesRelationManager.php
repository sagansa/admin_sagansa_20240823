<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

use App\Filament\Forms\DateInput;
use App\Filament\Tables\DailySalaryTable;
use App\Filament\Forms\StatusInput;
use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\PaymentReceiptResource;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Model;

class DailySalariesRelationManager extends RelationManager
{
    protected static string $relationship = 'dailySalaries';

    protected static ?string $recordTitleAttribute = 'date';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                StoreSelect::make('store_id')
                    ->required(),

                Select::make('shift_store_id')
                    ->required()
                    ->relationship('shiftStore', 'name')
                    ->preload(),

                DateInput::make('date'),

                TextInput::make('amount')
                    ->summarize(Sum::make()
                        ->prefix('Rp ')
                        ->label('')
                        ->numeric(thousandsSeparator: '.'))
                    ->required()
                    ->numeric()
                    ->step(1),

                Select::make('payment_type_id')
                    ->required()
                    ->relationship('paymentType', 'name')
                    ->preload(),

                Select::make('status')
                    ->options([
                        '1' => 'belum diperiksa',
                        '2' => 'siap dibayar',
                        '3' => 'dibayar',
                        '4' => 'tidak valid'
                    ])
                    ->required()
                    ->preload(),

                Select::make('created_by_id')
                    ->relationship('createdBy', 'name')
                    ->nullable()
                    ->searchable()
                    ->preload(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns(
                DailySalaryTable::schema()
            )

            ->filters([])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),

                // Tables\Actions\AttachAction::make()->form(
                //     fn(Tables\Actions\AttachAction $action): array => [
                //         $action->getRecordSelect()->preload(),
                //         // TextInput::make('name')
                //     ]
                // )->multiple(),

                // Tables\Actions\AttachAction::make()->preloadRecordSelect()->multiple(),
                // TextInput::make('amount')

                // Tables\Actions\AttachAction::make()
                //     ->recordSelect(
                //         fn (Select $select) => $select->placeholder('Select a post'),
                //     )
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make()
                    ->action(function ($record) {
                        $record->pivot->delete();
                        $record->update(['status' => 3]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\DetachBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->paymentReceipts()->detach();
                                $record->update(['status' => 3]);
                            }
                        }),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->payment_for === 2;
    }
}
