<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Forms\BaseSelect;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\DecimalInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\NominalInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\PaymentStatusSelectInput;
use App\Filament\Forms\SupplierSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FuelService;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\FuelServiceResource\Pages;
use App\Filament\Tables\FuelServiceTable;
use App\Models\PaymentType;
use App\Models\Supplier;
use App\Models\Vehicle;
use Filament\Forms\Components\Radio;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class FuelServiceResource extends Resource
{
    protected static ?string $model = FuelService::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Purchases::class;

    // protected static ?string $navigationGroup = 'Purchase';

    public static function getModelLabel(): string
    {
        return __('crud.fuelServices.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.fuelServices.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.fuelServices.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([

                Grid::make(['default' => 2])->schema([

                    ImageInput::make('image')
                        ->directory('images/FuelService'),

                    SupplierSelect::make('supplier_id'),

                    DateInput::make('date'),

                    Radio::make('fuel_service')
                        ->inline()
                        ->inlineLabel()
                        ->required()
                        ->options([
                            '1' => 'fuel',
                            '2' => 'service',
                        ]),

                    BaseSelect::make('vehicle_id')
                        ->required()
                        ->relationship(
                            name: 'vehicle',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (Vehicle $record) => "{$record->no_register}")
                        ->searchable()
                        ->preload(),

                    BaseSelect::make('payment_type_id')
                        ->relationship(
                            name: 'paymentType',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (PaymentType $record) => "{$record->name}"),

                    NominalInput::make('km')
                        ->label('km')
                        ->suffix('km'),

                    DecimalInput::make('liter')
                        ->suffix('liter'),

                    CurrencyInput::make('amount'),

                    PaymentStatusSelectInput::make('status'),

                ]),

                Grid::make(['default' => 1])->schema([
                    Notes::make('notes'),
                ])
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $query = FuelService::query();

        if(!Auth::user()->hasRole('admin')) {
            $query->where('created_by_id', Auth::id());
        }

        return $table
            ->query($query)
            ->poll('60s')
            ->columns(
                FuelServiceTable::schema()
            )
            ->filters([
                SelectFilter::make('payment_type_id')
                    ->label('Payment Type')
                    ->options([
                        '1' => 'transfer',
                        '2' => 'tunai',
                    ]),

                SelectFilter::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship(
                        name: 'vehicle',
                        titleAttribute: 'no_register',
                        modifyQueryUsing: fn (Builder $query) => $query,
                    )
                    ->getOptionLabelFromRecordUsing(fn (Vehicle $record) => "{$record->vehicle_status}")
                    ->searchable()
                    ->preload()
                    ,

                SelectFilter::make('fuel_service')
                    ->label('Fuel Service')
                    ->options([
                        '1' => 'fuel',
                        '2' => 'service',
                    ]),
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
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFuelServices::route('/'),
            'create' => Pages\CreateFuelService::route('/create'),
            'view' => Pages\ViewFuelService::route('/{record}'),
            'edit' => Pages\EditFuelService::route('/{record}/edit'),
        ];
    }
}
