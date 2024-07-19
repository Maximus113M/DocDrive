<?php

namespace App\Providers;

use App\Models\Project;
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
        //
    }

    /**
     * Verifica si un proyecto está incompleto
     */
    static public function projectIsIncomplete(Project $project) : bool
    {
        return !isset($project->startDate, $project->endDate, $project->target, $project->description);
    }

}
