<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 公開パスを ~/web/kaitorikun に固定する設定
    $this->app->bind('path.public', function() {
        return base_path('../kaitorikun');
    });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
