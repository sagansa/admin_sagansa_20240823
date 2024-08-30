<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Invoices;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\InvoicePurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;
use App\Filament\Resources\Panel\InvoicePurchaseResource\RelationManagers;
use App\Models\DetailRequest;
use App\Models\PaymentType;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Unit;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Marvinosswald\FilamentInputSelectAffix\TextInputSelectAffix;

class InvoicePurchaseResource extends Resource
{
    protected static ?string $model = InvoicePurchase::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Purchase';

    protected static ?string $cluster = Invoices::class;

    public static function getModelLabel(): string
    {
        return __('crud.invoicePurchases.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.invoicePurchases.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.invoicePurchases.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 2])->schema(
                    static::getDetailsFormHeadSchema()),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    static::getItemsRepeater()
                ])
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema(
                    static::getDetailsFormBottomSchema()),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('store.nickname'),

                TextColumn::make('supplier.name'),

                TextColumn::make('date'),

                // TextColumn::make('taxes'),

                // TextColumn::make('discounts'),

                TextColumn::make('total_price'),

                TextColumn::make('payment_status'),

                TextColumn::make('order_status'),

                TextColumn::make('createdBy.name'),
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
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoicePurchases::route('/'),
            'create' => Pages\CreateInvoicePurchase::route('/create'),
            'view' => Pages\ViewInvoicePurchase::route('/{record}'),
            'edit' => Pages\EditInvoicePurchase::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {
        return [
            ImageInput::make('image'),

            Select::make('payment_type_id')
                ->required()
                ->relationship(
                    name: 'paymentType',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                )
                ->getOptionLabelFromRecordUsing(fn (PaymentType $record) => "{$record->name}")
                ->disableOptionWhen(fn (string $value): bool => $value === '1')
                ->in(fn (Select $component): array => array_keys($component->getEnabledOptions()))
                ->preload()
                ->native(false),

            Select::make('store_id')
                ->required()
                ->relationship(
                    name: 'store',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status','<>', '8'),
                )
                ->getOptionLabelFromRecordUsing(fn (Store $record) => "{$record->nickname}")
                ->searchable()
                ->preload()
                ->native(false),

            Select::make('supplier_id')
                ->required()
                ->relationship(
                    name: 'supplier',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status','<>', '3'),
                )
                ->getOptionLabelFromRecordUsing(fn (Supplier $record) => "{$record->supplier_name}")
                ->searchable()
                ->preload()
                ->native(false),

            DateInput::make('date'),

            Select::make('payment_status')
                ->required()
                ->preload()
                ->options([
                    '1' => '<span class="text-yellow-500">belum dibayar</span>',
                    '2' => '<span class="text-green-500">sudah dibayar</span>',
                    '3' => '<span class="text-red-500">tidak valid</span>',
                ])
                ->allowHtml()
                ->native(false),

            Select::make('order_status')
                ->required()
                ->preload()
                ->options([
                    '1' => 'belum diterima',
                    '2' => 'sudah diterima',
                    '3' => 'dikembalikan',
                ])
                ->native(false),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return TableRepeater::make('detailInvoices')
            ->hiddenLabel()
            ->minItems(1)
            ->columns(['md' => 12])
            ->relationship()
            ->schema([
                Select::make('detail_request_id')
                    ->relationship('detailRequest.product', 'name')
                    ->native(false)
                    ->reactive()
                    ->required()
                    ->columnSpan(['md' => 4]),

                TextInput::make('quantity_product')
                    ->required()
                    ->suffix(function (Get $get) {
                        $detailRequest = DetailRequest::find($get('detail_request_id'));
                        return $detailRequest ? $detailRequest->product->unit->unit : '';
                    })
                    ->columnSpan(['md' => 2]),

                TextInputSelectAffix::make('quantity_invoice')
                    ->required()
                    ->select(fn() => Select::make('unit_id')
                        ->native(false)
                        ->extraAttributes([
                            // 'class' => 'w-[36px]' // if you want to constrain the selects size, depending on your usecase
                        ])
                        ->options(Unit::all()->pluck('unit', 'id'))
                    )
                    ->columnSpan(['md' => 3]),

                // Select::make('unit_id')
                //     ->native(false)
                //     ->relationship('unit', 'unit')
                //     ->columnSpan(['md' => 2]),

                CurrencyInput::make('subtotal_price')
                    ->columnSpan(['md' => 3]),
            ]);
    }

    public static function getDetailsFormBottomSchema(): array
    {
        return[
            CurrencyInput::make('taxes'),

            CurrencyInput::make('discounts'),

            CurrencyInput::make('total_price'),

            Notes::make('notes'),
        ];
    }
}
