<?php

namespace App\Filament\Resources\Panel\ProductionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\ProductionResource;
use App\Models\DetailInvoice;
use App\Models\ProductionMainFrom;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\ActionGroup;

class ProductionMainFromsRelationManager extends RelationManager
{
    protected static string $relationship = 'productionMainFroms';

    protected static ?string $recordTitleAttribute = 'created_at';

    public function form(Schema $form): Schema
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
            ->headerActions([\Filament\Actions\CreateAction::make()])
            ->actions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function afterCreate(ProductionMainFrom $record)
    {
        $record->detailInvoice->update(['status' => 2]);
    }
}
