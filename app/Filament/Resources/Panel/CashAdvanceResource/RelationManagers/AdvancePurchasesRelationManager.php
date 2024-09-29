<?php

namespace App\Filament\Resources\Panel\CashAdvanceResource\RelationManagers;

use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\StoreSelect;
use App\Filament\Forms\SupplierSelect;
use App\Filament\Tables\AdvancePurchaseTable;
use App\Models\AdvancePurchase;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;

class AdvancePurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'advancePurchases';

    protected static ?string $recordTitleAttribute = 'image';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                ImageInput::make('image')
                    ->directory('images/AdvancePurchase'),

                SupplierSelect::make('supplier_id'),

                StoreSelect::make('store_id')
                    ->required(),

                DateInput::make('date'),

                CurrencyInput::make('subtotal_price'),

                CurrencyInput::make('discount_price'),

                CurrencyInput::make('total_price'),

                Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->reactive() // Ensures that the form updates reactively
                    ->afterStateUpdated(function (callable $state) {
                        if ($state == 2) { // 'valid' status
                            AdvancePurchase::calculateTotalPrice();
                        }
                    }),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(AdvancePurchaseTable::schema())
            //
            ->filters([])
            // ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
    }
}
