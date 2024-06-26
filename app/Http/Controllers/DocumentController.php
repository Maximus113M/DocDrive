<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Providers\AuthServiceProvider;
use Illuminate\Http\UploadedFile;
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
            'document' => 'required|file|max:20240',
            'visualizationRoleSelected' => 'required',
            'name' => ['required','string','unique:documents']
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
            
        return redirect()->back()->with("message", "Documento creado correctamente");
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

        if (!file_exists($rutaCompleta)) {
            abort(404, "Documento no existe");
        }

        $type = mime_content_type($rutaCompleta);

        return response()->file($rutaCompleta, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline',
        ]);
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
}
