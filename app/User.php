<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['role_name'];

    public function clinic()
    {
        return $this->hasOne('App\Models\Eloquent\Clinic')->withDefault();
    }

    /**
     * Get Role Label Name.
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return config('const.role.' . $this->role . '.name');
    }

    /*
     * Check if super admin 
     * 
     * @returns boolean
     */
     public function isSuperAdmin() {
       return (
         (config('const.role.' . $user->role . '.key'))== (config('const.role.super.key'))
       );
     }


}
