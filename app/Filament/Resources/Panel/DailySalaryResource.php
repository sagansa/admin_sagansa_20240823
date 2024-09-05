<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\PaymentStatusColumn;
use App\Filament\Forms\BaseSelectInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\PaymentStatusSelectInput;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DailySalary;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Panel\DailySalaryResource\Pages;
use App\Filament\Tables\DailySalaryTable;
use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DailySalaryResource extends Resource
{
    protected static ?string $model = DailySalary::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'HRD';

    public static function getModelLabel(): string
    {
        return __('crud.dailySalaries.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.dailySalaries.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.dailySalaries.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    BaseSelectInput::make('store_id')
                        ->placeholder('Store')
                        ->relationship('store', 'nickname'),

                    BaseSelectInput::make('shift_store_id')
                        ->placeholder('Shift Store')
                        ->relationship('shiftStore', 'name'),

                    DateInput::make('date'),

                    TextInput::make('amount')
                        ->required()
                        ->prefix('Rp ')
                        ->numeric(),

                    Select::make('payment_type_id')
                        ->required()
                        // ->relationship('paymentType', 'name')
                        ->relationship(
                            name: 'paymentType',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (PaymentType $record) => "{$record->name}")
                        ->searchable()
                        ->preload()
                        ->native(false),

                    PaymentStatusSelectInput::make('status'),

                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns(
                DailySalaryTable::schema()
            )
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn ($record) => auth()->user()->can('update', $record)),
                Tables\Actions\ViewAction::make()->visible(fn ($record) => auth()->user()->can('view', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setStatusToThree')
                        ->label('Set Status to Siap Dibayar')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            DailySalary::whereIn('id', $records->pluck('id'))->update(['status' => 3]);
                        })
                        ->color('warning'),
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
            'index' => Pages\ListDailySalaries::route('/'),
            'create' => Pages\CreateDailySalary::route('/create'),
            'view' => Pages\ViewDailySalary::route('/{record}'),
            'edit' => Pages\EditDailySalary::route('/{record}/edit'),
        ];
    }
}
