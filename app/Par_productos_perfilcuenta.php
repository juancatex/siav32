<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_productos_perfilcuenta extends Model
{
    protected $table = 'par__productos__perfilcuentas';
    protected $primaryKey = 'idproductoperfil';
    protected $fillable = ['idproducto','idperfilcuentadetalle','valor_abc','valor_abc_php','iscargo','formula','formulaphp','html_value','fecharegistro','activo'];
}
