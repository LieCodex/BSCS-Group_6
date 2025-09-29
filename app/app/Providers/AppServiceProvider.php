<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Make $notifications available in all views for logged-in users
        View::composer('*', function ($view) {
            if(auth()->check()){
                $view->with('notifications', Notification::where('user_id', auth()->id())
                                                        ->orderBy('created_at', 'desc')
                                                        ->get());
            }
        });
    }
}
