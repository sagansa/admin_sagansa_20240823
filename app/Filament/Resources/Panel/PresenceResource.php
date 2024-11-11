<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use App\Filament\Columns\ImageOpenUrlColumn;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Presence;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Panel\PresenceResource\Pages;
use App\Filament\Resources\Panel\PresenceResource\RelationManagers;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class PresenceResource extends Resource
{
    protected static ?string $model = Presence::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = HRD::class;

    protected static ?string $navigationGroup = 'Personal Data';

    protected static ?string $pluralLabel = 'Presence';

    public static function getModelLabel(): string
    {
        return __('crud.presences.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.presences.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.presences.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'nickname')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('shift_store_id')
                        ->required()
                        ->relationship('shiftStore', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('status')
                        ->required()
                        ->options([
                            '1' => 'belum diperiksa',
                            '2' => 'valid',
                            '3' => 'tidak valid',
                        ]),

                    DateTimePicker::make('check_in')
                        ->required()
                        ->native(false),

                    DateTimePicker::make('check_out')
                        ->nullable()
                        ->native(false),

                    Select::make('created_by_id')
                        ->label('For')
                        ->nullable()
                        ->relationship('createdBy', 'name')
                        ->searchable()
                        ->disabled()
                        ->preload()
                        ->native(false),

                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $presence = Presence::query();

        if (!Auth::user()->hasRole('admin')) {
            $presence->where('created_by_id', Auth::id());
        }
        return $table
            ->query($presence)
            ->poll('60s')
            ->columns([

                ImageOpenUrlColumn::make('image_in')
                    ->visibility('public')
                    ->url(function ($record) {
                        $apiUrl = 'https://api.sagansa.id';  // URL API
                        return str_replace(
                            config('app.url'),
                            $apiUrl,
                            asset('storage/' . $record->image_in)
                        );
                    }),

                ImageOpenUrlColumn::make('image_out')
                    ->visibility('public')
                    ->url(function ($record) {
                        $apiUrl = 'https://api.sagansa.id';  // URL API
                        return str_replace(
                            config('app.url'),
                            $apiUrl,
                            asset('storage/' . $record->image_out)
                        );
                    }),

                TextColumn::make('createdBy.name')
                    ->sortable(),

                TextColumn::make('store.nickname')
                    ->sortable(),

                TextColumn::make('shiftStore.name'),

                TextColumn::make('status')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'belum diperiksa',
                            '2' => 'valid',
                            '3' => 'tidak valid',

                            default => $state,
                        }
                    )
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            '1' => 'warning',
                            '2' => 'success',
                            '3' => 'danger',
                            default => $state,
                        }
                    ),

                TextColumn::make('check_in'),

                TextColumn::make('check_out'),
            ])
            ->filters([
                SelectFilter::make('created_by_id')
                    ->label('Created By')
                    ->searchable()
                    ->relationship('createdBy', 'name'),
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
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPresences::route('/'),
            'create' => Pages\CreatePresence::route('/create'),
            'view' => Pages\ViewPresence::route('/{record}'),
            'edit' => Pages\EditPresence::route('/{record}/edit'),
        ];
    }
}
