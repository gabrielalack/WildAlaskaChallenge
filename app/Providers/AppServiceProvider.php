<?php

namespace App\Providers;

use App\Services\Recipes\RecipeSearchInterface;
use App\Services\Recipes\RecipeDatabaseSearchService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the Recipe Database Search Service
        $this->app->bind(RecipeSearchInterface::class, RecipeDatabaseSearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
