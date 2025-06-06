<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Forms\BaseSelect;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\DecimalInput;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UtilityBill;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\UtilityBillResource\Pages;
use App\Models\Utility;
use Filament\Forms\Get;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;

class UtilityBillResource extends Resource
{
    protected static ?string $model = UtilityBill::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Purchases::class;

    // protected static ?string $navigationGroup = 'Purchase';

    public static function getModelLabel(): string
    {
        return __('crud.utilityBills.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.utilityBills.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.utilityBills.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 2])->schema([
                    BaseSelect::make('utility_id')
                        ->required()
                        ->relationship('utility', 'name')
                        ->relationship(
                            name: 'utility',
                            modifyQueryUsing: fn(Builder $query) => $query->where('status', '1'),
                        )
                        ->getOptionLabelFromRecordUsing(fn(Utility $record) => "{$record->utility_name}")
                        ->searchable()
                        ->reactive()
                        ->preload(),

                    DateInput::make('date'),

                    CurrencyInput::make('amount')
                        ->numeric()
                        ->prefix('Rp'),

                    DecimalInput::make('initial_indicator')
                        ->suffix(function (Get $get) {
                            $utility = Utility::find($get('utility_id'));
                            return $utility ? $utility->unit->unit : '';
                        }),

                    DecimalInput::make('last_indicator')
                        ->suffix(function (Get $get) {
                            $utility = Utility::find($get('utility_id'));
                            return $utility ? $utility->unit->unit : '';
                        }),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('utility.number')->copyable(),

                TextColumn::make('utility.store.nickname'),

                TextColumn::make('utility.utilityProvider.name'),

                TextColumn::make('date')->sortable(),

                CurrencyColumn::make('amount'),

                TextColumn::make('initial_indicator'),

                TextColumn::make('last_indicator'),
            ])
            ->filters([
                SelectFilter::make('utility_id')
                    ->label('Utility')
                    ->searchable()
                    ->preload()
                    ->relationship(
                        name: 'utility',
                        titleAttribute: 'utility_name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('status', '1')->orderBy('number', 'asc'),
                    )
                    ->getOptionLabelFromRecordUsing(fn(Utility $record) => "{$record->utility_name}"),
            ])
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
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUtilityBills::route('/'),
            'create' => Pages\CreateUtilityBill::route('/create'),
            'view' => Pages\ViewUtilityBill::route('/{record}'),
            'edit' => Pages\EditUtilityBill::route('/{record}/edit'),
        ];
    }
}
