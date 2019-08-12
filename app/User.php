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
        'name', 'email', 'password', 'ruolo', 'login_capabilities'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function cacciatore()
      { 
          // the Cacciatore model is automatically assumed to have a user_id foreign key
          return $this->hasOne('App\Cacciatore','user_id','id');
      }


    public function hasRole($role)
      {
      return strtolower($role) === strtolower($this->ruolo);
      }

    /**
     * [hasLoginCapabilites definisce se un utente può fare login n base al boolean login_capabilities MA l'admin può FARE SEMPRE LOGIN]
     */
    public function hasLoginCapabilites()
      {
      return $this->ruolo == 'admin' || $this->login_capabilities;
      }

      
}
