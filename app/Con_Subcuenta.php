<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Subcuenta extends Model
{
    protected $table = 'con__subcuentas';
    protected $primaryKey = 'idsubcuenta';
    protected $fillable = ['idasientomaestro','subcuenta','tiposubcuenta'];

    public function con__asientomaestros()
    {
        return $this->hasMany('App\Con_Asientomaestro');
    }
}
