<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'code', 'country_id', 'city_id', 'birth_date', 'gender','img','user_type','title_id', 'department_id','fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function opinions ()
    {
        return $this->hasMany('App\Models\Opinion');
    }
    public function notifications()
    {
        return $this->belongsToMany('App\Models\Notification');
    }
    public function title ()
    {
        return $this->belongsTo('App\Models\Title');
    }

    public function department ()
    {
        return $this->belongsTo('App\Models\Department');
    }
        public function country ()
    {
        return $this->belongsTo('App\Models\Country');
    }
    public function city ()
    {
        return $this->belongsTo('App\Models\City');
    }
}
