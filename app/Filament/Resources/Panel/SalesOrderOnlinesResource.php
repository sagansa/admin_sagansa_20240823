<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Sales;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\DeliveryAddressColumn;
use App\Filament\Columns\DeliveryStatusColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Filters\DateFilter;
use App\Filament\Resources\Panel\SalesOrderOnlinesResource\Pages;
use App\Models\DeliveryAddress;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Forms\BottomTotalPriceForm;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\DeliveryAddressForm;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\SalesProductForm;
use App\Filament\Forms\StoreSelect;
use App\Filament\Resources\Panel\SalesOrderOnlinesResource\Widgets\SalesOrderOnlinesStat;
use App\Models\SalesOrderOnline;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Collection;
use JeffersonGoncalves\Filament\QrCodeField\Forms\Components\QrCodeInput;

class SalesOrderOnlinesResource extends Resource
{
    protected static ?string $model = SalesOrderOnline::class;

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 3;

    protected static ?string $pluralLabel = 'Online';

    protected static ?string $cluster = Sales::class;

    // public static function getModelLabel(): string
    // {
    //     return __('crud.salesOrderOnlines.itemTitle');
    // }

    // public static function getPluralModelLabel(): string
    // {
    //     return __('crud.salesOrderOnlines.collectionTitle');
    // }

    // public static function getNavigationLabel(): string
    // {
    //     return __('crud.salesOrderOnlines.collectionTitle');
    // }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Group::make()
                ->schema([
                    Section::make('Order')
                        ->schema(static::getDetailsFormHeadSchema())
                        ->columns(2),

                    Section::make('Detail Order')->schema([
                        SalesProductForm::getItemsRepeater()
                    ])
                        ->disabled(fn() => Auth::user()->hasRole('storage-staff')),
                ])
                ->columnSpan(['lg' => 2]),

