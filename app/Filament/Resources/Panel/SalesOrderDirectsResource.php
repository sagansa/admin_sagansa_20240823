<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Sales;
use App\Filament\Columns\DeliveryAddressColumn;
use App\Filament\Columns\DeliveryStatusColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Filters\DateFilter;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Forms\BottomTotalPriceForm;
use App\Filament\Forms\DeliveryAddressForm;
use App\Filament\Forms\ImageInput;
use App\Filament\Resources\Panel\SalesOrderDirectsResource\Pages;
use App\Models\SalesOrderDirect;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Filament\Forms\SalesProductForm;
use App\Filament\Forms\StoreSelect;
use App\Models\DeliveryAddress;
use App\Models\TransferToAccount;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\HtmlString;

class SalesOrderDirectsResource extends Resource
{
    protected static ?string $model = SalesOrderDirect::class;

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $pluralLabel = 'Order';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Sales::class;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make()
                ->schema(static::getDetailsFormHeadSchema()),

            Section::make('Detail Order')->schema([
                SalesProductForm::getItemsRepeater()
            ]),
            // ->disabled(fn (SalesOrderDirect $record) => $record->payment_status === 2),

            Section::make()
                 ->schema(BottomTotalPriceForm::schema())
        ]);
        // ->disabled(fn (?SalesOrderDirect $record) => $record !== null && $record->payment_status == 2 && $record->delivery_status == 2);
    }

    public static function table(Table $table): Table
    {
        $query = SalesOrderDirect::query();

        if (Auth::user()->hasRole('customer')) {
            $query->where('ordered_by_id', Auth::id());
        } elseif (Auth::user()->hasRole('storage-staff')) {
            $query->where('payment_status', 2);
        }

        $query->where('for', 1);

        return $table
            ->query($query)
            ->columns([

                ImageOpenUrlColumn::make('image_payment')
                    ->label('Payment')
                    ->url(fn($record) => asset('storage/' . $record->image_payment)),

                ImageOpenUrlColumn::make('image_delivery')
                    ->label('delivery')
                    ->url(fn($record) => asset('storage/' . $record->image_delivery)),

                TextColumn::make('orderedBy.name')
                    ->searchable()
                    ->visible(fn () => Auth::user()->hasRole('admin') || Auth::user()->hasRole('storage-staff')),

                TextColumn::make('store.nickname')
                    ->hidden(fn () => Auth::user()->hasRole('customer')),

                TextColumn::make('delivery_date')
                    ->label('Date'),

                TextColumn::make('deliveryService.name'),

                DeliveryAddressColumn::make('deliveryAddress'),

                TextColumn::make('transferToAccount.transfer_account_name')
                    ->hidden(fn () => Auth::user()->hasRole('storage-staff')),

                StatusColumn::make('payment_status')
                    ->hidden(fn () => Auth::user()->hasRole('storage-staff')),

                DeliveryStatusColumn::make('delivery_status'),

                TextColumn::make('shipping_cost')
                    ->label('Shipping Cost')
                    ->formatStateUsing(fn (SalesOrderDirect $record) => 'Rp ' . number_format($record->shipping_cost, 0, ',', '.'))
                    ->summarize(Sum::make()
                        ->numeric(
                            thousandsSeparator: '.'
                        )
                        ->label('')
                        ->prefix('Rp '))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total_price')
                    ->visible(fn ($record) => auth()->user()->hasRole('admin') || auth()->user()->hasRole('customer'))
                    ->label('Total Price')
                    ->formatStateUsing(fn (SalesOrderDirect $record) => 'Rp ' . number_format($record->total_price, 0, ',', '.'))
                    ->summarize(Sum::make()
                        ->numeric(
                            thousandsSeparator: '.'
                        )
                        ->label('')
                        ->prefix('Rp ')),

                TextColumn::make('receipt_no')->searchable(),

                TextColumn::make('received_by')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('assignedBy.name')
                    ->visible(fn () => Auth::user()->hasRole('admin'))
                    ->toggleable(isToggledHiddenByDefault: false),

            ])
            ->filters([
                SelectStoreFilter::make('store_id'),
                DateFilter::make('delivery_date'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Action::make('Update Payment Status To Valid')
                        ->icon('heroicon-o-pencil-square')
                        ->action(function ($record) {
                            $record->update(['payment_status' => 2]);
                        })
                        ->requiresConfirmation(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('delivery_date', 'desc');;
    }

    public static function getRelations(): array
    {
        return [
            // ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesOrderDirects::route('/'),
            'create' => Pages\CreateSalesOrderDirects::route('/create'),
            'view' => Pages\ViewSalesOrderDirects::route('/{record}'),
            'edit' => Pages\EditSalesOrderDirects::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {
        $options = [
            '1' => 'belum dikirim',
            '3' => 'sudah dikirim',
            '4' => 'siap dikirim',
            '5' => 'perbaiki',
            '6' => 'dikembalikan'
        ];

        if (Auth::user()->hasRole('admin')) {
            $options['2'] = 'valid';
        }

        return [
            ImageInput::make('image_payment')
                ->label('Payment')
                ->disabled(fn (SalesOrderDirect $salesOrderDirect) =>
                    Auth::user()->hasRole('customer') && $salesOrderDirect->payment_status == 2
                    || Auth::user()->hasRole('admin')
                    || Auth::user()->hasRole('storage-staff')
                )
                ->required()
                ->disk('public')
                ->directory('images/Direct/Payment'),

            StoreSelect::make('store_id')
                ->required(fn () => Auth::user()->hasRole('admin'))
                ->hidden(fn () => Auth::user()->hasRole('customer'))
                ->disabled(fn () => Auth::user()->hasRole('storage-staff')),

            DatePicker::make('delivery_date')
                ->required()
                ->label('Delivery Date')
                ->disabled(fn (SalesOrderDirect $salesOrderDirect) => Auth::user()->hasRole('customer') && $salesOrderDirect->payment_status == 2 || Auth::user()->hasRole('storage-staff'))
                ->default('today')
                ->rules(['date'])
                ->required()
                ->native(false),

            Select::make('delivery_service_id')
                ->required()
                ->label('Delivery Service')
                ->disabled(fn (SalesOrderDirect $salesOrderDirect) => Auth::user()->hasRole('customer') && $salesOrderDirect->payment_status == 2 || Auth::user()->hasRole('storage-staff'))
                ->relationship('deliveryService', 'name')
                ->searchable()
                ->preload()
                ->native(false),

            Placeholder::make('delivery_address')
                ->hidden(fn ($operation) => $operation === 'create' || Auth::user()->hasRole('customer'))
                ->content(fn (SalesOrderDirect $record): string => $record->deliveryAddress->delivery_address_name),

            Select::make('delivery_address_id')
                ->label('Delivery Address')
                ->required(fn (SalesOrderDirect $salesOrderDirect) => $salesOrderDirect->delivery_service_id != 33)
                ->relationship(
                    name: 'deliveryAddress',
                    modifyQueryUsing: function (Builder $query) {
                        if (Auth::user()->hasRole('admin')) {
                            $query->get();
                        } elseif (Auth::user()->hasRole('storage-staff')) {
                            $query->get();
                        } elseif (Auth::user()->hasRole('customer')) {
                            $query->where('user_id', Auth::id());
                        }
                    }
                )
                ->getOptionLabelFromRecordUsing(fn (DeliveryAddress $record) => "{$record->delivery_address_name}")
                ->searchable()
                ->hidden(fn (SalesOrderDirect $salesOrderDirect) => !Auth::user()->hasRole('customer') || $salesOrderDirect->delivery_service_id == 33)
                ->disabled(fn (SalesOrderDirect $salesOrderDirect) =>
                    Auth::user()->hasRole('customer') && $salesOrderDirect->payment_status == 2)
                ->preload()
                ->native(false)
                ->createOptionForm(
                    DeliveryAddressForm::schema()
                ),

            Select::make('transfer_to_account_id')
                ->required()
                ->label('Transfer To Account')
                ->hidden(fn () => Auth::user()->hasRole('storage-staff'))
                ->disabled(fn (SalesOrderDirect $salesOrderDirect) => Auth::user()->hasRole('customer') && $salesOrderDirect->payment_status == 1)
                ->relationship('transferToAccount', 'name')
                ->options(TransferToAccount::where('status', 1)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->id => $item->transfer_name];
                    }))
                ->native(false),

            TextInput::make('receipt_no')
                ->disabled(fn () => Auth::user()->hasRole('customer') || Auth::user()->hasRole('admin'))
                ->hidden(fn ($operation) => $operation === 'create' && fn(SalesOrderDirect $salesOrderDirect) => $salesOrderDirect->deliveryStatus == 3)
                ->required(fn (SalesOrderDirect $salesOrderDirect) => Auth::user()->hasRole('storage-staff') && $salesOrderDirect->delivery_status == 3),

            Select::make('payment_status')
                ->required(fn () => Auth::user()->hasRole('admin'))
                ->hidden(fn ($operation) => $operation === 'create' || Auth::user()->hasRole('storage-staff'))
                ->disabled(fn () => Auth::user()->hasRole('customer'))
                ->native(false)
                ->reactive()
                ->live()
                ->options([
                    '1' => 'belum diperiksa',
                    '2' => 'valid',
                ]),

            Placeholder::make('delivery_status')
                ->hidden(fn ($operation) => $operation === 'create')
                ->label('Delivery Status')
                ->content(fn (SalesOrderDirect $record): HtmlString => new HtmlString(match ($record->delivery_status) {
                    1 => '<span class="badge badge-warning">belum dikirim</span>',
                    3 => '<span class="badge badge-success">sudah dikirim</span>',
                    4 => '<span class="badge badge-info">siap dikirim</span>',
                    5 => '<span class="badge badge-danger">perbaiki</span>',
                    6 => '<span class="badge badge-secondary">dikembalikan</span>',
                    // default => '<span class="badge badge-dark">unknown</span>',
                })),

            Select::make('delivery_status')
                ->hidden(fn ($operation) => $operation === 'create' || !Auth::user()->hasRole('storage-staff'))
                ->required()
                ->native(false)
                ->options([
                    '1' => 'belum dikirim',
                    '3' => 'sudah dikirim',
                    '4' => 'siap dikirim',
                    '5' => 'perbaiki',
                    '6' => 'dikembalikan'
                ]),

            TextInput::make('received_by')
                ->hidden(fn ($operation) => $operation === 'create')
                ->disabled(fn () => Auth::user()->hasRoler('customer')),

            ImageInput::make('image_delivery')
                ->hidden(fn () => Auth::user()->hasRole('customer'))
                ->disabled(fn () => Auth::user()->hasRole('admin'))
                ->label('Delivered')
                ->disk('public')
                ->directory('images/Direct/Delivery'),
        ];
    }
}
