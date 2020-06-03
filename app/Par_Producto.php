<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Producto extends Model
{
    protected $table = 'par__productos';
    protected $primaryKey = 'idproducto';
    protected $fillable = ['moneda','idfactor','idescala','nomproducto','codproducto','max_prestamos'
    ,'lote'
    ,'activar_garante'
    ,'linea'
    ,'garantes'
    ,'cancelarprestamos'
    ,'tasa'
    ,'plazominimo'
    ,'plazomaximo'
    ,'activo'
    ,'desembolso_perfil'
    ,'cobranza_perfil'
    ,'desembolso_perfil_refi'
    ,'desembolso_perfil_garante'
    ,'cobranza_perfil_refi'
    ,'cobranza_perfil_garante'
    ,'cobranza_perfil_ascii'
    ,'cobranza_perfil_acreedor'
    ,'cobranza_perfil_daro'
    ,'perfil_cambio_estado'
    ,'perfil_cambio_estado_mora'
    ,'serializedmap'
    ,'fecharegistro'];
  
}
