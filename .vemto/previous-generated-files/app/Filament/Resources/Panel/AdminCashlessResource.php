<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AdminCashless;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\AdminCashlessResource\Pages;
use App\Filament\Resources\Panel\AdminCashlessResource\RelationManagers;

class AdminCashlessResource extends Resource
{
    protected static ?string $model = AdminCashless::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.adminCashlesses.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.adminCashlesses.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.adminCashlesses.collectionTitle');
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

                    TextInput::make('username')
                        ->nullable()
                        ->string(),

                    TextInput::make('email')
                        ->nullable()
                        ->string()
                        ->email(),

                    TextInput::make('no_telp')
                        ->nullable()
                        ->string(),

                    TextInput::make('password')
                        ->nullable()
                        ->string()
                        ->minLength(6)
                        ->password(),
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

                TextColumn::make('username'),

                TextColumn::make('email'),

                TextColumn::make('no_telp'),

                TextColumn::make('password'),
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
            'index' => Pages\ListAdminCashlesses::route('/'),
            'create' => Pages\CreateAdminCashless::route('/create'),
            'view' => Pages\ViewAdminCashless::route('/{record}'),
            'edit' => Pages\EditAdminCashless::route('/{record}/edit'),
        ];
    }
}
