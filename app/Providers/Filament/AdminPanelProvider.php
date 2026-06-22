<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\MyCalenderWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName('APLIKASI MONITORING HARGA KOMODITAS DAN MANAJEMEN KEGIATAN BERBASIS WEB PADA DKUMPP KABUPATEN BANJAR')
            // ->brandLogo("https://www.banjarkab.go.id/assets/images/logo.png")
            ->favicon("https://www.banjarkab.go.id/assets/images/logo.png")
            ->sidebarFullyCollapsibleOnDesktop()
            ->spa()
            //  ->topNavigation()
            ->colors([
                'primary' => Color::Teal,       // Lebih elegan dan profesional
                'info' => Color::Blue,     // Memberikan kesan tegas dan resmi
                'success' => Color::Green,      // Warna yang melambangkan keberhasilan dan positif
                'warning' => Color::Amber,      // Warna yang lebih lembut dan masih memberikan peringatan
                'danger' => Color::Red,         // Klasik untuk peringatan yang lebih serius
                'gray' => Color::Zinc,     // Warna netral yang cocok untuk latar belakang atau teks
            ])

            ->brandLogoHeight('3rem')


        // Atau cara termudah dengan Custom CSS
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->plugin(\Guava\Calendar\CalendarPlugin::make())
            //   ->spa(hasPrefetching: true)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
                MyCalenderWidget::class,
            ])
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
            ]);
    }
}
