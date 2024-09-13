<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusSupplierColumn;
use App\Filament\Forms\AddressForm;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\StatusSelectInput;
use App\Filament\Forms\SupplierStatusSelectInput;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Supplier;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\SupplierResource\Pages;
use App\Filament\Resources\Panel\SupplierResource\RelationManagers;
use App\Models\DeliveryAddress;
use Filament\Forms\Components\Group;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Purchases::class;

    // protected static ?string $navigationGroup = 'Purchase';

    public static function getModelLabel(): string
    {
        return __('crud.suppliers.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.suppliers.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.suppliers.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Group::make()->schema([
                Section::make()->schema([
                    Grid::make(['default' => 1])->schema([

                        ImageInput::make('image'),

                        TextInput::make('name')
                            ->required()
                            ->hiddenLabel()
                            ->placeholder('Name')
                            ->string()
                            ->autofocus()
                            ->afterStateUpdated(fn (callable $set, $state) => $set('name', ucwords(strtolower($state)))), // Mengubah teks menjadi capital case saat diinput

                        Select::make('bank_id')
                            ->nullable()
                            ->hiddenLabel()
                            ->placeholder('Bank')
                            ->relationship('bank', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),

                        TextInput::make('bank_account_name')
                            ->nullable()
                            ->hiddenLabel()
                            ->placeholder('Bank Account Name')
                            ->string(),

                        TextInput::make('bank_account_no')
                            ->nullable()
                            ->hiddenLabel()
                            ->placeholder('Bank Accunt No')
                            ->string(),

                        SupplierStatusSelectInput::make('status'),


                    ]),
                ]) ->columnSpan(['lg' => 1]),


                ]),
            Section::make()->schema([
                Grid::make(['default' => 1])->schema(AddressForm::schema())

                ])->columnSpan(['lg' => 1])

        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageOpenUrlColumn::make('image')
                    ->url(fn($record) => asset('storage/' . $record->image)),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city.name'),

                TextColumn::make('bank.name'),

                TextColumn::make('bank_account_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bank_account_no')
                    ->searchable(),

                StatusSupplierColumn::make('status'),

                TextColumn::make('user.name'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'view' => Pages\ViewSupplier::route('/{record}'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
