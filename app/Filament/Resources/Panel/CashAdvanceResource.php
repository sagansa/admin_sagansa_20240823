<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Advances;
use App\Filament\Clusters\Purchases;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StatusSelectInput;
use App\Filament\Forms\StatusSelectLabel;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\CashAdvanceResource\Pages;
use App\Filament\Resources\Panel\CashAdvanceResource\RelationManagers;
use App\Models\AdvancePurchase;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

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

                CurrencyColumn::make('before'),


                CurrencyColumn::make('purchase'),

                CurrencyColumn::make('remains'),

                TextColumn::make('user.name'),

                StatusColumn::make('status'),
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
            ->defaultSort('date', 'desc');
    }

    public static function getDetailsFormHeadSchema(): array
    {
        return [
            Grid::make(['default' => 1])->schema([
                ImageInput::make('image'),

                Group::make()->schema([
                    Select::make('user_id')
                        ->label('User')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->columnSpan([
                            'md' => 4,
                        ]),

                    DatePicker::make('date')
                        ->default('today')
                        ->columnSpan([
                            'md' => 4,
                        ]),

                    StatusSelectLabel::make('status')
                        ->label('Status')
                        ->columnSpan([
                            'md' => 4,
                        ]),

                    TextInput::make('transfer')
                        ->required()
                        ->label('Transfer')
                        ->default(0)
                        ->minValue(0)
                        ->numeric()
                        ->debounce(500)
                        ->prefix('Rp')
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            self::updatePurchase($get, $set);
                        })
                        ->columnSpan([
                            'md' => 3,
                        ]),

                    TextInput::make('before')
                        ->required()
                        ->label('Before')
                        ->default(0)
                        ->minValue(0)
                        ->numeric()
                        ->debounce(500)
                        ->prefix('Rp')
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            self::updatePurchase($get, $set);
                        })
                        ->columnSpan([
                            'md' => 3,
                        ]),

                     TextInput::make('purchase')
                        ->readOnly()
                        ->label('Purchase')
                        ->numeric()
                        ->prefix('Rp')
                        ->columnSpan([
                            'md' => 3,
                        ]),

                    TextInput::make('remains')
                        ->readOnly()
                        ->label('Remains')
                        ->numeric()
                        ->prefix('Rp')
                        ->columnSpan([
                            'md' => 3,
                        ]),

                    ])->columns([
                        'md' => 12,
                ]),

                Notes::make('notes'),

            ]),
        ];
    }

    public static function getRelations(): array
    {
        return [RelationManagers\AdvancePurchasesRelationManager::class];
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

    protected static function updatePurchase(Get $get, Set $set): void
    {
        $selectedAdvancePurchases = collect($get('advancePurchases'))->filter(fn($advancePurchase) => !empty($advancePurchase['advance_purchase_id']));

        $totalPrices = AdvancePurchase::whereIn('id', $selectedAdvancePurchases->pluck('advance_purchase_id'))->pluck('total_price', 'id');
        $purchase = $selectedAdvancePurchases->reduce(function ($purchase, $advancePurchase) use ($totalPrices) {
            return $purchase + ($totalPrices[$advancePurchase['advance_purchase_id']] ?? 0);
        }, 0);

        $set('purchase', $purchase);

        $transfer = $get('transfer') !== null ? (int) $get('transfer') : 0;
        $before = $get('before') !== null ? (int) $get('before') : 0;
        $remains = $transfer + $before - $purchase;

        $set('remains', $remains);
    }
}
