<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Invoices;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RequestPurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\RequestPurchaseResource\Pages;
use App\Filament\Resources\Panel\RequestPurchaseResource\RelationManagers;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class RequestPurchaseResource extends Resource
{
    protected static ?string $model = RequestPurchase::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Purchase';

    protected static ?string $cluster = Invoices::class;

    public static function getModelLabel(): string
    {
        return __('crud.requestPurchases.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.requestPurchases.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.requestPurchases.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'nickname')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    DatePicker::make('date')
                        ->default('today')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'Processes',
                            '2' => 'Approved',
                            '3' => 'Done',
                            '4' => 'Rejected',
                            '5' => 'Not Valid',
                            '6' => 'Not Used',
                        ]),
                ]),
            ]),

            Section::make()->schema([
                self::getItemsRepeater(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('store.nickname'),

                TextColumn::make('date'),

                TextColumn::make('user.name'),

                // TextColumn::make('status'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequestPurchases::route('/'),
            'create' => Pages\CreateRequestPurchase::route('/create'),
            'view' => Pages\ViewRequestPurchase::route('/{record}'),
            'edit' => Pages\EditRequestPurchase::route('/{record}/edit'),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('detailRequests')
            ->relationship()
            ->minItems(1)
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->distinct()
                    ->columnSpan(4),
                TextInput::make('quantity_plan')
                    ->required()
                    ->minValue(1)
                    ->numeric()
                    ->columnSpan(3),
                Select::make('status')
                    ->options([
                            '1' => 'Processed',
                            '2' => 'Approved',
                            '3' => 'Done',
                            '4' => 'Rejected',
                            '5' => 'Not Valid',
                            '6' => 'Not Used',
                        ])
                    ->columnSpan(3),
                    // ->visible(fn () => Auth::user()->hasRole('super_admin')),
                // Select::make('status')
                //     ->options([
                //             '1' => 'Processed',
                //         ])
                //     ->columnSpan(3),
                    // ->visible(fn () => Auth::user()->hasRole('staff')),

            ])
            ->columns([
                'md' => 10,
            ])
            ->defaultItems(1);
    }
}
