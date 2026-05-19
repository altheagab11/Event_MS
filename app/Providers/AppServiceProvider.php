<?php

namespace App\Providers;

use App\Models\Event;
use App\Services\EventSessionSyncService;
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
        Event::saved(function (Event $event): void {
            app(EventSessionSyncService::class)->syncForEvent($event);
        });
    }
}
