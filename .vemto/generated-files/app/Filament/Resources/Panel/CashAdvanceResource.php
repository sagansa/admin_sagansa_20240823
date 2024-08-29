<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
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
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\CashAdvanceResource\Pages;
use App\Filament\Resources\Panel\CashAdvanceResource\RelationManagers;

class CashAdvanceResource extends Resource
{
    protected static ?string $model = CashAdvance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

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
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    FileUpload::make('image')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    DatePicker::make('date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('transfer')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('before')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('purchase')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('remains')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

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
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('date')->since(),

                TextColumn::make('transfer'),

                TextColumn::make('user.name'),

                TextColumn::make('before'),

                TextColumn::make('purchase'),

                TextColumn::make('remains'),

                TextColumn::make('status'),

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
            'index' => Pages\ListCashAdvances::route('/'),
            'create' => Pages\CreateCashAdvance::route('/create'),
            'view' => Pages\ViewCashAdvance::route('/{record}'),
            'edit' => Pages\EditCashAdvance::route('/{record}/edit'),
        ];
    }
}
