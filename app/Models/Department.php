<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name_ar', 'name_en'
    ];

    public function titles ()
    {
        return $this->hasMany('App\Models\Title');
    }
        public function users (){
        return $this->hasMany('App\Models\User');
    }
}
