<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Cuentanivel3 extends Model
{
    protected $table = 'con__cuentanivel3';
    protected $primaryKey = 'idcuentanivel3';
    protected $fillable = ['codn2','codn3','valorn3','nomcuentan3','activo'];
}
