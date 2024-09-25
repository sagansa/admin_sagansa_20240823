<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use App\Filament\Clusters\Salaries;
use App\Filament\Forms\CurrencyInput;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MonthlySalary;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\MonthlySalaryResource\Pages;
use App\Filament\Resources\Panel\MonthlySalaryResource\RelationManagers;

class MonthlySalaryResource extends Resource
{
    protected static ?string $model = MonthlySalary::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Salaries';

    protected static ?string $pluralLabel = 'Monthly Salaries';

    protected static ?string $cluster = HRD::class;

    public static function getModelLabel(): string
    {
        return __('crud.monthlySalaries.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.monthlySalaries.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.monthlySalaries.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    CurrencyInput::make('amount')
                        ->autofocus(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([TextColumn::make('amount')])
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
            'index' => Pages\ListMonthlySalaries::route('/'),
            'create' => Pages\CreateMonthlySalary::route('/create'),
            'view' => Pages\ViewMonthlySalary::route('/{record}'),
            'edit' => Pages\EditMonthlySalary::route('/{record}/edit'),
        ];
    }
}
