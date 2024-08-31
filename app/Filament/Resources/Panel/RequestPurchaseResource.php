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
use App\Models\DetailRequest;
use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
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

                TextColumn::make('detailRequests')
                    ->label('Orders')
                    ->formatStateUsing(function (RequestPurchase $record) {
                        return implode('<br>', $record->detailRequests->map(function ($item) {
                            $statusLabels = [
                                '1' => '<span class="badge bg-warning">process</span>',
                                '2' => '<span class="badge bg-success">done</span>',
                                '3' => '<span class="badge bg-danger">reject</span>',
                                '4' => '<span class="badge bg-info">approved</span>',
                                '5' => '<span class="badge bg-secondary">not valid</span>',
                                '6' => '<span class="bg-gray-500 badge">not used</span>',
                            ];

                            // Pilih label status berdasarkan nilai status
                            $status = $statusLabels[$item->status] ?? '<span class="badge bg-default">unknown</span>';

                            return "{$item->product->name} = {$item->quantity_plan} {$item->product->unit->unit} ({$status})";
                        })->toArray());
                    })
                    ->html() // Mengizinkan HTML dalam kolom
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

                TextColumn::make('user.name'),

                // TextColumn::make('status'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
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
                    ->preload()
                    ->searchable()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->distinct()
                    ->reactive()
                    ->columnSpan(4),
                TextInput::make('quantity_plan')
                    ->required()
                    ->minValue(1)
                    ->numeric()
                    ->suffix(function (Get $get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->columnSpan(2),
                Placeholder::make('status')
                    ->hidden(fn ($operation) => $operation === 'create')
                    ->content(fn (DetailRequest $record): string => [
                        '1' => __('process'),
                        '2' => __('done'),
                        '3' => __('reject'),
                        '4' => __('approved'),
                        '5' => __('not valid'),
                        '6' => __('not used'),
                    ][$record->status])
                    ->columnSpan(2),
                Hidden::make('status') // belum selesai
                    ->default(1),
                Hidden::make('store_id') // belum selesai
                    ->default(1),
                Hidden::make('payment_type_id') //belum selesai
                    ->default(1),
            ])
            ->columns([
                'md' => 8,
            ])
            ->defaultItems(1);
    }
}
