<?php

namespace App\Filament\Resources\Panel\ProductionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\ProductionResource;
use App\Models\DetailInvoice;
use App\Models\ProductionMainFrom;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ActionGroup;

class ProductionMainFromsRelationManager extends RelationManager
{
    protected static string $relationship = 'productionMainFroms';

    protected static ?string $recordTitleAttribute = 'created_at';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([

                Select::make('detail_invoice_id')
                    ->label('Detail Invoice')
                    ->required()
                    ->relationship(
                        name: 'detailInvoice',
                        modifyQueryUsing: fn (Builder $query) => $query
                            // ->join('detail_requests', 'detail_invoices.detail_request_id', '=', 'detail_requests.id')
                            // ->join('products', 'detail_requests.product_id', '=', 'products.id')
                            ->where('detail_invoices.status', '1')
                            // ->where('products.material_group_id', '3')
                            ->orderBy('detail_invoices.id', 'asc'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (DetailInvoice $record) => "{$record->detail_invoice_name}")
                    ->searchable()
                    ->preload()
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('detailInvoice.detailRequest.product.name'),
                TextColumn::make('detailInvoice.quantity_product'),
                TextColumn::make('detailInvoice.detailRequest.product.unit.unit'),
                TextColumn::make('detailInvoice.invoicePurchase.date')
            ])
            ->filters([])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function afterCreate(ProductionMainFrom $record)
    {
        $record->detailInvoice->update(['status' => 2]);
    }
}
