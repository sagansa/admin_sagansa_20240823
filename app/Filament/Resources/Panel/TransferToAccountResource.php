<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Transaction\Settings;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\TransferToAccount;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\TransferToAccountResource\Pages;
use App\Filament\Resources\Panel\TransferToAccountResource\RelationManagers;

class TransferToAccountResource extends Resource
{
    protected static ?string $model = TransferToAccount::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Setting';

    protected static ?string $cluster = Settings::class;

    protected static ?string $pluralLabel = 'Transfer To Accounts';

    public static function getModelLabel(): string
    {
        return __('crud.transferToAccounts.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.transferToAccounts.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.transferToAccounts.collectionTitle');
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

                    TextInput::make('number')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('bank_id')
                        ->required()
                        ->relationship('bank', 'name')
                        ->searchable()
                        ->preload(),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('number'),

                TextColumn::make('bank.name'),

                TextColumn::make('status'),
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
            'index' => Pages\ListTransferToAccounts::route('/'),
            'create' => Pages\CreateTransferToAccount::route('/create'),
            'view' => Pages\ViewTransferToAccount::route('/{record}'),
            'edit' => Pages\EditTransferToAccount::route('/{record}/edit'),
        ];
    }
}
