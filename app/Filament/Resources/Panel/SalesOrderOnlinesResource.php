<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Sales;
use App\Filament\Columns\DeliveryStatusColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Resources\Panel\SalesOrderOnlinesResource\Pages;
use App\Models\DeliveryAddress;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Forms\BottomTotalPriceForm;
use App\Filament\Forms\DeliveryAddressForm;
use App\Filament\Forms\SalesProductForm;
use App\Models\SalesOrderOnline;
use App\Models\Store;
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

    public static function form(Form $form): Form
    {
        return $form->schema([
                Section::make('Order')
                    ->schema(static::getDetailsFormHeadSchema())
                    ->columns(2),

                Section::make('Detail Order')->schema([
                    SalesProductForm::getItemsRepeater()
                    ])
                    ->disabled(fn () => Auth::user()->hasRole('storage-staff')),

                Section::make('Total Price')
                    ->schema(BottomTotalPriceForm::schema()),
            ])
            ->disabled(fn (?SalesOrderOnline $record) => $record !== null && $record->delivery_status == 2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(SalesOrderOnline::query()->where('for', 3))
            ->columns([
                ImageOpenUrlColumn::make('image_payment')
                    ->disabled(fn () => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff'))
                    ->label('Payment')
                    ->url(fn($record) => asset('storage/' . $record->image_payment)),

                ImageOpenUrlColumn::make('image_delivery')
                    ->label('Delivery')
                    ->url(fn($record) => asset('storage/' . $record->image_delivery)),

                TextColumn::make('store.nickname')
                    ->disabled(fn () => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff')),

                TextColumn::make('delivery_date')
                    ->label('Date')
                    ->sortable()
                    ->disabled(fn () => Auth::user()->hasRole('staff') || Auth::user()->hasRole('storage-staff')),

                TextColumn::make('onlineShopProvider.name')
                    ->visible(fn () => Auth::user()->hasRole('admin'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deliveryService.name')
                    ->label('Service'),

                TextColumn::make('deliveryAddress.name')
                    ->searchable()
                    ->label('Address'),

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

                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->visible(fn () => Auth::user()->hasRole('admin'))
                    ->formatStateUsing(fn (SalesOrderOnline $record) => 'Rp ' . number_format($record->total_price, 0, ',', '.'))
                    ->summarize(Sum::make()
                        ->numeric(
                            thousandsSeparator: '.'
                        )
                        ->label('')
                        ->prefix('Rp ')),
            ])
            ->filters([
                SelectFilter::make('store_id')
                        ->searchable()
                        ->label('Store')
                        ->relationship('store', 'nickName'),
                Filter::make('delivery_date')
                        ->form([
                            DatePicker::make('date_from'),
                            DatePicker::make('date_until'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['date_from'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('delivery_date', '>=', $date),
                                )
                                ->when(
                                    $data['date_until'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('delivery_date', '<=', $date),
                                );
                        })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (SalesOrderOnline $record) => !in_array($record->delivery_status, [2])),
                Tables\Actions\ViewAction::make()
                    ->visible(fn (SalesOrderOnline $record) => in_array($record->delivery_status, [2])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

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
                            $records->each(function($record) use ($data) {
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
            FileUpload::make('image_payment')
                ->label('From Online Shop')
                ->rules(['image'])
                ->nullable()
                ->maxSize(1024)
                ->image()
                ->imageEditor()
                ->columnSpan([
                    'full'
                ])
                ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                ->disk('public')
                ->directory('images/Online/Payment')
                ->disabled(fn () => auth()->user()->hasRole('storage-staff')),

            Select::make('store_id')
                ->required()
                ->relationship('store', 'nickname')
                ->options(function () {
                    return Store::where('status', 1)->pluck('nickname', 'id');
                })
                ->preload()
                ->native(false)
                ->disabled(fn () => auth()->user()->hasRole('storage-staff')),

            DatePicker::make('delivery_date')
                ->required()
                ->default('today')
                ->rules(['date'])
                ->required()
                ->native(false)
                ->disabled(fn () => auth()->user()->hasRole('storage-staff')),

            Select::make('online_shop_provider_id')
                ->required()
                ->relationship('onlineShopProvider', 'name')
                ->searchable()
                ->preload()
                ->native(false)
                ->disabled(fn () => auth()->user()->hasRole('storage-staff')),

            Select::make('delivery_service_id')
                ->required()
                ->relationship('deliveryService', 'name')
                ->searchable()
                ->preload()
                ->native(false)
                ->disabled(fn () => auth()->user()->hasRole('storage-staff')),

            Select::make('delivery_address_id')
                ->label('Delivery Address')
                ->hidden(fn ($operation) => $operation === 'create')
                ->required(fn () => Auth::user()->hasRole('storage-staff'))
                ->nullable(fn () => Auth::user()->hasRole('admin'))
                ->relationship(
                    name: 'deliveryAddress',
                    modifyQueryUsing: fn (Builder $query) => $query->where('for', 3)
                        // $query->whereRaw('delivery_addresses.for = ?', [3])
                            // ->whereRaw('delivery_addresses.user_id = ?', [auth()->id()])
                )
                ->getOptionLabelFromRecordUsing(fn (DeliveryAddress $record) => "{$record->delivery_address_name}")

                ->searchable()
                ->preload()
                ->native(false)
                ->editOptionForm(
                    DeliveryAddressForm::schema()
                )
                ->createOptionForm(
                    DeliveryAddressForm::schema()
                ),


            TextInput::make('receipt_no')
                ->label('Receipt No'),
                // ->hidden(fn ($operation) => $operation === 'create')
                // ->required(fn (SalesOrderOnline $record) => $record->delivery_status == 3),

            Select::make('delivery_status')
                ->required()
                ->hidden(fn ($operation) => $operation === 'create')
                ->native(false)
                ->options([
                    '1' => 'belum dikirim',
                    '2' => 'valid',
                    '3' => 'sudah dikirim',
                    '4' => 'siap dikirim',
                    '5' => 'perbaiki',
                    '6' => 'dikembalikan'
                ]),

            FileUpload::make('image_delivery')
                ->label('Delivered')
                ->rules(['image'])
                ->nullable()
                ->hidden(fn ($operation) => $operation === 'create')
                ->maxSize(1024)
                ->image()
                ->imageEditor()
                ->disk('public')
                ->directory('images/Online/Delivery')
                ->columnSpan([
                    'full'
                ])
                ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),
        ];
    }
}
