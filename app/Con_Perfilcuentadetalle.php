<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Perfilcuentadetalle extends Model
{
    protected $table = 'con__perfilcuentadetalles';
    protected $primaryKey = 'idperfilcuentadetalle';
    protected $fillable = ['idperfilcuentamaestro','idcuenta','tipocargo','porcentaje'];
}
