<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\PaymentStatusColumn;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\PaymentStatusSelectInput;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FuelService;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\FuelServiceResource\Pages;
use App\Filament\Resources\Panel\FuelServiceResource\RelationManagers;
use App\Filament\Tables\FuelServiceTable;
use App\Models\PaymentType;
use App\Models\Supplier;
use App\Models\Vehicle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;

class FuelServiceResource extends Resource
{
    protected static ?string $model = FuelService::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Purchase';

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
                Grid::make(['default' => 1])->schema([

                    ImageInput::make('image'),

                    DateInput::make('date')
                        ->required(),

                    Radio::make('fuel_service')
                        ->inline()
                        ->hiddenLabel()
                        ->required()
                        ->options([
                            '1' => 'fuel',
                            '2' => 'service',
                        ]),

                    Select::make('vehicle_id')
                        ->required()
                        ->placeholder('Vehicle')
                        ->hiddenLabel()
                        ->relationship(
                            name: 'vehicle',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (Vehicle $record) => "{$record->no_register}")
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('supplier_id')
                        ->required()
                        ->placeholder('Supplier')
                        ->hiddenLabel()
                        ->relationship(
                            name: 'supplier',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status','<>', '3'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (Supplier $record) => "{$record->supplier_name}")
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('payment_type_id')
                        ->required()
                        ->placeholder('Payment Type')
                        ->hiddenLabel()
                        ->relationship(
                            name: 'paymentType',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (PaymentType $record) => "{$record->name}")
                        // ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('km')
                        ->placeholder('km')
                        ->hiddenLabel()
                        ->suffix('km')
                        ->required()
                        ->numeric(),

                    TextInput::make('liter')
                        ->placeholder('liter')
                        ->hiddenLabel()
                        ->suffix('liter')
                        ->nullable()
                        ->numeric(),

                    TextInput::make('amount')
                        ->required()
                        ->placeholder('Amount')
                        ->hiddenLabel()
                        ->numeric()
                        ->prefix('Rp'),

                    PaymentStatusSelectInput::make('status'),

                    Notes::make('notes'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $query = FuelService::query();

        if(Auth::user()->hasRole('staff')) {
            $query->where('created_by_id', Auth::id());
        }

        return $table
            ->query($query)
            ->poll('60s')
            ->columns(
                FuelServiceTable::schema()
            )
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
            'index' => Pages\ListFuelServices::route('/'),
            'create' => Pages\CreateFuelService::route('/create'),
            'view' => Pages\ViewFuelService::route('/{record}'),
            'edit' => Pages\EditFuelService::route('/{record}/edit'),
        ];
    }
}
