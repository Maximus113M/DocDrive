<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     * Actualizar un projecto
     */
    public function update($projectID)
    {
        $project = Project::find($projectID);
        $usersID =  $project->users->pluck('id')->toArray();
        if (!in_array(Auth::user()->id, $usersID)) {
            abort(403, "No tienes persmisos para estar aqui");
        }
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'startDate ' => 'required',
            'endDate' => 'required',
            'target' => 'required'
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
        $project->delete();

        return redirect()->back()->with("message", "Proyecto eliminado.");
    }

}
