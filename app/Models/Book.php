<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "books";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        "title",
        "description",
        "quantity",
        "pdf_file",
        "cover_image",
        "category_id",
        "created_by",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}
