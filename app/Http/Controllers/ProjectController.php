<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Validity;
use App\Models\User;
use App\Models\VisualizationRole;
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
            'investigatorsID' => 'required|array',
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
        $project->description = request("description");
        
        if(request("startDate") || request("endDate")){
            $validity= Validity::where('id', request("validityID"))->first();
            $startDate = "{$validity->year}-01-01";
            $endDate = "{$validity->year}-12-31";
            // error_log($startDate);
            // error_log($endDate);
            $validator = Validator::make(request()->all(), [
                'startDate' => "required|date|before_or_equal:$endDate|after_or_equal:$startDate",
                'endDate' => "required|date|after_or_equal:startDate|before_or_equal:$endDate",
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $project->startDate= request("startDate");
            $project->endDate= request("endDate");
        }
            
        $project->save();

        $project->users()->attach(request("investigatorsID"));


        return redirect()->back()->with("message", "Proyecto creado.");
    }

    private function validateYear($validity, $originalDate){
        $date = explode("-", $originalDate)[0];

        if(!$validity || ($validity->year !==  $date)){
            return false;
        }
        return true;
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
        $year= $project->validity->first()->year;
        $startDate = "{$year}-01-01";
        $endDate = "{$year}-12-31";


        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'startDate' => "required|date|before_or_equal:$endDate|after_or_equal:$startDate",
            'endDate' => "required|date|after_or_equal:startDate|before_or_equal:$endDate",
            'visualizationRoleSelected' => 'required|numeric',
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
        $project->visualization_role_id = request("visualizationRoleSelected");
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
        $role = AuthServiceProvider::getRole();

        if (!in_array(Auth::user()->id, $usersID) && $role != RoleServiceProvider::ADMIN) {
            abort(403, "No tienes persmisos para estar aqui");
        }
        if ($project->folders->count() > 0 || $project->documents->count() > 0) {
            return redirect()->back()->with("errorMessage", "¡No se puede eliminar un proyecto con documentos o carpetas asociados!");
        }
        // TODO: TECNICAMENTE SI PODRIA ELIMINAR SI TIENE USUARIOS, SE DEBE ELIMINAR AL ASOCIACION DE PROJECTS-USERS
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
        $project = Project::with('users')->find($projectID);
        $userRole = AuthServiceProvider::getRole();
        if ($userRole == RoleServiceProvider::INVESTIGATOR || $userRole == RoleServiceProvider::COLLABORATOR) {
            $usersID = $project->users->pluck('id')->toArray();
            $filteredDocuments = $project->documents->filter(function ($document) use ($usersID) {
                $visualizationRole = $document->visualizationRole()->first()->name;
                return $this->conditionCollaboratorInvestigator($visualizationRole, $usersID);
            });
            $filteredFolders = $project->folders->filter(function ($folder) use ($usersID) {
                $visualizationRole = $folder->visualizationRole()->first()->name;
                return $this->conditionCollaboratorInvestigator($visualizationRole, $usersID);
            });
            $project->setRelation('documents', $filteredDocuments);
            $project->setRelation('folders', $filteredFolders);
        } elseif ($userRole == RoleServiceProvider::GUEST) {
            $filteredDocuments = $project->documents->filter(function ($document) {
                $visualizationRole = $document->visualizationRole()->first()->name;
                return $this->conditionGuest($visualizationRole);
            });
            $filteredFolders = $project->folders->filter(function ($folder) {
                $visualizationRole = $folder->visualizationRole()->first()->name;
                return $this->conditionGuest($visualizationRole);
            });
            $project->setRelation('documents', $filteredDocuments);
            $project->setRelation('folders', $filteredFolders);
        } else {
            $project->documents;
            $project->folders;
        }
        return Inertia::render("Projects/Project/Index", [
            // "folders" => $project->folders,
            // "documents" => $project->documents,
            "project" => $project,
            "currentYear" => $validityYear,
            "visualizationsRole" => VisualizationRole::all(),
            "investigators" => InvestigatorController::getInvestigators(),
            "collaborators" => CollaboratorController::getCollaborators()
        ]);
    }

    private function conditionCollaboratorInvestigator($visualizationRole, $usersID): bool
    {
        return $visualizationRole == RoleServiceProvider::PUBLIC || $visualizationRole ==
            RoleServiceProvider::GENERAL_PUBLIC || ($visualizationRole == RoleServiceProvider::PRIVATE
                && in_array(Auth::user()->id, $usersID));
    }

    private function conditionGuest($visualizationRole): bool
    {
        return $visualizationRole == RoleServiceProvider::GENERAL_PUBLIC; 
    }


    /**
     * Asociar usuarios a un proyecto
     */
    public function associatedUsers($projectID)
    {
        $project = Project::find($projectID);
        if (!$project) {
            abort(403, "No tienes persmisos para estar aqui");
        }
        $usersID =  $project->users->pluck('id')->toArray();
        $roleAuth = AuthServiceProvider::getRole();
        if (!in_array(Auth::user()->id, $usersID) && $roleAuth != RoleServiceProvider::ADMIN) {
            abort(403, "No tienes persmisos para estar aqui");
        }
        $validator = Validator::make(request()->all(), [
            'role' => 'required',
            'usersID' =>'',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = request("role");

        $this->disassociatedUsers($project, $role, request("usersID"));        

        foreach (request("usersID") as $userID) {
            if (!in_array($userID, $usersID)) {
                $project->users()->attach($userID);
            }
        }


        return redirect()->back()->with("message", "Usuarios asociados/desasociados.");
    }


    /**
     * 
     * Desasociar usuarios
     */
    public function disassociatedUsers($project, $role, $usersID)
    {
        if (RoleServiceProvider::INVESTIGATOR == $role) {
            $investigators = User::whereHas('role', function($query){
                $query->where('id', RoleServiceProvider::INVESTIGATOR_ID);
            })->pluck("id");
            $usersIDToRemove = $this->removeDuplicates($usersID, $investigators);
            $project->users()->detach($usersIDToRemove);
        } else if (RoleServiceProvider::COLLABORATOR == $role) {
            $collaborators = User::whereHas('role', function($query){
                $query->where('id', RoleServiceProvider::COLLABORATOR_ID);
            })->pluck("id");
            $usersIDToRemove = $this->removeDuplicates($usersID, $collaborators);
            $project->users()->detach($usersIDToRemove);
        }
    }

    /**
     * Remover los ids repetidos de los usuarios (investigadores o colaboradores)
     */
    private function removeDuplicates($usersID, $roleUsersID)
    {
        return array_filter($roleUsersID->toArray(), function ($roleUserID) use ($usersID) {
            return !in_array($roleUserID, $usersID);
        });
    }

}
