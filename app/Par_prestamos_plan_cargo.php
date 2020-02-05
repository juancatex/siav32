<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_prestamos_plan_cargo extends Model
{
    protected $table = 'par__prestamos__plan__cargos';
    protected $primaryKey = 'idcargos';
    protected $fillable = ['idplan',
'idperfilcuentadetalle',
'cargo', 
]; 
 
}
