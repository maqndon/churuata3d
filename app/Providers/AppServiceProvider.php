<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TagRepositoryEloquent;
use App\Repositories\TagRepositoryInterface;
use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\CategoryRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepositoryEloquent::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepositoryEloquent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
