<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Forms\DeliveryAddressForm;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DeliveryAddress;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Panel\DeliveryAddressResource\Pages;
use Illuminate\Support\Facades\Auth;

class DeliveryAddressResource extends Resource
{
    protected static ?string $model = DeliveryAddress::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Sales Transaction';

    public static function getModelLabel(): string
    {
        return __('crud.deliveryAddresses.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.deliveryAddresses.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.deliveryAddresses.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema(DeliveryAddressForm::schema())
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        $query = DeliveryAddress::query();

        if (Auth::user()->hasRole('storage-staff')) {
            $query->where('for', 3);
        } elseif (Auth::user()->hasRole('sales')) {
            $query->where('user_id', Auth::id());
        } elseif (Auth::user()->hasRole('customer')) {
            $query->where('user_id', Auth::id());
        }

        return $table
            ->query($query)
            ->poll('60s')
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('recipients_name'),

                TextColumn::make('recipients_telp_no')
                    ->label('Telephone'),

                TextColumn::make('city.name'),

                TextColumn::make('user.name')
                    ->visible(fn ($record) => auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
            ])
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
            'index' => Pages\ListDeliveryAddresses::route('/'),
            'create' => Pages\CreateDeliveryAddress::route('/create'),
            'view' => Pages\ViewDeliveryAddress::route('/{record}'),
            'edit' => Pages\EditDeliveryAddress::route('/{record}/edit'),
        ];
    }

}
