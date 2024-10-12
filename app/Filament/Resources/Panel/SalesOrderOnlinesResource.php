<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Sales;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\DeliveryAddressColumn;
use App\Filament\Columns\DeliveryStatusColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Filters\SelectStoreFilter;
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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Columns\ImageColumn;

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

                TextColumn::make('store.nickname')
                    ->disabled(fn() => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff')),

                TextColumn::make('delivery_date')
                    ->label('Date')
                    ->sortable()
                    ->disabled(fn() => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff')),

                TextColumn::make('onlineShopProvider.name')
                    ->visible(fn() => Auth::user()->hasRole('admin'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deliveryService.name')
                    ->label('Service'),

                DeliveryAddressColumn::make('deliveryAddress'),

                TextColumn::make('receipt_no')
                    ->searchable()
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

                TextColumn::make('received_by'),

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
                SelectStoreFilter::make('store_id'),
                // DateFilter::make('delivery_date'),

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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('delivery_date', 'desc');
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

                ->directory('images/Online/Payment')
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            Select::make('delivery_address_id')
                ->label('Delivery Address')
                ->hidden(fn($operation) => $operation === 'create')
                ->required(fn() => Auth::user()->hasRole('storage-staff'))
                ->nullable(fn() => Auth::user()->hasRole('admin'))
                ->relationship(
                    name: 'deliveryAddress',
                    modifyQueryUsing: fn(Builder $query) => $query->where('for', 3)
                    // $query->whereRaw('delivery_addresses.for = ?', [3])
                    // ->whereRaw('delivery_addresses.user_id = ?', [auth()->id()])
                )
                ->getOptionLabelFromRecordUsing(fn(DeliveryAddress $record) => "{$record->delivery_address_name}")

                ->searchable()
                ->preload()
                ->editOptionForm(
                    DeliveryAddressForm::schema()
                )
                ->createOptionForm(
                    DeliveryAddressForm::schema()
                ),

            StoreSelect::make('store_id')
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            DateInput::make('delivery_date')
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            Select::make('online_shop_provider_id')
                ->required()
                ->inlineLabel()
                ->relationship('onlineShopProvider', 'name')
                ->searchable()
                ->preload()
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            Select::make('delivery_service_id')
                ->required()
                ->inlineLabel()
                ->relationship('deliveryService', 'name')
                ->searchable()
                ->preload()
                ->disabled(fn() => auth()->user()->hasRole('storage-staff')),

            TextInput::make('receipt_no')
                ->inlineLabel()
                ->nullable()
                ->label('Receipt No'),
            // ->hidden(fn ($operation) => $operation === 'create')
            // ->required(fn (SalesOrderOnline $record) => $record->delivery_status == 3),

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
                ->hidden(fn($operation) => $operation === 'create')
                ->disabled(fn() => Auth::user()->hasRole('admin')),

            ImageInput::make('image_delivery')
                ->label('Delivered')
                ->directory('images/Online/Delivery'),
        ];
    }
}
