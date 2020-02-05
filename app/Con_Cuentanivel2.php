<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Cuentanivel2 extends Model
{
    protected $table = 'con__cuentanivel2';
    protected $primaryKey = 'idcuentanivel2';
    protected $fillable = ['codn1','codn2','valorn2','nomcuentan2','activo'];
}
