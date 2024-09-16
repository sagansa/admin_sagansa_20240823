<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Sales;
use App\Filament\Columns\DeliveryAddressColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\BottomTotalPriceForm;
use App\Filament\Forms\DeliveryAddressForm;
use App\Filament\Forms\SalesProductForm;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\StoreSelect;
use App\Filament\Resources\Panel\SalesOrderEmployeesResource\Pages;
use App\Models\DeliveryAddress;
use App\Models\Store;
use App\Models\TransferToAccount;
use App\Models\SalesOrderEmployee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;

class SalesOrderEmployeesResource extends Resource
{
    protected static ?string $model = SalesOrderEmployee::class;

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    protected static ?string $pluralLabel = 'Employee';

    protected static ?string $cluster = Sales::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema(static::getDetailsFormHeadSchema())
                ->columns(2),

            Section::make('Detail Order')->schema([
                SalesProductForm::getItemsRepeater()
            ]),

            Section::make()
                ->schema(BottomTotalPriceForm::schema())
        ])
        ->disabled(fn (?SalesOrderEmployee $record) => $record !== null && $record->payment_status == 2);
    }

    public static function table(Table $table): Table
    {
        $query = SalesOrderEmployee::query();

        if (Auth::user()->hasRole('sales')) {
            $query->where('ordered_by_id', Auth::id());
        }

        $query->where('for', 2);

        return $table
            ->query($query)
            ->columns([
                ImageOpenUrlColumn::make('image_payment')
                    ->label('Transfer')
                    ->url(fn($record) => asset('storage/' . $record->image_payment)),

                TextColumn::make('delivery_date')
                    ->label('Date'),

                DeliveryAddressColumn::make('deliveryAddress')
                    ->label('Customer'),

                TextColumn::make('transferToAccount.name')
                    ->label('Transfer to Account'),

                TextColumn::make('detailSalesOrders')
                    ->label('Orders')
                    ->html()
                    ->formatStateUsing(function (SalesOrderEmployee $record) {
                        return implode('<br>', $record->detailSalesOrders->map(function ($item) {
                            return "{$item->product->name} ({$item->quantity} {$item->product->unit->unit})";
                        })->toArray());
                    })
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->formatStateUsing(fn (SalesOrderEmployee $record) => 'Rp ' . number_format($record->total_price, 0, ',', '.'))
                    ->summarize(Sum::make()
                        ->numeric(
                            thousandsSeparator: '.'
                        )
                        ->label('')
                        ->prefix('Rp ')),

                StatusColumn::make('payment_status')
                    ->label('Payment Status'),

                TextColumn::make('orderedBy.name')
                    ->label('Sales')
                    ->visible(fn ($record) => auth()->user()->hasRole('admin')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListSalesOrderEmployees::route('/'),
            'create' => Pages\CreateSalesOrderEmployees::route('/create'),
            'edit' => Pages\EditSalesOrderEmployees::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {
        return [
            ImageInput::make('image_payment')
                ->label('Transfer')
                ->disk('public')
                ->directory('images/Employee'),

            StoreSelect::make('store_id'),

            DatePicker::make('delivery_date')
                ->required()
                ->default('today')
                ->rules(['date'])
                ->required()
                ->native(false),

            Select::make('delivery_address_id')
                ->label('Delivery Address')
                ->required()
                ->relationship(
                    name: 'deliveryAddress',
                    modifyQueryUsing: fn (Builder $query) =>
                        $query->where('user_id', Auth::id())
                )
                ->getOptionLabelFromRecordUsing(fn (DeliveryAddress $record) => "{$record->delivery_address_name}")
                ->searchable()
                ->preload()
                ->native(false)
                ->createOptionForm(
                    DeliveryAddressForm::schema()
                ),

            Select::make('transfer_to_account_id')
                ->label('Transfer To Account')
                ->required()
                ->relationship('transferToAccount', 'name')
                ->options(TransferToAccount::where('status', 1)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->id => $item->transfer_name];
                    }))
                ->native(false),

            Select::make('payment_status')
                ->required()
                ->default(1)
                ->options([
                    '1' => 'belum diperiksa',
                    '2' => 'valid',
                    '3' => 'perbaiki',
                    '4' => 'periksa ulang',
                ])
                ->visible(fn ($record) => auth()->user()->hasRole('admin')),
        ];
    }
}
