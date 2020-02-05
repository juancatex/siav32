<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Tipoaporte extends Model
{
    protected $primaryKey = 'idtipoaporte';
    protected $fillable =['descripcion','idcuentacontable','activo'];

    public function cuentacontable()
    {
        return $this->hasMany('App\cuenta');
    }
}
