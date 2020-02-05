<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pto_Estimacion extends Model
{
    protected $primaryKey = 'idestimacion';
    protected $fillable = ['idasignacion','descripcion','monto'];
}
