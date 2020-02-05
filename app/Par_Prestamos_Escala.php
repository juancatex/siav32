<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Prestamos_Escala extends Model
{
    protected $table = 'par__prestamos__escalas';
    protected $primaryKey = 'idescala';
    protected $fillable = ['nomescala'];
}