            Section::make('Total Price')
                ->schema(BottomTotalPriceForm::schema())
                ->columnSpan(['lg' => 1]),
        ])
            ->columns(3)
            ->disabled(fn(?SalesOrderOnline $record) => $record !== null && $record->delivery_status == 2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->query(SalesOrderOnline::query()->where('for', 3))
            ->columns([
                ImageOpenUrlColumn::make('image_payment')
                    ->disabled(fn() => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff'))
                    ->label('Payment')
                    ->url(fn($record) => asset('storage/' . $record->image_payment)),

                ImageOpenUrlColumn::make('image_delivery')
                    ->label('Delivery')
                    ->url(fn($record) => asset('storage/' . $record->image_delivery)),

                TextColumn::make('receipt_no')
                    ->label('Receipt No')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Receipt number copied')
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('store.nickname')
                    ->disabled(fn() => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('delivery_date')
                    ->label('Date')
                    ->sortable()
                    ->searchable()
                    ->disabled(fn() => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff')),

                TextColumn::make('onlineShopProvider.name')
                    ->visible(fn() => Auth::user()->hasRole('admin'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deliveryService.name')
                    ->label('Service')
                    ->searchable()
                    ->toggleable(),

                DeliveryAddressColumn::make('deliveryAddress'),

                TextColumn::make('receipt_no')
                    ->label('Receipt No')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Receipt number copied')
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('detailSalesOrders')
                    ->label('Orders')
                    ->html()
                    ->formatStateUsing(function (SalesOrderOnline $record) {
                        return implode('<br>', $record->detailSalesOrders->map(function ($item) {
                            return "{$item->product->name} ({$item->quantity} {$item->product->unit->unit})";
                        })->toArray());
                    })
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

                DeliveryStatusColumn::make('delivery_status')
                    ->label('Status'),

                TextColumn::make('orderedBy.name')
                    ->label('Input By')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('assignedBy.name')
                    ->label('Processed By')
                    ->toggleable(isToggledHiddenByDefault: true),

                // TextColumn::make('received_by')
                //     ->label('Received By')
                //     ->copyable()
                //     ->copyMessage('Receiver name copied')
                //     ->copyMessageDuration(1500),

                CurrencyColumn::make('total_price')
                    ->label('Total Price')
                    ->visible(fn() => Auth::user()->hasRole('admin'))
                    ->summarize(Sum::make()
                        ->numeric(
                            thousandsSeparator: '.'
                        )
                        ->label('')
                        ->prefix('Rp ')),
            ])
            ->filters([
                Filter::make('receipt')
                    ->label('Scan QR Resi')
                    ->form([
                        QrCodeInput::make('receipt_no')
                            ->label('Scan QR untuk Resi')
                            ->inlineLabel()
                            ->placeholder('Scan atau tempel nomor resi untuk memfilter data')
                            ->columnSpanFull(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['receipt_no'] ?? null),
                            fn (Builder $query, string $receiptNo): Builder => $query->where('receipt_no', $receiptNo),
                        );
                    })
                    ->indicateUsing(function (array $state): ?string {
                        $value = $state['receipt_no'] ?? null;

                        if (blank($value)) {
                            return null;
                        }

                        return "Resi: {$value}";
                    })
                    ->columnSpanFull(),
                SelectStoreFilter::make('store_id'),
                DateFilter::make('delivery_date'),
                SelectFilter::make('online_shop_provider_id')
                    ->label('Online Shop Provider')
                    ->relationship('onlineShopProvider', 'name'),
                SelectFilter::make('delivery_service_id')
                    ->label('Delivery Service')
                    ->relationship('deliveryService', 'name'),
                SelectFilter::make('delivery_status')
                    ->label('Delivery Status')
                    ->options([
                        '1' => 'belum dikirim',
                        '2' => 'valid',
                        '3' => 'sudah dikirim',
                        '4' => 'siap dikirim',
                        '5' => 'perbaiki',
                        '6' => 'dikembalikan',
                    ]),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn (array $filters): array => [
                $filters['receipt']->columnSpanFull(),
                Section::make('Filter Lainnya')
                    ->schema([
                        $filters['store_id'],
                        $filters['delivery_date'],
                        $filters['online_shop_provider_id'],
                        $filters['delivery_service_id'],
                        $filters['delivery_status'],
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->visible(fn(SalesOrderOnline $record) => !in_array($record->delivery_status, [2])),
                    Tables\Actions\ViewAction::make()
                        ->visible(fn(SalesOrderOnline $record) => in_array($record->delivery_status, [2])),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Change Delivery Status')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('delivery_status')
                                ->label('Delivery Status')
                                ->options([
                                    '1' => 'belum dikirim',
                                    '2' => 'valid',
                                    '3' => 'sudah dikirim',
                                    '4' => 'siap dikirim',
                                    '5' => 'perbaiki',
                                    '6' => 'dikembalikan',
                                ])
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                SalesOrderOnline::where('id', $record->id)->update(['delivery_status' => $data['delivery_status']]);
                            });
                        }),
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No online sales orders')
            ->emptyStateDescription('Try adjusting filters or create a new order if you have permissions.');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // public static function getWidgets(): array
    // {
    //     return [
    //         SalesOrderOnlinesStat::class,
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesOrderOnlines::route('/'),
            'create' => Pages\CreateSalesOrderOnlines::route('/create'),
            'view' => Pages\ViewSalesOrderOnlines::route('/{record}'),
            'edit' => Pages\EditSalesOrderOnlines::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {

        return [
            ImageInput::make('image_payment')
                ->label('From Online Shop')
                ->helperText('Upload a clear screenshot or invoice from the online shop.')
                ->directory('images/Online/Payment')
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            // Select::make('delivery_address_id')
            //     ->label('Delivery Address')
            //     ->hidden(fn($operation) => $operation === 'create')
            //     ->required(fn() => Auth::user()->hasRole('storage-staff'))
            //     ->nullable(fn() => Auth::user()->hasRole('admin'))
            //     ->relationship(
            //         name: 'deliveryAddress',
            //         modifyQueryUsing: fn(Builder $query) => $query->where('for', 3)
            //         // $query->whereRaw('delivery_addresses.for = ?', [3])
            //         // ->whereRaw('delivery_addresses.user_id = ?', [auth()->id()])
            //     )
            //     ->getOptionLabelFromRecordUsing(fn(DeliveryAddress $record) => "{$record->delivery_address_name}")
            //     ->placeholder('Search or create delivery address')
            //     ->searchable()
            //     ->preload()
            //     ->editOptionForm(
            //         DeliveryAddressForm::schema()
            //     )
            //     ->createOptionForm(
            //         DeliveryAddressForm::schema()
            //     ),

            StoreSelect::make('store_id')
                ->placeholder('Select store')
                ->default('150')
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            DateInput::make('delivery_date')
                // ->helperText('Use the planned delivery date.')
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            Select::make('online_shop_provider_id')
                ->required()
                ->inlineLabel()
                ->relationship('onlineShopProvider', 'name')
                ->placeholder('Select provider')
                ->searchable()
                ->preload()
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            Select::make('delivery_service_id')
                ->required()
                ->inlineLabel()
                ->relationship('deliveryService', 'name')
                ->placeholder('Select delivery service (e.g., JNE, SiCepat)')
                ->searchable()
                ->preload(),

            Radio::make('qr_code_type')
                ->label('Receipt Input Type')
                ->inline()
                ->hidden(fn (Get $get) => filled($get('receipt_no')) && fn ($operation) => $operation !== 'create')
                ->reactive()
                ->dehydrated(false) // tidak disimpan ke DB
                ->options([
                    'qr_code' => 'QR Code',
                    'manual'  => 'Manual',
                ])
                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                    // kosongkan receipt_no saat ganti mode agar tidak bawa nilai lama
                    $set('receipt_no', null);
                }),

            // Scan QR menulis langsung ke kolom 'receipt_no'
            QrCodeInput::make('receipt_qr_code')
                ->label('Receipt No (QR)')
                ->hidden(fn (Get $get) => $get('qr_code_type') !== 'qr_code')
                ->required(fn (Get $get) => $get('qr_code_type') === 'qr_code')
                ->statePath('receipt_no') // <— kunci: tulis ke state 'receipt_no' (kolom model)
                ->dehydrated(fn (Get $get) => $get('qr_code_type') === 'qr_code')
                ->unique(
                    table: SalesOrderOnline::class,
                    column: 'receipt_no',
                    ignoreRecord: true,
                )
                ->inlineLabel(),

            // Input manual juga menulis ke state 'receipt_no'
            TextInput::make('receipt_manual')
                ->label('Receipt No (Manual)')
                ->hidden(fn (Get $get) => $get('qr_code_type') !== 'manual' || blank($get('qr_code_type')))
                // ->visible(fn (Get $get) => filled($get('receipt_no')) || fn ($operation) => $operation === 'create')
                ->required(fn (Get $get) => $get('qr_code_type') === 'manual')
                ->statePath('receipt_no') // <— sama
                ->dehydrated(fn (Get $get) => $get('qr_code_type') === 'manual')
                ->unique(
                    table: SalesOrderOnline::class,
                    column: 'receipt_no',
                    ignoreRecord: true,
                )
                ->inlineLabel(),

            TextInput::make('receipt_no')
                ->hidden(fn ($operation) => $operation === 'create')
                ->visible(fn (Get $get, $operation) => filled($get('receipt_no')))
                ->dehydrated(fn (Get $get) => filled($get('receipt_no'))) // hanya simpan jika ada isinya
                ->inlineLabel()
                ->disabled(fn() => auth()->user()->hasRole('storage-staff'))
                ->unique(
                    table: SalesOrderOnline::class,
                    column: 'receipt_no',
                    ignoreRecord: true,
                )
                ->required(),

            // Tidak perlu field 'receipt_no' yang hidden lagi.

            Select::make('delivery_status')
                ->required()
                ->inlineLabel()
                ->hidden(fn($operation) => $operation === 'create')
                ->options([
                    '1' => 'belum dikirim',
                    '2' => 'valid',
                    '3' => 'sudah dikirim',
                    '4' => 'siap dikirim',
                    '5' => 'perbaiki',
                    '6' => 'dikembalikan'
                ]),

            TextInput::make('received_by')
                ->inlineLabel()
                ->placeholder('Receiver name')
                ->hidden(fn($operation) => $operation === 'create')
                ->disabled(fn() => Auth::user()->hasRole('admin')),

            ImageInput::make('image_delivery')
                ->label('Delivered')
                ->hidden(fn($operation) => $operation === 'create')
                // ->helperText('Upload delivery proof (resi/parcel photo).')
                ->directory('images/Online/Delivery'),
        ];
    }
}
