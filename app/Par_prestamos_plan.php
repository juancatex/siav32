<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_prestamos_plan extends Model
{
    //
    protected $table = 'par__prestamos__plans';
    protected $primaryKey = 'idplan';
    protected $fillable = ['idprestamo',
'pe',
'fp',
'di',
'am',
'in',
'car',
'cut',
'ca',
'ca_an',
'cuota',
'fecharegistro',
'tipo',
'idestado',
'tipocambio',
'send_ascii',
'idtransaccionC',
'numDocC',
'glosa',
'fechaCobranza',
'importe',
'idusuario' 
];   
}
