<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $fillable = [
        'name_ar', 'name_en', 'department_id'
    ];


    public function department ()
    {
        return $this->belongsTo('App\Models\Dapartment');
    }
        public function users (){
        return $this->hasMany('App\Models\User');
    }
}
