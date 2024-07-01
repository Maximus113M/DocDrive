<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'documentPath',
        'description',
        'format',
        'project_id',
        'visualization_role_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, "project_id");
    }

    public function folder()
    {
        return $this->belongsToMany(Folder::class, "document_folder");
    }

    public function visualizationRole()
    {
        return $this->belongsTo(VisualizationRole::class, "visualization_role_id");
    }
    
    public function categories()
    {
        return $this->belongsToMany(DocumentCategory::class, "document_categories_documents");
    }
}
