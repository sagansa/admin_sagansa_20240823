<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Advances;
use App\Filament\Clusters\Purchases;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\CurrencyMinusInput;
use App\Filament\Forms\CurrencyRepeaterInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StatusSelectLabel;
use App\Filament\Forms\StoreSelect;
use App\Filament\Forms\SupplierSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CashAdvance;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\CashAdvanceResource\Pages;
use App\Filament\Resources\Panel\CashAdvanceResource\RelationManagers;
use App\Models\AdvancePurchase;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;

class CashAdvanceResource extends Resource
{
    protected static ?string $model = CashAdvance::class;

    // protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Purchases::class;

    protected static ?string $navigationGroup = 'Advances';

    public static function getModelLabel(): string
    {
        return __('crud.cashAdvances.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.cashAdvances.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.cashAdvances.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema(static::getDetailsFormHeadSchema()),

            Section::make()
                ->schema([
                    static::getItemsRepeater()
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        $cashAdvance = CashAdvance::query();

        if (!Auth::user()->hasRole('admin')) {
            $cashAdvance->where('user_id', Auth::id());
        }

        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('date'),

                CurrencyColumn::make('transfer'),

                CurrencyColumn::make('purchase'),

                CurrencyColumn::make('before'),

                CurrencyColumn::make('remains'),

                TextColumn::make('user.name'),

                StatusColumn::make('status'),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('user')
                    ->relationship('user', 'name')
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

    public static function getDetailsFormHeadSchema(): array
    {
        return [
            Grid::make(['default' => 1])->schema([
                ImageInput::make('image')

                    ->directory('images/CashAdvance'),

                Group::make()->schema([
                    Select::make('user_id')
                        ->label('User')
                        ->inlineLabel()
                        ->required()
                        ->relationship('user', 'name', fn (Builder $query) => $query
                            ->whereHas('roles', fn (Builder $query) => $query
                                ->where('name', 'staff') || $query
                                ->where('name', 'supervisor')))
                        ->searchable()
                        ->preload()
                        ->columnSpan([
                            'md' => 4,
                        ]),

                    DateInput::make('date')
                        ->columnSpan([
                            'md' => 4,
                        ]),

                    StatusSelectLabel::make('status')
                        ->label('Status')
                        ->inlineLabel()
                        ->columnSpan([
                            'md' => 4,
                        ]),

                    CurrencyInput::make('transfer')
                        ->label('Transfer')
                        ->debounce(2000)
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            self::updateTotalPurchase($get, $set);
                        })
                        ->columnSpan([
                            'md' => 3,
                        ]),

                    CurrencyMinusInput::make('before')
                        ->label('Before')
                        ->debounce(2000)
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            self::updateTotalPurchase($get, $set);
                        })
                        ->columnSpan([
                            'md' => 3,
                        ]),

                    CurrencyInput::make('purchase')
                        ->readOnly()
                        ->label('Purchase')
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            self::updateTotalPurchase($get, $set);
                        })
                        ->columnSpan([
                            'md' => 3,
                        ]),

                    CurrencyMinusInput::make('remains')
                        ->readOnly()
                        ->label('Remains')
                        ->columnSpan([
                            'md' => 3,
                        ]),

                    ])->columns([
                        'md' => 12,
                ]),

                Notes::make('notes'),

            ])->afterStateUpdated(function (Set $set, Get $get) {
                self::updateTotalPurchase($get, $set);
            }),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\AdvancePurchasesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashAdvances::route('/'),
            'create' => Pages\CreateCashAdvance::route('/create'),
            'view' => Pages\ViewCashAdvance::route('/{record}'),
            'edit' => Pages\EditCashAdvance::route('/{record}/edit'),
        ];
    }

    protected static function updateTotalPurchase(Get $get, Set $set): void
    {
        $repeaterItems = $get('advancePurchases') ?? [];

        $totalPurchase = 0;

        foreach ($repeaterItems as $item) {
            if (isset($item['total_price'])) {
                $totalPurchase += (int) $item['total_price'];
            }
        }

        $set('purchase', $totalPurchase);

        $transfer = $get('transfer') !== null ? (int) $get('transfer') : 0;
        $before = $get('before') !== null ? (int) $get('before') : 0;
        $remains = $transfer + $before - $totalPurchase;

        $set('remains', $remains);
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('advancePurchases')
            ->hiddenLabel()
            ->columns(['md' => 8])
            ->relationship()
            ->schema([
                Select::make('supplier_id')
                    ->hiddenLabel()
                    ->disabled()
                    ->relationship('supplier', 'name')
                    ->columnSpan(4),

                Select::make('store_id')
                    ->hiddenLabel()
                    ->disabled()
                    ->relationship('store', 'nickname')
                    ->columnSpan(2),

                CurrencyRepeaterInput::make('total_price')
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotalPurchase($get, $set);
                    })
                    ->columnSpan(2),
            ])
            ->afterStateUpdated(function (Get $get, Set $set) {
                self::updateTotalPurchase($get, $set);
            });
    }
}
