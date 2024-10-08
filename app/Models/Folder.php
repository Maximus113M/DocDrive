<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'documentPath',
        'description',
        'project_id',
        'visualization_role_id',
        'father_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, "project_id");
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, "document_folder");
    }

    public function visualizationRole()
    {
        return $this->belongsTo(VisualizationRole::class, "visualization_role_id");
    }

}
