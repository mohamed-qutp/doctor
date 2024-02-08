<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        "title_ar",
        "title_en",
        "body_ar",
        "body_en",
        "user_id",
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
