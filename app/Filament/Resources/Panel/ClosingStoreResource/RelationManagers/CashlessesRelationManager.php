<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\RelationManagers;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Forms\ImageInput;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\ClosingStoreResource;
use App\Models\AccountCashless;
use Filament\Resources\RelationManagers\RelationManager;

class CashlessesRelationManager extends RelationManager
{
    protected static string $relationship = 'cashlesses';

    protected static ?string $recordTitleAttribute = 'image';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                Select::make('account_cashless_id')
                    ->required()
                    ->relationship(
                        name: 'accountCashless',
                        modifyQueryUsing: function (Builder $query, callable $get) {
                            // Ambil store_id dari model yang sedang diakses
                            $storeId = $get('../../store_id');

                            // Filter accountCashless berdasarkan store_id yang sesuai
                            $query->where('store_id', $storeId);

                            return $query;
                        }
                    )
                    ->getOptionLabelFromRecordUsing(fn (AccountCashless $record) => $record->account_cashless_name)
                    ->preload(),

                ImageInput::make('image')

                        ->directory('images/Cashless'),

                TextInput::make('bruto_apl')
                    ->label('Bruto Application')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),

                TextInput::make('netto_apl')
                    ->label('Netto Application')
                    ->prefix('Rp')
                    ->nullable()
                    ->numeric(),

                TextInput::make('bruto_real')
                    ->label('Bruto Real')
                    ->prefix('Rp')
                    ->nullable()
                    ->numeric(),

                TextInput::make('netto_real')
                    ->label('Netto Real')
                    ->prefix('Rp')
                    ->nullable()
                    ->numeric(),

                ImageInput::make('image_canceled'),

                TextInput::make('canceled')
                    ->prefix('Rp')

                    ->directory('images/Cashless')
                    ->required()
                    ->numeric(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accountCashless.email'),

                ImageColumn::make('image')->visibility('public'),

                CurrencyColumn::make('bruto_apl')->label(__('crud.cashlesses.inputs.bruto_apl.label')),

                CurrencyColumn::make('netto_apl'),

                CurrencyColumn::make('bruto_real'),

                CurrencyColumn::make('netto_real'),

                ImageColumn::make('image_canceled'),

                TextColumn::make('canceled'),
            ])
            ->filters([])
            // ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
