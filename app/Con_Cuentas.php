<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Cuentas extends Model
{
    protected $table = 'con__cuentas';
    protected $primaryKey = 'idcuenta';
    protected $fillable = ['codn5','nomcuenta','codcuenta','descripcion','activo','idmoneda'];
}
