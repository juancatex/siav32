<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class par_prestamo_garante extends Model
{
    protected $table = 'par__prestamos__garantes';
    protected $primaryKey = 'idgarante';
    protected $fillable = ['idsocio','factor','idprestamo','fecharegistro','activo'];
}
