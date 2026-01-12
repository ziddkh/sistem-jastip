<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Native\Desktop\Facades\Window;
use Native\Desktop\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        // Run migrations for the NativePHP SQLite database
        Artisan::call('migrate', [
            '--force' => true,
        ]);

        // Seed the database if tables are empty
        // Only run seeder if no users exist (first run)
        if (\App\Models\User::count() === 0) {
            Artisan::call('db:seed', [
                '--force' => true,
            ]);
        }

        Window::open()
            ->title('Sistem Jastip')
            ->maximized()
            ->hideDevTools();
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
