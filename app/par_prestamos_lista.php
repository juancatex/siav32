<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class par_prestamos_lista extends Model
{
    protected $table = 'par__prestamos__listas';
    protected $primaryKey = 'idlista';
    protected $fillable = ['idsocio_beneficiario','idcuentasocio_beneficiario',
'fecharegistro','idusuario_reg','idusuario_desembolso','estado'];
}
