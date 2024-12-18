<?php

namespace App\Filament\Resources\Panel\StorageStockResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\Product;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
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
                // Tables\Actions\CreateAction::make(),

                // Tables\Actions\AttachAction::make()->form(
                //     fn(Tables\Actions\AttachAction $action): array => [
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
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),

                //     Tables\Actions\DetachBulkAction::make(),
                // ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
