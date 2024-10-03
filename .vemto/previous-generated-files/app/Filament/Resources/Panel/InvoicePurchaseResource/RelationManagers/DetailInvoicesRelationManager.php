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

class DetailInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'detailInvoices';

    protected static ?string $recordTitleAttribute = 'created_at';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                Select::make('detail_request_id')
                    ->required()
                    ->relationship('detailRequest', 'notes')
                    ->searchable()
                    ->preload()
                    ->native(false),

                TextInput::make('quantity_product')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('quantity_invoice')
                    ->nullable()
                    ->numeric()
                    ->step(1),

                Select::make('unit_id')
                    ->nullable()
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->step(1),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('detailRequest.notes'),

                TextColumn::make('quantity_product'),

                TextColumn::make('quantity_invoice'),

                TextColumn::make('unit.name'),

                TextColumn::make('status'),
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
