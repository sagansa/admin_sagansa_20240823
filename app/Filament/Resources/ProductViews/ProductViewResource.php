<?php

namespace App\Filament\Resources\ProductViews;

use App\Filament\Resources\ProductViews\Pages\ManageProductViews;
use App\Models\ProductView;
use BackedEnum;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductViewResource extends Resource
{
    protected static ?string $model = ProductView::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEye;
    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Product View';
    protected static ?string $pluralModelLabel = 'Product Views';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Read-only
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->default('Guest'),
                \Filament\Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(30),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Viewed At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProductViews::route('/'),
        ];
    }
}
