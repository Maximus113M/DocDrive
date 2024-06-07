<?php

namespace App\Http\Middleware;

use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProtectProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $projectID = $request->route("projectID");
        $validityYear = $request->route("validityYear");
        $validity = \App\Models\Validity::where("year", $validityYear)->first();
        $project = \App\Models\Project::find($projectID);
        if (!$validity) {
            abort(404, "Vigencia no existe");
        }
        if (!$project || !in_array($projectID, $validity->projects->pluck('id')->toArray())) {
            abort(404, "Proyecto no existe");
        }
        $userRole = AuthServiceProvider::getRole();
        $visualizationRole = $project->visualizationRole()->first()->name;
        $usersID = $project->users->pluck('id')->toArray();
        if ($userRole == RoleServiceProvider::GUEST && $visualizationRole != 
        RoleServiceProvider::GENERAL_PUBLIC) {
            abort(403, "No tienes persmisos para estar aqui");
        } elseif ( ($userRole == RoleServiceProvider::COLLABORATOR || $userRole ==
        RoleServiceProvider::INVESTIGATOR) && ($visualizationRole == 
        RoleServiceProvider::PRIVATE &&  !in_array(Auth::user()->id, $usersID)) ) {
            abort(403, "No tienes persmisos para estar aqui");
        }

        return $next($request);
    }
}
