<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Filters\DateFilter;
use App\Filament\Filters\SelectPaymentTypeFilter;
use App\Filament\Filters\SelectStoreFilter;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DetailRequest;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Panel\DetailRequestResource\Pages;
use App\Filament\Resources\Panel\DetailRequestResource\RelationManagers;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class DetailRequestResource extends Resource
{
    protected static ?string $model = DetailRequest::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Invoice';

    protected static ?string $cluster = Purchases::class;

    protected static ?string $pluralLabel = 'Invoice';

    public static function getModelLabel(): string
    {
        return __('crud.detailRequests.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.detailRequests.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.detailRequests.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 2])->schema([
                    Select::make('product_id')
                        ->required()
                        ->inlineLabel()
                        ->relationship('product', 'name')
                        ->disabled()
                        ->preload(),

                    Select::make('payment_type_id')
                        ->required()
                        ->inlineLabel()
                        ->relationship('paymentType', 'name')
                        ->preload()
                        ,
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->groups([
                'requestPurchase.date',
                'store.nickname'
            ])
            // ->defaultGroup('requestPurchase.date')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product'),
                TextColumn::make('requestPurchase.date')
                    ->label('Request Date'),
                TextColumn::make('paymentType.name')
                    ->label('Payment Type'),
                TextColumn::make('store.nickname')
                    ->label('Store'),

                TextColumn::make('detailInvoices.quantity_product')
                    ->formatStateUsing(fn (DetailRequest $record) => implode(', ', $record->detailInvoices->pluck('quantity_product')->all()))
                    ->label('Qty Purchase'),

                TextColumn::make('quantity_plan')
                    ->label('Qty Plan')
                    ->formatStateUsing(fn (DetailRequest $record) =>
                        number_format($record->quantity_plan, 0, ',', '.') . ' ' .
                            $record->product->unit->unit),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            '1' => 'warning',
                            '2' => 'success',
                            '3' => 'danger',
                            '4' => 'warning',
                            '5' => 'danger',
                            '6' => 'gray',
                            default => $state,
                        }
                    )
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'process',
                            '2' => 'done',
                            '3' => 'reject',
                            '4' => 'approved',
                            '5' => 'not valid',
                            '6' => 'not used',
                            default => $state,
                        }
                    ),

                    TextColumn::make('requestPurchase.user.name')
                        ->label('Request By')
                        ->hidden(fn () => !Auth::user()->hasRole('admin')),
            ])
            ->filters([
                SelectStoreFilter::make('store_id'),
                SelectPaymentTypeFilter::make('payment_type_id'),
                DateFilter::make('delivery_date'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    // Tables\Actions\ViewAction::make(),
                    Action::make('Update Payment Type To Cash')
                        ->icon('heroicon-o-pencil-square')
                        ->action(function ($record) {
                            $record->update(['payment_type_id' => 2]);
                        })
                        ->requiresConfirmation(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setStatusToDone')
                        ->label('Set Status to Done')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            DetailRequest::whereIn('id', $records->pluck('id'))->update(['status' => 2]);
                        })
                        ->color('success'),
                    Tables\Actions\BulkAction::make('setStatusToReject')
                        ->label('Set Status to Reject')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            DetailRequest::whereIn('id', $records->pluck('id'))->update(['status' => 3]);
                        })
                        ->color('danger'),
                    Tables\Actions\BulkAction::make('setStatusToNot Valid')
                        ->label('Set Status to Not Valid')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            DetailRequest::whereIn('id', $records->pluck('id'))->update(['status' => 5]);
                        })
                        ->color('warning'),
                    Tables\Actions\BulkAction::make('setStatusToNotUsed')
                        ->label('Set Status to Not Used')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            DetailRequest::whereIn('id', $records->pluck('id'))->update(['status' => 6]);
                        })
                        ->color('gray'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetailRequests::route('/'),
            'create' => Pages\CreateDetailRequest::route('/create'),
            'view' => Pages\ViewDetailRequest::route('/{record}'),
            'edit' => Pages\EditDetailRequest::route('/{record}/edit'),
        ];
    }
}
