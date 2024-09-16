<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Transaction\Settings;
use App\Filament\Columns\ActiveColumn;
use App\Filament\Forms\ActiveStatusSelect;
use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Utility;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\UtilityResource\Pages;
use App\Filament\Resources\Panel\UtilityResource\RelationManagers;
use Illuminate\Database\Eloquent\Collection;

class UtilityResource extends Resource
{
    protected static ?string $model = Utility::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    // protected static ?string $navigationLabel = 'Custom Navigation Label';

    protected static ?string $navigationGroup = 'Utility';

    protected static ?string $cluster = Settings::class;

    protected static ?string $pluralLabel = 'Utilities';

    public static function getModelLabel(): string
    {
        return __('crud.utilities.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.utilities.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.utilities.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    TextInput::make('number')
                        ->required()
                        ->string()
                        ->unique('utilities', 'number', ignoreRecord: true)
                        ->autofocus(),

                    TextInput::make('name')
                        ->required()
                        ->string(),

                    StoreSelect::make('store_id')
                        ->required()
                        ->sortable(),

                    ActiveStatusSelect::make('status'),

                    Select::make('unit_id')
                        ->required()
                        ->relationship('unit', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('utility_provider_id')
                        ->required()
                        ->relationship('utilityProvider', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('pre_post')
                        ->required()
                        ->options([
                            '1' => 'pre',
                            '2' => 'post'
                        ])
                        ->preload()
                        ->native(false),

                    Select::make('category')
                        ->options([
                            '1' => 'listrik',
                            '2' => 'air',
                            '3' => 'internet'
                        ])
                        ->required()
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
            ->columns([
                TextColumn::make('number')
                    ->copyable(),

                TextColumn::make('name'),

                TextColumn::make('store.nickname'),

                TextColumn::make('unit.unit'),

                TextColumn::make('utilityProvider.name'),

                TextColumn::make('pre_post')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'pre',
                            '2' => 'post',
                        }
                    ),

                TextColumn::make('category')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'listrik',
                            '2' => 'air',
                            '3' => 'internet',
                        }
                    ),

                ActiveColumn::make('status'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setStatusToInactive')
                    ->label('Set Status to Inactive')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        Utility::whereIn('id', $records->pluck('id'))->update(['status' => 2]);
                    })
                    ->color('warning'),
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
            'index' => Pages\ListUtilities::route('/'),
            'create' => Pages\CreateUtility::route('/create'),
            'view' => Pages\ViewUtility::route('/{record}'),
            'edit' => Pages\EditUtility::route('/{record}/edit'),
        ];
    }
}
