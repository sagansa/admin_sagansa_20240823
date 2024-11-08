<?php

namespace App\Providers\Filament;

use App\Models\Employee;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Filament\Pages\Auth\Register;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Access')
                    ->icon('heroicon-o-shield-check'),
                NavigationGroup::make()
                    ->label('Transaction')
                    ->icon('heroicon-o-shopping-cart'),
                NavigationGroup::make()
                    ->label('Asset')
                    ->icon('heroicon-o-globe-alt'),
                NavigationGroup::make()
                    ->label('HRD')
                    ->icon('heroicon-o-user-group'),
                NavigationGroup::make()
                    ->label('Cash')
                    ->icon('heroicon-o-banknotes'),
                NavigationGroup::make()
                    ->label('Stock')
                    ->icon('heroicon-o-document-chart-bar'),
            ])
            ->default()
            ->topNavigation()
            ->id('admin')
            ->path('admin')
            ->login()
            ->PasswordReset()
            ->registration()
            ->emailVerification()
            // ->profile()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
            ])
            ->plugins([
                FilamentShieldPlugin::make('super_admin'),
                FilamentEditProfilePlugin::make()
                    // ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    // ->setNavigationGroup('Group Profile')
                    ->setIcon('heroicon-o-user')
                    // ->setSort(10)
                    // ->canAccess(fn () => auth()->user()->id === 1)
                    // ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false)
                // ->shouldShowSanctumTokens()
                // ->shouldShowBrowserSessionsForm()
                // ->shouldShowAvatarForm()
                // ->customProfileComponents([
                //     Employee::class,
                // ]),
            ]);
    }
}
