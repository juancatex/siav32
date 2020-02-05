<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Tipocomprobante extends Model
{
    protected $table = 'con__tipocomprobantes';
    protected $primaryKey = 'idtipocomprobante';
    protected $fillable = ['nomtipocomprobante',
                            'descripcion'];
    
    
}
