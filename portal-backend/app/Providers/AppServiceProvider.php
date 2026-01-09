<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Gallery;
use App\Models\User;
use App\Models\ActivityLog;

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
        View::composer('partials.sidebar', function ($view) {
            $trashedCount = 0;
            $models = [
                Article::class, 
                Category::class, 
                Tag::class, 
                Gallery::class, 
                User::class, 
                ActivityLog::class
            ];
            
            foreach ($models as $model) {
                if (method_exists($model, 'onlyTrashed')) {
                    $trashedCount += $model::onlyTrashed()->count();
                }
            }
            
            $view->with('trashedCount', $trashedCount);
        });
    }
}
