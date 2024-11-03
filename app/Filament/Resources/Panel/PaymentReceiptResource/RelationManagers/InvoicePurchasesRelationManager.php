<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\SupplierColumn;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\StoreSelect;
use App\Filament\Forms\SupplierSelect;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\PaymentReceiptResource;
use App\Filament\Tables\InvoicePurchaseTable;
use App\Models\InvoicePurchase;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InvoicePurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicePurchases';

    protected static ?string $recordTitleAttribute = 'invoice_purchase_name';

    public function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(
                InvoicePurchaseTable::schema()
            )
            ->filters([])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    // ->multiple()
                    ->recordTitle(fn(InvoicePurchase $record): string => "{$record->invoice_purchase_name}")
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query
                        ->where('payment_status', 1)
                        ->where('payment_type_id', 1))
                    ->after(function (?InvoicePurchase $record) { // Note the ? before InvoicePurchase
                        $record->update(['payment_status' => 2]);
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make()
                    ->action(function ($record) {
                        $record->pivot->delete();
                        $record->update(['payment_status' => 1]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\DetachBulkAction::make()
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                if ($record->pivot) {
                                    $record->pivot->delete();
                                }
                                $record->update(['payment_status' => 1]);
                            }
                        }),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->payment_for === 3;
    }
}
