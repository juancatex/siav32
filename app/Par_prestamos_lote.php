<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_prestamos_lote extends Model
{
    protected $table = 'par__prestamos__lotes';
    protected $primaryKey = 'idlote';
    protected $fillable = ['min', 'max', 'fecha', 'activo','close' ];
 
}
