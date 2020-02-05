<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daa_Devolucion extends Model
{
    protected $primaryKey = 'iddevolucion';
    protected $fillable = ['idsocio','idtipodevolucion','idformula','iddescuento','totalparcial','totaldevolucion'];

    public function descuento() {
        return $this->hasOne('App\Daa_Descuento', 'iddevolucion');
    }
}
