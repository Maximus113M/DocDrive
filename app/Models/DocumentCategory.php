<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function documents()
    {
        return $this->belongsToMany(Document::class, "document_categories_documents", "category_id", "document_id");
    }
}
