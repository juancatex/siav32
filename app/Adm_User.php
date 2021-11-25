<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Adm_User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
     protected $table = 'adm__users';
     protected $fillable = [
        'username', 'email', 'password','activo'
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

    public function roles() {
        return $this
        ->belongsToMany('App\Adm_Role','adm__roleusers', 'iduser','idrole')
        ->withTimestamps();
    }

    public function authorizeRoles($roles) {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'Esta acción no está autorizada.');
    }

    public function hasAnyRole($roles) {
        if (is_array($roles)) { 
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else { 
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public  function hasRole($role){
        //echo $valor =  $this->roles()->where('nomrol', $role)->select('nomrol')->toSql();
        $valor =  Adm_User::where('username','=',$role)->get();
        foreach($valor as $user) 
            $rol = $user->roles[0]->nomrol;
        
        if ($rol) {
            return ($rol);
        }
        return false;
    }

    public function ja() {
        $data = '1234';
        return ($data);
        
    }
    public function jaja() {
        $data = $this->ja();
        $data =  $data . 'aaaa';
        return ($data);
        
    }
}
