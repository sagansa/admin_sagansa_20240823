<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Invoices;
use App\Filament\Clusters\Purchases;
use App\Filament\Columns\StatusColumn;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\StoreSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RequestPurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\RequestPurchaseResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;

class RequestPurchaseResource extends Resource
{
    protected static ?string $model = RequestPurchase::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Invoice';

    protected static ?string $cluster = Purchases::class;

    protected static ?string $pluralLabel = 'Invoice';

    public static function getModelLabel(): string
    {
        return __('crud.requestPurchases.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.requestPurchases.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.requestPurchases.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    StoreSelect::make('store_id'),

                    DateInput::make('date'),

                ]),
            ]),

            Section::make()->schema([
                self::getItemsRepeater(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $requestPurchases = RequestPurchase::query();

        if (!Auth::user()->hasRole('admin')) {
            $requestPurchases->where('user_id', Auth::id());
        }

        return $table
            ->query($requestPurchases)
            ->poll('60s')
            ->columns([
                TextColumn::make('store.nickname'),

                TextColumn::make('date'),

                TextColumn::make('detailRequests')
                    ->label('Orders')
                    ->formatStateUsing(function (RequestPurchase $record) {
                        return implode('<br>', $record->detailRequests->map(function ($item) {
                            $statusLabels = [
                                '1' => '<span class="badge bg-warning">process</span>',
                                '2' => '<span class="badge bg-success">done</span>',
                                '3' => '<span class="badge bg-danger">reject</span>',
                                '4' => '<span class="badge bg-info">approved</span>',
                                '5' => '<span class="badge bg-secondary">not valid</span>',
                                '6' => '<span class="bg-gray-500 badge">not used</span>',
                            ];

                            // Pilih label status berdasarkan nilai status
                            $status = $statusLabels[$item->status] ?? '<span class="badge bg-default">unknown</span>';

                            return "{$item->product->name} = {$item->quantity_plan} {$item->product->unit->unit} ({$status})";
                        })->toArray());
                    })
                    ->html() // Mengizinkan HTML dalam kolom
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

                TextColumn::make('user.name')->hidden(fn () => !Auth::user()->hasRole('admin')),

                // StatusColumn::make('status'),
            ])
            ->filters([
                SelectStoreFilter::make('store_id')
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
            'index' => Pages\ListRequestPurchases::route('/'),
            'create' => Pages\CreateRequestPurchase::route('/create'),
            'view' => Pages\ViewRequestPurchase::route('/{record}'),
            'edit' => Pages\EditRequestPurchase::route('/{record}/edit'),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {

        return Repeater::make('detailRequests')
            ->relationship()
            ->minItems(1)
            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, RequestPurchase $record): array {
                $data['store_id'] = $record->store_id;
                $data['payment_type_id'] = optional(Product::find($data['product_id']))->payment_type_id;
                $data['status'] = '1';

                return $data;
            })
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    // ->disabled(fn (Get $get) => $get('status') != 1)
                    ->reactive()
                    ->columnSpan(4),

                TextInput::make('quantity_plan')
                    ->required()
                    ->minValue(1)
                    ->numeric()
                    ->suffix(function (Get $get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->columnSpan(2),

                Select::make('status')
                    ->options([
                        '1' => 'process',
                        '2' => 'done',
                        '3' => 'reject',
                        '4' => 'approved',
                        '5' => 'not valid',
                        '6' => 'not used',
                    ])
                    ->default(1)
                    ->disabled(fn () => !Auth::user()->hasRole('admin'))
                    ->columnSpan(2),

            ])
            ->columns([
                'md' => 8,
            ])
            ->defaultItems(1);
    }
}
