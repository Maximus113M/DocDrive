<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use App\Rules\ValidityEndYearProject;
use App\Rules\ValidityYearProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProjectController extends Controller
{
    
    /**
     * Crear un nuevo projecto
     * 
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'investigatorsID' => 'required',
            'validityID' => 'required|numeric',
            'visualizationRoleSelected' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $project = new Project();
        $project->name = request("name");
        $project->validity_id = request("validityID");
        $project->visualization_role_id = request("visualizationRoleSelected");
        $project->save();

        $project->users()->attach(request("investigatorsID"));


        return redirect()->back()->with("message", "Proyecto creado.");
    }

    /**
     @TODO: FALTA IMPLEMENTAR EL CAMPO TARGET DE PROJECT
     * 
     * Actualizar un projecto
     */
    public function update($projectID)
    {
        $project = Project::find($projectID);
        $usersID =  $project->users->pluck('id')->toArray();
        $role = AuthServiceProvider::getRole();
        if (!in_array(Auth::user()->id, $usersID) && $role != RoleServiceProvider::ADMIN) {
            abort(403, "No tienes persmisos para estar aqui");
        }
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'startDate' => ['required', new ValidityYearProject($project->validity->first()->year)],
            'endDate' => ['required', new ValidityEndYearProject(request("startDate"))],
            //'target' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $project->name = request("name");
        $project->description = request("description");
        $project->startDate = request("startDate");
        $project->endDate = request("endDate");
        $project->target = request("target");
        $project->update();

        return redirect()->back()->with("message", "Proyecto actualizado.");
    }

    /**
     * Eliminar un proyecto
     */
    public function destroy($projectID)
    {
        $project = Project::find($projectID);
        $usersID =  $project->users->pluck('id')->toArray();
        if (!in_array(Auth::user()->id, $usersID)) {
            abort(403, "No tienes persmisos para estar aqui");
        }
        if ($project->folders->count() > 0 || $project->documents->count() > 0) {
            return redirect()->back()->with("errorMessage", "¡No se puede eliminar un proyecto con documentos o carpetas asociados!");
        }
        if ($project->users->count() > 0) {
            return redirect()->back()->with("errorMessage", "¡No se puede eliminar un proyecto con usuarios asociados!");
        }

        $project->delete();

        return redirect()->back()->with("message", "Proyecto eliminado.");
    }

    /**
     * 
     * MUESTRA LOS DOCUMENTOS Y CARPETAS QUE TIENE UN PROEYCTO
     */
    public function index($validityYear, $projectID)
    {
        $project = Project::find($projectID);
        return Inertia::render("Projects/Project/Index", [
            "folders" => $project->folders,
            "documents" => $project->documents
        ]);
    }

}
