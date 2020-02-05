<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Producto extends Model
{
    protected $table = 'par__productos';
    protected $primaryKey = 'idproducto';
    protected $fillable = ['moneda','idfactor','idescala','nomproducto','codproducto','max_prestamos'
    ,'lote'
    ,'blockauto'
    ,'linea'
    ,'garantes'
    ,'cancelarprestamos'
    ,'tasa'
    ,'plazominimo'
    ,'plazomaximo'
    ,'activo'
    ,'desembolso_perfil'
    ,'cobranza_perfil'
    ,'serializedmap'
    ,'fecharegistro'];

}
