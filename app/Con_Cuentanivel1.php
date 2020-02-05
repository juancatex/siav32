<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Cuentanivel1 extends Model
{
    protected $table = 'con__cuentanivel1';
    protected $primaryKey = 'idcuentanivel1';
    protected $fillable = ['codn1','nomcuentan1','activo'];
}
