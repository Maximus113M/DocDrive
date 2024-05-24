<?php

namespace App\Http\Controllers;

use App\Models\Validity;
use App\Models\VisualizationRole;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use App\Rules\SingleYearValidity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ValidityController extends Controller
{
    //

    /**
     * 
     * Muestra las vigencias en su vista.
     */
    public function index()
    {
        return Inertia::render('Validity/Index', [
            "validities" => Validity::all(),
            "role" => AuthServiceProvider::getRole(),
            "isAuthenticated" => AuthServiceProvider::checkAuthenticated(),
        ]);
    }

    /**
     * 
     * Crea una nueva Vigencia
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'year' => ['required', 'numeric', new SingleYearValidity()],
        ]);

        if ($validator->fails()) {
            return redirect()->route("validity.index")->withErrors($validator)->withInput();
        }

        Validity::create([
            "year" => request("year"),
        ]);

        return redirect()->route("validity.index")->with("message", "¡La vigencia se ha creado correctamente!");
    }

    /**
     * 
     * Actualiza la vigencia
     */
    public function update($validityID)
    {
        $this->validate(request(), [
            "year" => "required",
        ]);

        $validity = Validity::find($validityID);

        $validity->update([
            "year" => request("year"),
        ]);
        
        return redirect()->route("validity.index")->with("message", "¡La vigencia se ha actualizado correctamente!");
    }

    /**
     * 
     * Muestra los proyectos de una Vigencia segun el rol de visualización y el rol
     * del usuario
     */
    public function projects($validityYear)
    {
        if(!Validity::where('year', $validityYear)->exists()) {
            return Inertia::render("PageNotFound");
        }
        $userRole = AuthServiceProvider::getRole();
        $projects = Validity::where('year', $validityYear)->first()->projects;
        $data = array();
        if ($userRole == RoleServiceProvider::INVESTIGATOR || $userRole == RoleServiceProvider::COLLABORATOR) {
            $data = $projects->filter(function ($project) {
                $usersID = $project->users->pluck('id')->toArray();
                $visualizationRole = $project->visualizationRole()->first()->name;
                return $visualizationRole == RoleServiceProvider::PUBLIC || $visualizationRole == 
                    RoleServiceProvider::GENERAL_PUBLIC || ($visualizationRole == RoleServiceProvider::PRIVATE
                    && in_array(Auth::user()->id, $usersID)); 
            });
        } else if ($userRole == RoleServiceProvider::GUEST) {
            $data = $projects->filter(function ($project) {
                $visualizationRole = $project->visualizationRole()->first()->name;
                return $visualizationRole == RoleServiceProvider::GENERAL_PUBLIC; 
            });
        } else {
            $data = $projects;
        }

        return Inertia::render("Projects/Index", [
            "projects" => $data,
            "role" => AuthServiceProvider::getRole(),
            "isAuthenticated" => AuthServiceProvider::checkAuthenticated()
        ]);
    }
}