<?php

namespace App\Filament\Resources\Panel\CashAdvanceResource\RelationManagers;

use App\Filament\Tables\AdvancePurchaseTable;
use App\Models\AdvancePurchase;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
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
                FileUpload::make('image')
                    ->rules(['image'])
                    ->nullable()
                    ->maxSize(1024)
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                Select::make('supplier_id')
                    ->required()
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('store_id')
                    ->required()
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                DatePicker::make('date')
                    ->rules(['date'])
                    ->required()
                    ->native(false),

                TextInput::make('subtotal_price')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('discount_price')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->step(1),

                Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

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
