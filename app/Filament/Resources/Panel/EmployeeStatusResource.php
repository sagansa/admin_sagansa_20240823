<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmployeeStatus;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\EmployeeStatusResource\Pages;
use App\Filament\Resources\Panel\EmployeeStatusResource\RelationManagers;

class EmployeeStatusResource extends Resource
{
    protected static ?string $model = EmployeeStatus::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Personal Data';

    protected static ?string $cluster = HRD::class;

    public static function getModelLabel(): string
    {
        return __('crud.employeeStatuses.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.employeeStatuses.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.employeeStatuses.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([TextColumn::make('name')])
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
            'index' => Pages\ListEmployeeStatuses::route('/'),
            'create' => Pages\CreateEmployeeStatus::route('/create'),
            'view' => Pages\ViewEmployeeStatus::route('/{record}'),
            'edit' => Pages\EditEmployeeStatus::route('/{record}/edit'),
        ];
    }
}
