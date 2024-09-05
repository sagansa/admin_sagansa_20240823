<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ClosingCourier;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\ClosingCourierResource\Pages;
use App\Filament\Resources\Panel\ClosingCourierResource\RelationManagers;

class ClosingCourierResource extends Resource
{
    protected static ?string $model = ClosingCourier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Cash';

    public static function getModelLabel(): string
    {
        return __('crud.closingCouriers.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.closingCouriers.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.closingCouriers.collectionTitle');
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

                    Select::make('bank_id')
                        ->required()
                        ->relationship('bank', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('total_cash_to_transfer')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('created_by_id')
                        ->nullable()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('approved_by_id')
                        ->required()
                        ->relationship('createdBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'belum diperiksa',
                            '2' => 'valid',
                            '3' => 'diperbaiki',
                            '4' => 'periksa ulang',
                        ]),

                    RichEditor::make('notes')
                        ->nullable()
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

                TextColumn::make('bank.name'),

                TextColumn::make('total_cash_to_transfer'),

                TextColumn::make('created_by_id'),

                TextColumn::make('createdBy.name'),

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
        return [RelationManagers\ClosingStoresRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClosingCouriers::route('/'),
            'create' => Pages\CreateClosingCourier::route('/create'),
            'view' => Pages\ViewClosingCourier::route('/{record}'),
            'edit' => Pages\EditClosingCourier::route('/{record}/edit'),
        ];
    }
}
