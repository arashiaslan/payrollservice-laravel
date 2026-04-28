<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
     * Di sini kita mendaftarkan Gate (aturan otorisasi) secara native Laravel.
     */
    public function boot(): void
    {
        // Gate 'admin': hanya user dengan role 'admin' yang diizinkan.
        // Dipakai di route: middleware('can:admin')
        // Dipakai di Blade: @can('admin') ... @endcan
        Gate::define("admin", function ($user) {
            // Kembalikan true jika role user adalah 'admin'
            return $user->role === "admin";
        });
    }
}
