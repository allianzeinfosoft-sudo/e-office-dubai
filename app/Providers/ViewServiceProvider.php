<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use App\Models\BackgroundImage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!Schema::hasTable('background_images')) {
            return;
        }

        $bgImage = BackgroundImage::with('appearance')
                        ->where('background_type', 'all')
                        ->first();
        $login_bgImage = BackgroundImage::with('appearance')
                        ->where('background_type', 'login')
                        ->first();

        $feeds_bgImage = BackgroundImage::with('appearance')
                        ->where('background_type', 'feeds')
                        ->first();


        View::share([
                        'background_image' => $bgImage?->appearance?->image ?? '',
                        'login_background_image' => $login_bgImage?->appearance?->image ?? '',
                        'feeds_background_image' => $feeds_bgImage?->appearance?->image ?? '',
                    ]);

    }
}
