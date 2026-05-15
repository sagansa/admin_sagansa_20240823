<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use App\Filament\Resources\Panel\RecruitmentApplicantResource\Pages;
use App\Models\ApplicantDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecruitmentApplicantResource extends Resource
{
    protected static ?string $model = ApplicantDetail::class;

    protected static ?string $cluster = HRD::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Full Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('user.email')
                            ->label('Email')
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->disabled(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Personal Details')
                    ->schema([
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->disabled(),
                        Forms\Components\TextInput::make('gender')
                            ->disabled(),
                        Forms\Components\TextInput::make('birth_place')
                            ->disabled(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->disabled(),
                        Forms\Components\TextInput::make('religion')
                            ->disabled(),
                        Forms\Components\TextInput::make('marital_status')
                            ->disabled(),
                        Forms\Components\TextInput::make('education_level')
                            ->disabled(),
                        Forms\Components\TextInput::make('education_major')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Address & Family')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('father_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('mother_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('emergency_name')
                            ->label('Emergency Contact Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('emergency_phone')
                            ->label('Emergency Contact Phone')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('education_level')
                    ->label('Education'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'warning',
                        'reviewed' => 'info',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'reviewed' => 'Reviewed',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Applicant Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Full Name'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone Number'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'submitted' => 'warning',
                                'reviewed' => 'info',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),
                    ])->columns(2),

                Infolists\Components\Section::make('Personal Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('nik')
                            ->label('NIK'),
                        Infolists\Components\TextEntry::make('gender'),
                        Infolists\Components\TextEntry::make('birth_place'),
                        Infolists\Components\TextEntry::make('birth_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('religion'),
                        Infolists\Components\TextEntry::make('marital_status'),
                        Infolists\Components\TextEntry::make('education_level'),
                        Infolists\Components\TextEntry::make('education_major'),
                    ])->columns(2),

                Infolists\Components\Section::make('Work Experiences')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('user.workExperiences')
                            ->schema([
                                Infolists\Components\TextEntry::make('company_name'),
                                Infolists\Components\TextEntry::make('position'),
                                Infolists\Components\TextEntry::make('start_date')
                                    ->date(),
                                Infolists\Components\TextEntry::make('end_date')
                                    ->date(),
                                Infolists\Components\TextEntry::make('description')
                                    ->columnSpanFull(),
                            ])->columns(2)
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecruitmentApplicants::route('/'),
            'view' => Pages\ViewRecruitmentApplicant::route('/{record}'),
        ];
    }
}
