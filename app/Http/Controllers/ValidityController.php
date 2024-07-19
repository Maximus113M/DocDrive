<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Folder;
use App\Models\Project;
use App\Models\Validity;
use App\Models\VisualizationRole;
use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use App\Rules\SingleYearValidity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            "sharedFolders" => Folder::where([
                ["father_id", "=", null],
                ["project_id", "=", null],
            ])->get(),
            "role" => AuthServiceProvider::getRole(),
            "isAuthenticated" => AuthServiceProvider::checkAuthenticated(),
            "categories" => DocumentCategory::all()
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

        if (count($validity->projects) > 0) {
            return redirect()->route("validity.index")->with("errorMessage", "No se puede actualizar una vigencia con proyectos asociados!");
        }

        $validity->update([
            "year" => request("year"),
        ]);

        return redirect()->route("validity.index")->with("message", "¡La vigencia se ha actualizado correctamente!");
    }

    /**
     * 
     * ELimina una vigencia
     */

    public function destroy($validityID)
    {
        $validity = Validity::find($validityID);

        if ($validity->projects->count() > 0) {
            return redirect()->route("validity.index")->with("errorMessage", "¡No se puede eliminar una vigencia con proyectos asociados!");
        }

        $validity->delete();

        return redirect()->route("validity.index")->with("message", "¡La vigencia se ha eliminado correctamente!");
    }


    /**
     * 
     * Muestra los proyectos de una Vigencia segun el rol de visualización y el rol
     * del usuario
     */
    public function projects($validityYear)
    {
        if (!Validity::where('year', $validityYear)->exists()) {
            return Inertia::render("PageNotFound");
        }
        $validity = Validity::where('year', $validityYear)->first();
        $userRole = AuthServiceProvider::getRole();
        $projects = $validity->projects;
        $data = array();
        if ($userRole == RoleServiceProvider::INVESTIGATOR || $userRole == RoleServiceProvider::COLLABORATOR) {
            foreach ($projects as $project) {
                $usersID = $project->users->pluck('id')->toArray();
                $visualizationRole = $project->visualizationRole()->first()->name;
                if (
                    $visualizationRole == RoleServiceProvider::PUBLIC || $visualizationRole ==
                    RoleServiceProvider::GENERAL_PUBLIC || ($visualizationRole == RoleServiceProvider::PRIVATE
                        && in_array(Auth::user()->id, $usersID))
                ) {
                    $projectArr = $project->toArray();
                    $projectArr["isIncomplete"] = AppServiceProvider::projectIsIncomplete($project);
                    array_push($data, $projectArr);
                }
            }
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
            "investigators" => InvestigatorController::getInvestigators(),
            "validityID" => $validity->id,
            "visualizationsRole" => VisualizationRole::all(),
            "currentYear" => $validityYear,
        ]);
    }

    /**
     * Realiza la consulta del filtro de búsqueda
     */
    public function search($consulta)
    {
        $validities = Validity::where("year", "like", "%{$consulta}%")->get();
        $sharedResources = Folder::whereNull("father_id")
            ->whereNull("project_id")
            ->where("name", "like", "%{$consulta}%")
            ->get();

        $projects = null;
        $documents = null;
        $folders = null;
        $userRole = AuthServiceProvider::getRole();
        $documentsForCategory = null;

        if ($userRole == RoleServiceProvider::GUEST) {
            $projects = Project::whereHas("visualizationRole", function ($query) {
                $query->where('name', '=', RoleServiceProvider::GENERAL_PUBLIC);
            })->where("name", "like", "%{$consulta}%")->get();

            $documents = Document::whereHas("visualizationRole", function ($query) {
                $query->where('name', '=', RoleServiceProvider::GENERAL_PUBLIC);
            })->where("name", "like", "%{$consulta}%")->with(['project', 'folder.project'])->get();

            $folders = Folder::whereHas("visualizationRole", function ($query) {
                $query->where('name', RoleServiceProvider::GENERAL_PUBLIC);
            })->where(function ($query) {
                $query->whereNotNull('father_id')
                    ->orWhereNotNull('project_id');
            })->where("name", "like", "%{$consulta}%")->with('project')->get();

            $documentsForCategory = Document::whereHas("visualizationRole", function ($query) {
                $query->where('name', RoleServiceProvider::GENERAL_PUBLIC);
            })->whereHas('categories', function ($query) use ($consulta) {
                $query->where('name', "like", "%{$consulta}%");
            })->with(['project', 'folder.project'])->get();
        } else if ($userRole == RoleServiceProvider::INVESTIGATOR || $userRole == RoleServiceProvider::COLLABORATOR) {
            $projects = DB::table('projects as p')
                ->select('p.*')
                ->join('project_user as pu', 'p.id', '=', 'pu.project_id')
                ->join('users as u', 'u.id', '=', 'pu.user_id')
                ->where(function ($query) {
                    $query->where('p.visualization_role_id', RoleServiceProvider::PRIVATE_ID)
                        ->where('u.id', Auth::user()->id);
                })
                ->orWhere(function ($query) {
                    $query->where('p.visualization_role_id', RoleServiceProvider::GENERAL_PUBLIC_ID)
                        ->orWhere('p.visualization_role_id', RoleServiceProvider::PUBLIC_ID);
                })
                ->where("p.name", "like", "%{$consulta}%")
                ->distinct()
                ->get();

            $documents1 = Document::select('documents.*')
                ->join('document_folder', 'document_folder.document_id', '=', 'documents.id')
                ->join('folders', 'folders.id', '=', 'document_folder.folder_id')
                ->join('projects', 'projects.id', '=', 'folders.project_id')
                ->join('project_user', 'projects.id', '=', 'project_user.project_id')
                ->join('users', 'users.id', '=', 'project_user.user_id')
                ->where("documents.name", "like", "%{$consulta}%")
                ->where(function ($query) {
                    $query->where('documents.visualization_role_id', RoleServiceProvider::PRIVATE_ID)
                        ->where('users.id', Auth::user()->id)
                        ->orWhere(function ($query) {
                            $query->where('documents.visualization_role_id', RoleServiceProvider::GENERAL_PUBLIC_ID)
                                ->orWhere('documents.visualization_role_id', RoleServiceProvider::PUBLIC_ID);
                        });
                })
                ->distinct()
                ->with(['folder', 'folder.project'])
                ->get();

            $documents2 = Document::select('documents.*')
                ->join('projects', 'projects.id', '=', 'documents.project_id')
                ->join('project_user', 'projects.id', '=', 'project_user.project_id')
                ->join('users', 'users.id', '=', 'project_user.user_id')
                ->where("documents.name", "like", "%{$consulta}%")
                ->where(function ($query) {
                    $query->where('documents.visualization_role_id', RoleServiceProvider::PRIVATE_ID)
                        ->where('users.id', Auth::user()->id)
                        ->orWhere(function ($query) {
                            $query->where('documents.visualization_role_id', RoleServiceProvider::GENERAL_PUBLIC_ID)
                                ->orWhere('documents.visualization_role_id', RoleServiceProvider::PUBLIC_ID);
                        });
                })
                ->distinct()
                ->with(['project'])
                ->get();

            $documents = $documents1->toBase()->merge($documents2);


            $documentsForCategory1 = Document::select('documents.*')
                ->join('document_folder', 'document_folder.document_id', '=', 'documents.id')
                ->join('folders', 'folders.id', '=', 'document_folder.folder_id')
                ->join('projects', 'projects.id', '=', 'folders.project_id')
                ->join('project_user', 'projects.id', '=', 'project_user.project_id')
                ->join('users', 'users.id', '=', 'project_user.user_id')
                ->join('document_categories_documents', 'document_categories_documents.document_id', '=', 'documents.id')
                ->join('document_categories', 'document_categories.id', '=', 'document_categories_documents.category_id')
                ->where("document_categories.name", "like", "%{$consulta}%")
                ->where(function ($query) {
                    $query->where('documents.visualization_role_id', RoleServiceProvider::PRIVATE_ID)
                        ->where('users.id', Auth::user()->id)
                        ->orWhere(function ($query) {
                            $query->where('documents.visualization_role_id', RoleServiceProvider::GENERAL_PUBLIC_ID)
                                ->orWhere('documents.visualization_role_id', RoleServiceProvider::PUBLIC_ID);
                        });
                })
                ->distinct()
                ->with(['folder', 'folder.project'])
                ->get();

            $documentsForCategory2 = Document::select('documents.*')
                ->join('projects', 'projects.id', '=', 'documents.project_id')
                ->join('project_user', 'projects.id', '=', 'project_user.project_id')
                ->join('users', 'users.id', '=', 'project_user.user_id')
                ->join('document_categories_documents', 'document_categories_documents.document_id', '=', 'documents.id')
                ->join('document_categories', 'document_categories.id', '=', 'document_categories_documents.category_id')
                ->where("document_categories.name", "like", "%{$consulta}%")
                ->where(function ($query) {
                    $query->where('documents.visualization_role_id', RoleServiceProvider::PRIVATE_ID)
                        ->where('users.id', Auth::user()->id)
                        ->orWhere(function ($query) {
                            $query->where('documents.visualization_role_id', RoleServiceProvider::GENERAL_PUBLIC_ID)
                                ->orWhere('documents.visualization_role_id', RoleServiceProvider::PUBLIC_ID);
                        });
                })
                ->distinct()
                ->with(['project'])
                ->get();

            $documentsForCategory = $documentsForCategory1->toBase()->merge($documentsForCategory2);

            $folders = Folder::select('folders.*')
                ->join('projects as p', 'p.id', '=', 'folders.project_id')
                ->join('project_user', 'p.id', '=', 'project_user.project_id')
                ->join('users', 'users.id', '=', 'project_user.user_id')
                ->where("folders.name", "like", "%{$consulta}%")
                ->where(function ($query) {
                    $query->where('folders.visualization_role_id', RoleServiceProvider::PRIVATE_ID)
                        ->where('users.id', Auth::user()->id)
                        ->orWhere(function ($query) {
                            $query->where('folders.visualization_role_id', RoleServiceProvider::GENERAL_PUBLIC_ID)
                                ->orWhere('folders.visualization_role_id', RoleServiceProvider::PUBLIC_ID);
                        });
                })
                ->distinct()
                ->with('project')
                ->get();
        } else {

            $projects = Project::where("name", "like", "%{$consulta}%")->get();
            $documents = Document::where("name", "like", "%{$consulta}%")->with(['project', 'folder.project', 'categories'])->get();
            $folders = Folder::where("name", "like", "%{$consulta}%")
                ->where(function ($query) {
                    $query->whereNotNull('father_id')
                        ->orWhereNotNull('project_id');
                })->with('project')->get();
            $documentsForCategory = Document::whereHas('categories', function ($query) use ($consulta) {
                $query->where('name', "like", "%{$consulta}%");
            })->with(['project', 'folder.project', 'categories'])->get();
        }

        /**
         @TODO: CAMBIAR VISTA A RENDERIZAR
         */
        error_log("-------------------------------------");
        error_log("Project $projects");
        error_log("Folders $folders");
        error_log("Validities $validities");
        error_log("SharedResources $sharedResources");
        return Inertia::render("Search/Index", [
            "projects" => $projects,
            "documents" => $documents,
            "folders" => $folders,
            "validities" => $validities,
            "shared-resources" => $sharedResources,
            "isAuthenticated" => AuthServiceProvider::checkAuthenticated(),
            "role" => AuthServiceProvider::getRole(),
            "visualizationsRole" => VisualizationRole::all(),
            "documentsForCategory" => $documentsForCategory,
            "documentCategories" => DocumentCategory::all(),
        ]);
    }
}
