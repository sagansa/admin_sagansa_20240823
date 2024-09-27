<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Transaction\Settings;
use App\Filament\Columns\ActiveColumn;
use App\Filament\Forms\ActiveStatusSelect;
use App\Filament\Forms\BaseSelect;
use App\Filament\Forms\BaseTextInput;
use App\Filament\Forms\NominalInput;
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
use Filament\Tables\Actions\ActionGroup;

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
                Grid::make(['default' => 2])->schema([
                    BaseTextInput::make('name'),

                    NominalInput::make('number'),

                    BaseSelect::make('bank_id')
                        ->relationship('bank', 'name'),

                    ActiveStatusSelect::make('status'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('name')->searchable(),

                TextColumn::make('number')->searchable(),

                TextColumn::make('bank.name')->searchable(),

                ActiveColumn::make('status'),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
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
