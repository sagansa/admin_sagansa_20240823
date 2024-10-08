<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ContractEmployee;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\Panel\ContractEmployeeResource\Pages;
use App\Filament\Resources\Panel\ContractEmployeeResource\RelationManagers;

class ContractEmployeeResource extends Resource
{
    protected static ?string $model = ContractEmployee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'HRD';

    public static function getModelLabel(): string
    {
        return __('crud.contractEmployees.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.contractEmployees.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.contractEmployees.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    FileUpload::make('file')
                        ->rules(['file'])
                        ->nullable()
                        ->maxSize(1024),

                    DatePicker::make('from_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    DatePicker::make('until_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('nominal_guarantee')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('guarantee')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Checkbox::make('guaranteed_return')
                        ->rules(['boolean'])
                        ->required()
                        ->inline(),

                    Select::make('employee_id')
                        ->nullable()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
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
                TextColumn::make('file'),

                TextColumn::make('from_date')->since(),

                TextColumn::make('until_date')->since(),

                TextColumn::make('nominal_guarantee'),

                TextColumn::make('guarantee'),

                CheckboxColumn::make('guaranteed_return'),

                TextColumn::make('employee_id'),

                TextColumn::make('user.name'),
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
            'index' => Pages\ListContractEmployees::route('/'),
            'create' => Pages\CreateContractEmployee::route('/create'),
            'view' => Pages\ViewContractEmployee::route('/{record}'),
            'edit' => Pages\EditContractEmployee::route('/{record}/edit'),
        ];
    }
}
