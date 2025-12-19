<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

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
    public function boot()
    {
        // Aplica a todas as views ('*')
        View::composer('*', function ($view) {
            // Se o user estiver autenticado
            $unreadCount = Auth::check()
                ? Notification::where('receiver', Auth::id())
                              ->where('is_read', false)
                              ->count()
                : 0;
    
            // Disponibiliza a variável para todas as views
            $view->with('unreadCount', $unreadCount);
        });
    }
}
