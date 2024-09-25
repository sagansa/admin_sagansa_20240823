<?php

namespace App\Filament\Resources\Panel\InvoicePurchaseResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\InvoicePurchaseResource;
use App\Models\DetailInvoice;
use App\Models\DetailRequest;
use App\Models\InvoicePurchase;
use Filament\Forms\Components\Component;

class DetailInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'detailInvoices';

    protected static ?string $recordTitleAttribute = 'created_at';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                Select::make('detailRequest')
                    ->label('Detail Request')
                    ->relationship(
                        name: 'detailRequest',
                        modifyQueryUsing: function (Builder $query, $get) {
                    //         // Mengambil nilai 'store_id' dan 'payment_type_id' dari 'invoicePurchase'

                    //         // $storeId = InvoicePurchase::get('store_id');
                    //         // $paymentTypeId = InvoicePurchase::get('payment_type_id');

                    //         // $query->where('payment_type_id', $paymentTypeId);

                            // dd($query);
                            return $query;
                        }
                    )
                    ->getOptionLabelFromRecordUsing(fn (DetailRequest $record) => "{$record->detail_request_name}")
                    ->required()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan(['md' => 4]),

                TextInput::make('quantity_product')
                    ->required()
                    ->numeric()
                    ->step(1),

                // TextInput::make('quantity_invoice')
                //     ->nullable()
                //     ->numeric()
                //     ->step(1),

                // Select::make('unit_id')
                //     ->nullable()
                //     ->relationship('unit', 'name')
                //     ->searchable()
                //     ->preload(),

                TextInput::make('subtotal_invoice')
                    ->prefix('Rp ')
                    ->required()
                    ->numeric()
                    ->step(1),

                // TextInput::make('status')
                //     ->required()
                //     ->numeric()
                //     ->step(1),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('detailRequest.notes'),

                TextColumn::make('quantity_product'),

                TextColumn::make('subtotal_invoice'),

            ])
            ->filters([])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
