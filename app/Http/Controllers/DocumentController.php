<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use App\Models\Project;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Crear un documento
     */
    public function store($validityYear, $projectID)
    {
        if (!AuthServiceProvider::checkAuthenticated()) {
            abort(403, "No tienes permisos para estar aqui");
        }
        $validator = Validator::make(request()->all(), [
            'document' => 'required|file|max:300000',
            'visualizationRoleSelected' => 'required',
            'name' => ['required', 'string', 'unique:documents']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = request()->file("document");
        $path = $this->uploadDocument($file, $validityYear, $projectID, request("folder_id") ?? null);
        if (!$path) {
            return redirect()->back()->with("errorMessage", "Documento ya existe");;
        }
        $document = new Document();
        $document->name = request("name");
        $document->documentPath = $path;
        $document->format = $file->extension() ?? $this->getExtension($document->name);
        if (request("folder_id") == null) {
            $document->project_id = $projectID;
        }
        $document->visualization_role_id = request("visualizationRoleSelected");
        $document->save();
        if (request("folder_id") !== null) {
            $document->folder()->attach(request("folder_id"));
        }
        if (request("category")) {
            $category = request("category");
            error_log("$category");

            $document->categories()->attach($category);
        } else {
            $document->categories()->attach(1);
        }

        return redirect()->back()->with("message", "Documento creado correctamente");
    }

    /**
     * Crear un link
     */
    public function storeLink($validityYear, $projectID)
    {
        if (!AuthServiceProvider::checkAuthenticated()) {
            abort(403, "No tienes permisos para estar aqui");
        }
        $validator = Validator::make(request()->all(), [
            'link' => 'required|url',
            'visualizationRoleSelected' => 'required',
            'name' => ['required', 'string', 'unique:documents']
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $document = new Document();
        $document->name = request("name");
        $document->documentPath = request("link");
        $document->format = "link";
        if (request("folder_id") == null) {
            $document->project_id = $projectID;
        }
        $document->visualization_role_id = request("visualizationRoleSelected");
        $document->save();
        if (request("folder_id") !== null) {
            $document->folder()->attach(request("folder_id"));
        }


        return redirect()->back()->with("message", "Link creado correctamente");
    }

    /** 
     * Subir el archivo al storage 
     */
    private function uploadDocument(UploadedFile $file, int $year, int $projectID, ?int $folderID)
    {
        $ruta = $year . '/' . $projectID;
        if (isset($folderID)) {
            $ruta .= '/' . $folderID;
        }
        if (Storage::exists($ruta . '/' . $file->getClientOriginalName())) {
            return false;
        }
        Storage::exists($ruta);
        $file->storeAs($ruta, $file->getClientOriginalName());
        return Storage::url($ruta . '/' . $file->getClientOriginalName());
    }

    /**
     * Obtener la extension de algunos documentos
     */
    private function getExtension(string $name)
    {
        $split = explode('.', $name);
        return $split[count($split) - 1];
    }

    /**
     * Mostrar el documento
     */
    public function show($documentID)
    {
        $document = Document::find($documentID);

        $rutaArreglada = str_replace('/storage/', '', $document->documentPath);

        $rutaCompleta = Storage::path($rutaArreglada);

        $explodeRuta = explode('.', $rutaCompleta);
        $explodeName = explode('/', $explodeRuta[0]);

        $name = $explodeName[count($explodeName) - 1];
        if (!file_exists($rutaCompleta)) {
            abort(404, "Documento no existe");
        }

        $nombreArchivo = basename($rutaCompleta);
        $extension = pathinfo($rutaCompleta, PATHINFO_EXTENSION);
        $type = $this->getMime($rutaCompleta, $extension);
        if (!$type) {
            abort(404, "Documento no encontrado");
        }
        $visualizationType = 'attachment';
        if ($extension == 'pdf') {
            $visualizationType = 'inline';
        }
        return Storage::download($rutaArreglada, $name,  [
            'Content-Type' => $type,
            'Content-Disposition' => $visualizationType . '; filename="' . $nombreArchivo . '"'
        ]);
    }

    /**
     * Extrae el mime
     */
    private function getMime(string $ruta, string $extension)
    {
        switch ($extension) {
            case 'xlsx':
                $type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'xls':
                $type = 'application/vnd.ms-excel';
                break;
            case 'csv':
                $type = 'text/csv';
                break;
            case 'tsv':
                $type = 'text/tab-separated-values';
                break;
            case 'ods':
                $type = 'application/vnd.oasis.opendocument.spreadsheet';
                break;
            case 'xml':
                $type = 'application/xml';
                break;
            case 'xlsb':
                $type = 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
                break;
            default:
                $type = mime_content_type($ruta); // Fallback type
                break;
        }
        return $type;
    }

    /**
     * Mostrar el documento protegido
     */
    public function showProtected($validityYear, $projectID, $documentID)
    {
        return $this->show($documentID);
    }

    /**
     * Mostrar el documento que esta dentro de los recursos compartidos
     */
    public function showSharedResource($folderID, $documentID)
    {
        return $this->show($documentID);
    }

    /**
     * Eliminar un documento
     */
    public function destroy($projectID, $documentID)
    {
        $project = Project::find($projectID);
        $usersID =  $project->users->pluck('id')->toArray();
        $role = AuthServiceProvider::getRole();

        if (!in_array(Auth::user()->id, $usersID) && $role != RoleServiceProvider::ADMIN) {
            abort(403, "No tienes persmisos para estar aqui");
        }


        $document = Document::find($documentID);
        $rutaArreglada = str_replace('/storage/', '', $document->documentPath);
        Storage::delete($rutaArreglada);
        $document->folder()->detach();
        $document->categories()->detach();
        $document->delete();

        return redirect()->back()->with("message", "Documento eliminado correctamente");
    }


    /**
     * Actualizar un documento normal
     */
    public function updateDocument($projectID, $documentID)
    {
        $project = Project::find($projectID);
        $usersID =  $project->users->pluck('id')->toArray();
        $role = AuthServiceProvider::getRole();

        if (!in_array(Auth::user()->id, $usersID) && $role != RoleServiceProvider::ADMIN) {
            abort(403, "No tienes persmisos para estar aqui");
        }

        return $this->update($documentID);
    }

    /**
     * Actualizar un documento
     */
    public function update($documentID)
    {
        $validator = Validator::make(request()->all(), [
            'visualizationRoleSelected' => 'required|numeric',
            'name' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $document = Document::find($documentID);
        $document->name = request("name");
        $document->visualization_role_id = request("visualizationRoleSelected");
        if (request("category")) {
            $category = request("category");
            error_log("$category");

            $document->categories()->detach();
            $document->categories()->attach($category);
        } else {
            $document->categories()->detach();
            $document->categories()->attach(1);
        }
        $document->update();

        return redirect()->back()->with("message", "Documento actualizado correctamente");
    }


    /**
     * Asociar documentos
     */
    public function associatedDocument($folderID)
    {
        $validator = Validator::make(request()->all(), [
            'documentsID' => '',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $folder = Folder::find($folderID);
        $documentsID = $folder->documents->pluck('id')->toArray();
        $this->disassociatedDocuments($folder, request("documentsID"));

        foreach (request("documentsID") as $documentID) {
            if (!in_array($documentID, $documentsID)) {
                $folder->documents()->attach($documentID);
            }
        }

        return redirect()->back()->with("message", "Documentos asociados/desasociados correctamente");
    }

    /**
     * 
     * Desasociar documentos
     */
    public function disassociatedDocuments($folder, $inputDocumentsID)
    {
        $documentsID = Document::pluck("id");
        $documentsIDToRemove = $this->removeDuplicates($inputDocumentsID, $documentsID);
        $folder->documents()->detach($documentsIDToRemove);
    }


    /**
     * Remover los ids repetidos de los documents para tener los ids no seleccionados a eliminar
     */
    private function removeDuplicates($inputDocumentsID, $documentsID)
    {
        return array_filter($documentsID->toArray(), function ($documentID) use ($inputDocumentsID) {
            return !in_array($documentID, $inputDocumentsID);
        });
    }
}
