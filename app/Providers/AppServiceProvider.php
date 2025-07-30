<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\View;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Auth;
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
        Paginator::useBootstrap();

        // survey notification
        View::composer('*', function ($view) {
        $newsurvey_count = Auth::check()
            ? CustomHelper::SurveyNotification(Auth::id())
            : 0;

            $view->with('newsurvey_count', $newsurvey_count);
        });

        // par notification
        View::composer('*', function ($view) {
        $newpar_count = Auth::check()
            ? CustomHelper::ParNotification(Auth::id())
            : 0;

            $view->with('newpar_count', $newpar_count);
        });

        // sar notification
         View::composer('*', function ($view) {
        $newsar_count = Auth::check()
            ? CustomHelper::SarNotification(Auth::id())
            : 0;

            $view->with('newsar_count', $newsar_count);
        });

        // feedback notification
        View::composer('*', function ($view) {
        $newfeedback_count = Auth::check()
            ? CustomHelper::FeedbackNotification(Auth::id())
            : 0;

            $view->with('newfeedback_count', $newfeedback_count);
        });

        // company policy notification
        View::composer('*', function ($view) {
        $newcompany_policy_count = Auth::check()
            ? CustomHelper::PolicyNotification(Auth::id())
            : 0;

            $view->with('newfeedback_count', $newcompany_policy_count);
        });

    }
}
