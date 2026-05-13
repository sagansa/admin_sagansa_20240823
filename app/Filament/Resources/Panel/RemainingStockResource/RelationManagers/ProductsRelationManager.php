<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\Product;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->autofocus(),

                Select::make('unit_id')
                    // ->required()
                    ->disabled()
                    ->relationship('unit', 'unit')
                    ->searchable()
                    ->preload(),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->step(1)
                    ->autofocus(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('quantity')
                    ->suffix(function ($record) {
                        $product = Product::find($record->id);
                        return $product ? ' ' . $product->unit->unit . ' ' : ''; // added a space before and after the unit
                    }),
            ])
            ->filters([])
            ->headerActions([
                // \Filament\Actions\CreateAction::make(),

                // \Filament\Actions\AttachAction::make()->form(
                //     fn(\Filament\Actions\AttachAction $action): array => [
                //         $action->getRecordSelect(),

                //         TextInput::make('quantity')
                //             ->required()
                //             ->numeric()
                //             ->step(1)
                //             ->autofocus(),
                //     ]
                // ),
            ])
            ->actions([
                // \Filament\Actions\EditAction::make(),
                // \Filament\Actions\DeleteAction::make(),
                // \Filament\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make(),

                //     \Filament\Actions\DetachBulkAction::make(),
                // ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
