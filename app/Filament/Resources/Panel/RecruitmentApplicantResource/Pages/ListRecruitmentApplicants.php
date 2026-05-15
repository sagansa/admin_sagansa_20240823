<?php

namespace App\Filament\Resources\Panel\RecruitmentApplicantResource\Pages;

use App\Filament\Resources\Panel\RecruitmentApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecruitmentApplicants extends ListRecords
{
    protected static string $resource = RecruitmentApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
