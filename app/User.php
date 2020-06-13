<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
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

    
    // ROLE DEFINITION
    public function isPengurus() {
       return $this->role === 'pengurus';
    }

    public function isDonatur() {
       return $this->role === 'donatur';
    }

    public function isAdmin() {
       return $this->role === 'admin';
    }

    public function isUser() {
       return $this->role === NULL;
    }

    public function role(){
        return $this->role;
    }
}
