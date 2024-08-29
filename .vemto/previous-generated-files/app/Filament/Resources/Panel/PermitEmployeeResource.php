<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PermitEmployee;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\PermitEmployeeResource\Pages;
use App\Filament\Resources\Panel\PermitEmployeeResource\RelationManagers;

class PermitEmployeeResource extends Resource
{
    protected static ?string $model = PermitEmployee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.permitEmployees.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.permitEmployees.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.permitEmployees.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('reason')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'menikah',
                            '2' => 'sakit',
                            '3' => 'pulkam',
                            '4' => 'libur',
                            '5' => 'keluarga meninggal',
                        ]),

                    DatePicker::make('from_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    DatePicker::make('until_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'belum disetujui',
                            '2' => 'disetujui',
                            '3' => 'tidak disetujui',
                            '4' => 'pengajuan ulang',
                        ]),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('reason'),

                TextColumn::make('from_date')->since(),

                TextColumn::make('until_date')->since(),

                TextColumn::make('status'),

                TextColumn::make('created_by_id'),

                TextColumn::make('createdBy.name'),

                TextColumn::make('notes')->limit(255),
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
            'index' => Pages\ListPermitEmployees::route('/'),
            'create' => Pages\CreatePermitEmployee::route('/create'),
            'view' => Pages\ViewPermitEmployee::route('/{record}'),
            'edit' => Pages\EditPermitEmployee::route('/{record}/edit'),
        ];
    }
}
