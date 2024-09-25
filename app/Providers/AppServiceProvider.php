<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FileUpload::configureUsing(function (FileUpload $fileUpload) {
        //     $fileUpload->hiddenLabel();
        // });

        // TextInput::configureUsing(function (TextInput $textInput) {
        //     $textInput->inlineLabel();
        // });

        Select::configureUsing(function (Select $select) {
            $select->native(false);
        });

        // Radio::configureUsing(function (Radio $radio) {
        //     $radio->inlineLabel();
        // });

        DatePicker::configureUsing(function(DatePicker $datePicker) {
            $datePicker->native(false);
        });

        // Section::configureUsing(function(Section $section) {
        //     $section->columns()->compact();
        // });
    }
}
