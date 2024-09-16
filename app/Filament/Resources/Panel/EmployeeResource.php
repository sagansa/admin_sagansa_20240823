<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use App\Filament\Forms\ImageInput;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Employee;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\Panel\EmployeeResource\Pages;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = HRD::class;

    protected static ?string $navigationGroup = 'Personal Data';

    public static function getModelLabel(): string
    {
        return __('crud.employees.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.employees.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.employees.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    TextInput::make('identity_no')
                        ->required()
                        ->string()
                        ->autofocus(),

                    TextInput::make('nickname')
                        ->required()
                        ->string(),

                    TextInput::make('no_telp')
                        ->required()
                        ->string(),

                    TextInput::make('birth_place')
                        ->required()
                        ->string(),

                    DatePicker::make('birth_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('fathers_name')
                        ->required()
                        ->string(),

                    TextInput::make('mothers_name')
                        ->required()
                        ->string(),

                    RichEditor::make('address')
                        ->required()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    Select::make('province_id')
                        ->required()
                        ->relationship('province', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('city_id')
                        ->required()
                        ->relationship('city', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('district_id')
                        ->required()
                        ->relationship('district', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('subdistrict_id')
                        ->required()
                        ->relationship('subdistrict', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('postal_code_id')
                        ->required()
                        ->relationship('postalCode', 'id')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('parents_no_telp')
                        ->required()
                        ->string(),

                    TextInput::make('siblings_name')
                        ->required()
                        ->string(),

                    TextInput::make('siblings_no_telp')
                        ->required()
                        ->string(),

                    Checkbox::make('bpjs')
                        ->rules(['boolean'])
                        ->required()
                        ->inline(),

                    TextInput::make('bank_account_no')
                        ->nullable()
                        ->string(),

                    DatePicker::make('acceptance_date')
                        ->rules(['date'])
                        ->nullable()
                        ->native(false),

                    TextInput::make('signs')
                        ->nullable()
                        ->string(),

                    RichEditor::make('notes')
                        ->required()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('bank_id')
                        ->required()
                        ->relationship('bank', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('employee_status_id')
                        ->required()
                        ->relationship('employeeStatus', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('gender')
                        ->required()
                        ->in([])
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('major')
                        ->nullable()
                        ->string(),

                    TextInput::make('latitude')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('longitude')
                        ->required()
                        ->numeric()
                        ->step(1),

                    ImageInput::make('image_identity_id')
                        ->disk('public')
                        ->directory('images/Employee'),

                    ImageInput::make('image_selfie')
                        ->disk('public')
                        ->directory('images/Employee'),

                    Select::make('religion')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('driving_license')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('marital_status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('level_of_education')
                        ->required()
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
                TextColumn::make('identity_no'),

                TextColumn::make('nickname'),

                TextColumn::make('no_telp'),

                TextColumn::make('birth_place'),

                TextColumn::make('birth_date')->since(),

                TextColumn::make('fathers_name'),

                TextColumn::make('mothers_name'),

                TextColumn::make('address')->limit(255),

                TextColumn::make('province.name'),

                TextColumn::make('city.name'),

                TextColumn::make('district.name'),

                TextColumn::make('subdistrict.name'),

                TextColumn::make('postalCode.id'),

                TextColumn::make('parents_no_telp'),

                TextColumn::make('siblings_name'),

                TextColumn::make('siblings_no_telp'),

                CheckboxColumn::make('bpjs'),

                TextColumn::make('bank_account_no'),

                TextColumn::make('acceptance_date')->since(),

                TextColumn::make('signs'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('user.name'),

                TextColumn::make('bank.name'),

                TextColumn::make('employeeStatus.name'),

                TextColumn::make('gender'),

                TextColumn::make('major'),

                TextColumn::make('latitude'),

                TextColumn::make('longitude'),

                ImageColumn::make('image_identity_id')->visibility('public'),

                ImageColumn::make('image_selfie')->visibility('public'),

                TextColumn::make('religion'),

                TextColumn::make('driving_license'),

                TextColumn::make('marital_status'),

                TextColumn::make('level_of_education'),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
