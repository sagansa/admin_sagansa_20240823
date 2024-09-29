<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Filters\SelectPaymentTypeFilter;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\CurrencyRepeaterInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StoreSelect;
use App\Filament\Forms\SupplierSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\InvoicePurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;
use App\Filament\Tables\InvoicePurchaseTable;
use App\Models\DetailRequest;
use App\Models\Supplier;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class InvoicePurchaseResource extends Resource
{
    protected static ?string $model = InvoicePurchase::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Invoice';

    protected static ?string $pluralLabel = 'Invoices';

    protected static ?string $cluster = Purchases::class;

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

            Group::make()
                ->schema([
                    Section::make()
                        ->schema(static::getDetailsFormHeadSchema())
                        ->columns(2),

                    Section::make()
                        ->schema([static::getItemsRepeater()]),
                ])
                ->columnSpan(['lg' => 2]),

            Section::make()
                ->schema(static::getDetailsFormBottomSchema())
                ->columnSpan(['lg' => 1]),
        ])
        ->columns(3);
    }

    public static function table(Table $table): Table
    {
        $invoicePurchases = InvoicePurchase::query();

        if (!Auth::user()->hasRole('admin')) {
            $invoicePurchases->where('created_by_id', Auth::id());
        }

        return $table
            ->query($invoicePurchases)
            ->poll('60s')
            ->columns(
                InvoicePurchaseTable::schema()
            )
            ->filters([
                SelectPaymentTypeFilter::make('payment_type_id'),

                SelectFilter::make('store_id')
                    ->label('Store')
                    ->searchable()
                    ->relationship('store', 'nickname'),

                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->searchable()
                    ->relationship('supplier', 'name'),

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setPaymentStatusToOne')
                        ->label('Set Payment Status to Belum Dibayar')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            InvoicePurchase::whereIn('id', $records->pluck('id'))->update(['payment_status' => 1]);
                        })
                        ->color('warning'),
                    Tables\Actions\BulkAction::make('setOrderStatusToOne')
                        ->label('Set Order Status to Belum Diterima')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            InvoicePurchase::whereIn('id', $records->pluck('id'))->update(['order_status' => 1]);
                        })
                        ->color('warning'),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
            ImageInput::make('image')
                ->directory('images/InvoicePurchase'),

            SupplierSelect::make('supplier_id'),

            StoreSelect::make('store_id'),

            Select::make('payment_type_id')
                ->required()
                ->reactive()
                ->relationship(
                    name: 'paymentType',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                )
                ->default(2)
                ->inlineLabel()
                ->preload(),
                // ->afterStateUpdated(function (Set $set) {
                    // $set('detailInvoices', null);
                // }),

            DateInput::make('date'),

            Select::make('payment_status')
                ->required(fn () => Auth::user()->hasRole('admin'))
                ->disabled(fn () => !Auth::user()->hasRole('admin'))
                ->hidden(fn ($operation) => $operation === 'create')
                ->preload()
                ->inlineLabel()
                ->options([
                    '1' => 'belum dibayar',
                    '2' => 'sudah dibayar',
                    '3' => 'tidak valid',
                ])
                ,

            Select::make('order_status')
                ->required()
                ->hidden(fn ($operation) => $operation === 'create')
                ->preload()
                ->inlineLabel()
                ->options([
                    '1' => 'belum diterima',
                    '2' => 'sudah diterima',
                    '3' => 'dikembalikan',
                ])
                ,
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('detailInvoices')
            ->hiddenLabel()
            ->minItems(1)
            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, InvoicePurchase $record): array {
                $data['status'] = '3';

                return $data;
            })
            ->columns(['md' => 8])
            ->relationship()
            ->schema([
                Select::make('detail_request_id')
                    // ->label('Detail Order')
                    ->hiddenLabel()
                    ->placeholder('product')
                    ->relationship(
                        name: 'detailRequest',
                        modifyQueryUsing: function (Builder $query, callable $get) {
                            $paymentTypeId = $get('../../payment_type_id');
                            $storeId = $get('../../store_id');

                            $paymentTypeFilter = null;
                                if ($paymentTypeId == '2') {
                                    $paymentTypeFilter = '2';
                                }

                                $queryFinal = $query
                                    ->where('store_id', $storeId)
                                    ->when($paymentTypeFilter, function ($query) use ($paymentTypeFilter) {
                                        $query->where('payment_type_id', $paymentTypeFilter);
                                    })
                                    ->orderBy('id', 'desc');
                            return $queryFinal;
                        }
                    )
                    ->getOptionLabelFromRecordUsing(fn (DetailRequest $record) => "{$record->detail_request_name}")

                    ->required()
                    ->preload()
                    ->searchable()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan(['md' => 4]),

                TextInput::make('quantity_product')
                    ->hiddenLabel()
                    ->placeholder('quantity')
                    ->required()
                    ->reactive()
                    ->minValue(1)
                    ->default(1)
                    ->suffix(function (Get $get) {
                        $detailRequest = DetailRequest::find($get('detail_request_id'));
                        return $detailRequest ? $detailRequest->product->unit->unit : '';
                    })
                    ->columnSpan(['md' => 2]),

                CurrencyRepeaterInput::make('subtotal_invoice')
                    ->placeholder('subtotal')
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotalPrice($get, $set);
                    })

                    ->columnSpan(['md' => 2]),
            ])
            ->afterStateUpdated(function (Get $get, Set $set) {
                self::updateTotalPrice($get, $set);
            });
    }

    public static function getDetailsFormBottomSchema(): array
    {
        return[
            CurrencyInput::make('taxes')
                ->reactive()
                ->debounce(2000)
                ->inlineLabel()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    self::updateTotalPrice($get, $set);
                }),

            CurrencyInput::make('discounts')
                ->reactive()
                ->debounce(2000)
                ->inlineLabel()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    self::updateTotalPrice($get, $set);
                }),

            CurrencyInput::make('total_price')
                ->inlineLabel()
                ->readOnly(),

            Notes::make('notes'),
        ];
    }

    protected static function updateTotalPrice(Get $get, Set $set): void
    {
        // Get the repeater items or initialize to an empty array if null
        $repeaterItems = $get('detailInvoices') ?? [];

        $subtotalPrice = 0;
        $totalPrice = 0;
        $taxes = 0;
        $discounts = 0;

        $taxes = $get('taxes') !== null ? (int) $get('taxes') : 0;
        $discounts = $get('discounts') !== null ? (int) $get('discounts') : 0;

        foreach ($repeaterItems as $item) {
            if (isset($item['subtotal_invoice'])) {
                $subtotalPrice += (int) $item['subtotal_invoice'];
            }
        }

        $totalPrice = $subtotalPrice + $taxes - $discounts;

        $set('subtotal_price', $subtotalPrice);
        $set('total_price', $totalPrice);
    }
}
