<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AccountCashless;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\AccountCashlessResource\Pages;
use App\Filament\Resources\Panel\AccountCashlessResource\RelationManagers;

class AccountCashlessResource extends Resource
{
    protected static ?string $model = AccountCashless::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Setting';

    public static function getModelLabel(): string
    {
        return __('crud.accountCashlesses.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.accountCashlesses.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.accountCashlesses.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('cashless_provider_id')
                        ->required()
                        ->relationship('cashlessProvider', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('store_cashless_id')
                        ->required()
                        ->relationship('storeCashless', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('email')
                        ->nullable()
                        ->string()
                        ->email(),

                    TextInput::make('username')
                        ->nullable()
                        ->string(),

                    TextInput::make('password')
                        ->nullable()
                        ->string()
                        ->minLength(6)
                        ->password(),

                    TextInput::make('no_telp')
                        ->nullable()
                        ->string(),

                    TextInput::make('status')
                        ->required()
                        ->numeric()
                        ->step(1),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('cashlessProvider.name'),

                TextColumn::make('store.name'),

                TextColumn::make('storeCashless.name'),

                TextColumn::make('email'),

                TextColumn::make('username'),

                TextColumn::make('password'),

                TextColumn::make('no_telp'),

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
            'index' => Pages\ListAccountCashlesses::route('/'),
            'create' => Pages\CreateAccountCashless::route('/create'),
            'view' => Pages\ViewAccountCashless::route('/{record}'),
            'edit' => Pages\EditAccountCashless::route('/{record}/edit'),
        ];
    }
}
