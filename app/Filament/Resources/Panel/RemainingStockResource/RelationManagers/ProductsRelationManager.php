<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\RelationManagers;

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
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\RemainingStockResource;
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
                    ->preload()
                    ->native(false),

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
                        return $product ? $product->unit->unit : '';
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
            ]);
    }
}
