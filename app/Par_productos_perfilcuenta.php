<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_productos_perfilcuenta extends Model
{
    protected $table = 'par__productos__perfilcuentas';
    protected $primaryKey = 'idproductoperfil';
    protected $fillable = ['idproducto','idperfilcuentadetalle','valor_abc','key_abc','iscargo','formula','html_value','fecharegistro','activo'];
}
