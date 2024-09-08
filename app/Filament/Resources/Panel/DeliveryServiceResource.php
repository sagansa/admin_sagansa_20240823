<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Sales;
use App\Filament\Clusters\Transaction\Settings;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DeliveryService;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\DeliveryServiceResource\Pages;
use App\Filament\Resources\Panel\DeliveryServiceResource\RelationManagers;

class DeliveryServiceResource extends Resource
{
    protected static ?string $model = DeliveryService::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Setting';

    protected static ?string $pluralLabel = 'Delivery Services';

    protected static ?string $cluster = Settings::class;

    public static function getModelLabel(): string
    {
        return __('crud.deliveryServices.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.deliveryServices.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.deliveryServices.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([TextColumn::make('name'), TextColumn::make('status')])
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
            'index' => Pages\ListDeliveryServices::route('/'),
            'create' => Pages\CreateDeliveryService::route('/create'),
            'view' => Pages\ViewDeliveryService::route('/{record}'),
            'edit' => Pages\EditDeliveryService::route('/{record}/edit'),
        ];
    }
}
