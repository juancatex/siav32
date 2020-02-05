<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Entidadbancaria extends Model
{
    protected $primaryKey = 'identidadbancaria';
    protected $fillable =['nomentidadbancaria','siglaentidadbancaria','activo'];

    public function con_cuentasocios()
    {
        return $this->hasMany('App\Con_Cuentasocio');
    }
}
