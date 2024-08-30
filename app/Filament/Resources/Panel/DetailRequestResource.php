<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Invoices;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DetailRequest;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\DetailRequestResource\Pages;
use App\Filament\Resources\Panel\DetailRequestResource\RelationManagers;
use Filament\Tables\Columns\SelectColumn;

class DetailRequestResource extends Resource
{
    protected static ?string $model = DetailRequest::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Purchase';

    protected static ?string $cluster = Invoices::class;

    public static function getModelLabel(): string
    {
        return __('crud.detailRequests.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.detailRequests.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.detailRequests.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Section::make()->schema([
            //     Grid::make(['default' => 1])->schema([
            //         Select::make('product_id')
            //             ->required()
            //             ->relationship('product', 'name')
            //             ->searchable()
            //             ->preload()
            //             ->native(false),

            //         TextInput::make('quantity_plan')
            //             ->required()
            //             ->numeric()
            //             ->step(1),

            //         TextInput::make('status')
            //             ->required()
            //             ->numeric()
            //             ->step(1),

            //         RichEditor::make('notes')
            //             ->nullable()
            //             ->string()
            //             ->fileAttachmentsVisibility('public'),

            //         Select::make('request_purchase_id')
            //             ->required()
            //             ->relationship('requestPurchase', 'date')
            //             ->searchable()
            //             ->preload()
            //             ->native(false),

            //         Select::make('store_id')
            //             ->required()
            //             ->relationship('store', 'name')
            //             ->searchable()
            //             ->preload()
            //             ->native(false),

            //         Select::make('payment_type_id')
            //             ->required()
            //             ->relationship('paymentType', 'name')
            //             ->searchable()
            //             ->preload()
            //             ->native(false),
            //     ]),
            // ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product'),
                TextColumn::make('requestPurchase.date')
                    ->label('Request Date'),
                TextColumn::make('product.paymentType.name')
                    ->label('Payment Type'),
                TextColumn::make('store.nickname')
                    ->label('Store'),
                TextColumn::make('quantity_plan')
                    ->label('Quantity Plan'),
                TextColumn::make('quantityPurchase.quantity')
                    ->label('Quantity Purchase'),
                TextColumn::make('requestPurchase.user.name')
                    ->label('Request By'),
                SelectColumn::make('status')
                    // ->native(false)
                    ->options([
                        '1' => 'Processed',
                        '2' => 'Approved',
                        '3' => 'Done',
                        '4' => 'Rejected',
                        '5' => 'Not Valid',
                        '6' => 'Not Used',
                    ]),
            ])
            ->filters([])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetailRequests::route('/'),
            'create' => Pages\CreateDetailRequest::route('/create'),
            'view' => Pages\ViewDetailRequest::route('/{record}'),
            'edit' => Pages\EditDetailRequest::route('/{record}/edit'),
        ];
    }
}
