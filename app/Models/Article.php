<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        "title_ar","title_en","description_ar","description_en","category_id","img",
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
