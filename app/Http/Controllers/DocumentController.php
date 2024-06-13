<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Validity;
use Illuminate\Http\Request;
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
        $validator = Validator::make(request()->all(), [
            'document' => 'required|file|max:10240',
            'visualizationRoleSelected' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $file = request()->file("document");
        $path = $this->uploadDocument($file, $validityYear, $projectID);
        if (!$path) {
            return redirect()->back()->with("errorMessage", "Documento ya existe");;
        }
        $document = new Document();
        $document->name = $file->getClientOriginalName();
        $document->documentPath = $path;
        $document->format = $file->extension() ?? $this->getExtension($document->name);
        $document->project_id = $projectID;
        $document->visualization_role_id = request("visualizationRoleSelected");
        $document->save();

        return redirect()->back()->with("message", "Documento creado correctamente");
    }

    /** 
     * Subir el archivo al storage 
     */
    private function uploadDocument(UploadedFile $file, int $year, int $projectID)
    {
        $ruta = $year.'/'.$projectID;
        if (Storage::exists($ruta.'/'.$file->getClientOriginalName())) {
            return false;
        } 
        Storage::exists($ruta);
        $file->storeAs($ruta, $file->getClientOriginalName());
        return Storage::url($ruta.'/'.$file->getClientOriginalName());
    }

    /**
     * Obtener la extension de algunos documentos
     */
    private function getExtension(string $name)
    {
        $split = explode('.', $name);
        return $split[count($split)-1];
    }
}
