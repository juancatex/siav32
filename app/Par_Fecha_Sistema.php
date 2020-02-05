<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Fecha_Sistema extends Model
{
    protected $table='par__fecha__sistemas';
    protected $primaryKey='id_fecha';
    protected $fillable = ['fechaSistema,fechaCorte'];
}
