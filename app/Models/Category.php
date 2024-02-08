<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_ar', 'name_en', "description_ar" , "description_en" , "img"
    ];
    public function opinions ()
    {
        return $this->hasMany('App\Models\Opinion');
    }
    public function articles ()
    {
        return $this->hasMany('App\Models\Article');
    }
}
