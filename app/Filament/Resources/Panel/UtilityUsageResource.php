<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Stock;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StatusSelectInput;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UtilityUsage;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\UtilityUsageResource\Pages;
use App\Models\Utility;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class UtilityUsageResource extends Resource
{
    protected static ?string $model = UtilityUsage::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Stock::class;

    protected static ?string $navigationGroup = 'Consumption';

    public static function getModelLabel(): string
    {
        return __('crud.utilityUsages.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.utilityUsages.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.utilityUsages.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    ImageInput::make('image')
                        ->disk('public')
                        ->directory('images/UtilityUsage'),

                    Select::make('utility_id')
                        ->required()
                        ->relationship(
                            name: 'utility',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (Utility $record) => "{$record->utility_name}")
                        ->preload(),

                    TextInput::make('result')
                        ->required()
                        ->numeric(),

                    StatusSelectInput::make('status')
                        ->required()
                        ->hidden(fn ($operation) => $operation === 'create'),

                    Notes::make('notes'),

                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $utilityUsage = UtilityUsage::query();

        if (!Auth::user()->hasRole('admin')) {
            $utilityUsage->where('created_by_id', Auth::id());
        }

        return $table
            ->poll('60s')
            ->query($utilityUsage)
            ->columns([
                ImageOpenUrlColumn::make('image')
                    ->visibility('public')
                    ->url(fn($record) => asset('storage/' . $record->image)),

                TextColumn::make('created_at')
                    ->sortable()
                    ->date(),

                TextColumn::make('utility.utility_column_name'),

                TextColumn::make('result'),

                StatusColumn::make('status'),

                TextColumn::make('createdBy.name'),

                TextColumn::make('approvedBy.name'),
            ])
            ->filters([

                SelectFilter::make('utility_id')
                    ->label('Utility')
                    ->relationship(
                        name: 'utility',
                        titleAttribute: 'utility_name',
                        modifyQueryUsing: fn (Builder $query) => $query,
                    )
                    ->getOptionLabelFromRecordUsing(fn (Utility $record) => "{$record->utility_name}"),

            ])
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
            'index' => Pages\ListUtilityUsages::route('/'),
            'create' => Pages\CreateUtilityUsage::route('/create'),
            'view' => Pages\ViewUtilityUsage::route('/{record}'),
            'edit' => Pages\EditUtilityUsage::route('/{record}/edit'),
        ];
    }
}
