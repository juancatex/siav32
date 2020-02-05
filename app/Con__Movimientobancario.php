<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con__Movimientobancario extends Model
{
    protected $table = 'con___movimientobancarios';
    protected $primaryKey = 'idmovimiento';
    protected $fillable = ['idasientomaestro','idcuenta','numdocumento','detalledocumento','importe','tipocargo','estado'];
}
