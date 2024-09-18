<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PaymentReceipt;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\PaymentReceiptResource\Pages;
use App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

class PaymentReceiptResource extends Resource
{
    protected static ?string $model = PaymentReceipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.paymentReceipts.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.paymentReceipts.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.paymentReceipts.collectionTitle');
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

                    Select::make('payment_for')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'fuel service',
                            '2' => 'daily salary',
                            '3' => 'invoice',
                        ]),

                    Select::make('supplier_id')
                        ->nullable()
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('user_id')
                        ->nullable()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('total_amount')
                        ->required()
                        ->numeric()
                        ->step(1)
                        ->prefix('Rp '),

                    TextInput::make('transfer_amount')
                        ->required()
                        ->numeric()
                        ->step(1)
                        ->prefix('Rp'),

                    FileUpload::make('image_adjust')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

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

                TextColumn::make('payment_for'),

                TextColumn::make('supplier.name'),

                TextColumn::make('user.name'),

                TextColumn::make('total_amount')->numeric(
                    decimalSeparator: ',',
                    thousandsSeparator: '.'
                ),

                TextColumn::make('transfer_amount')->numeric(
                    decimalSeparator: ',',
                    thousandsSeparator: '.'
                ),

                ImageColumn::make('image_adjust')->visibility('public'),
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
        return [
            RelationManagers\FuelServicesRelationManager::class,
            RelationManagers\DailySalariesRelationManager::class,
            RelationManagers\InvoicePurchasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentReceipts::route('/'),
            'create' => Pages\CreatePaymentReceipt::route('/create'),
            'view' => Pages\ViewPaymentReceipt::route('/{record}'),
            'edit' => Pages\EditPaymentReceipt::route('/{record}/edit'),
        ];
    }
}
