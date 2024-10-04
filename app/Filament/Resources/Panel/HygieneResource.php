<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Store;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Forms\BaseRepeaterSelect;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Hygiene;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\HygieneResource\Pages;
use App\Filament\Resources\Panel\HygieneResource\RelationManagers;
use App\Models\Room;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\ActionGroup;

class HygieneResource extends Resource
{
    protected static ?string $model = Hygiene::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Hygiene';

    protected static ?string $cluster = Store::class;

    public static function getModelLabel(): string
    {
        return __('crud.hygienes.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.hygienes.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.hygienes.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    StoreSelect::make('store_id'),

                    // Notes::make('notes'),

                ]),
            ]),

            Section::make()->schema(
                self::getItemsRepeater(),
            ),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('store.nickname'),

                TextColumn::make('created_at'),

                TextColumn::make('createdBy.name')
                    ->visible(fn ($record) => auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')),

                TextColumn::make('approvedBy.name'),


            ])
            ->filters([
                SelectStoreFilter::make('store_id'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\HygieneOfRoomsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHygienes::route('/'),
            'create' => Pages\CreateHygiene::route('/create'),
            'view' => Pages\ViewHygiene::route('/{record}'),
            'edit' => Pages\EditHygiene::route('/{record}/edit'),
        ];
    }

    public static function getItemsRepeater(): array
    {
        $rooms = Room::orderBy('name', 'asc')->get()->map(function ($item) {
            return [
                'room_id' => $item->id,
                'image' => $item->image,
            ];
        })->toArray();

        return [
            Repeater::make('hygieneOfRooms')
                ->hiddenLabel()
                ->default($rooms)
                ->relationship()
                ->addable(false)
                ->deletable(false)
                ->schema([
                    BaseRepeaterSelect::make('room_id')
                        ->relationship('room', 'name'),

                    ImageInput::make('image')
                        ->multiple()
                        ->hiddenLabel()
                        ->directory('images/Hygiene'),
            ])
        ];
    }
}
