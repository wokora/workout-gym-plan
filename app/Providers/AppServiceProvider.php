<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Blade::directive('vite', function ($expression) {
            return "<?php echo \App\Support\Vite::assets({$expression}); ?>";
        });

        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'food' => \App\Models\Food\Food::class,
            'workout' => \App\Models\Workout\Workout::class,
            'exercise' => \App\Models\Exercise\Exercise::class,
            'disease' => \App\Models\Disease\Disease::class,
        ]);
    }
}
