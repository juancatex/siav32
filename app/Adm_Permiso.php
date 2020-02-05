<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adm_Permiso extends Model
{
    protected $fillable = ['idventanamodulo','nompermiso','descripcion','metodo'];
    protected $primaryKey = 'idpermiso';

    public function roles() {
        return $this
        ->belongsToMany('App\Adm_Role','adm__rolepermisos','idpermiso','idrole');
    }
}