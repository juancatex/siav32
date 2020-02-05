<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adm_Role extends Model
{
    protected $fillable = ['nomrol','descripcionrol','idrolepadre'];
    protected $primaryKey = 'idrole';

    public function users() {
        return $this
        ->belongsToMany('App\Adm_User','adm__roleusers','iduser','idrole');
    }

    public function permisos() {
        return $this
        ->belongsToMany('App\Adm_Permiso','adm__rolepermisos','idrole','idpermiso')
        ->withTimestamps();
    }
}
