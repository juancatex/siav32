<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Asientomaestro extends Model
{
    protected $table = 'con__asientomaestros';
    protected $primaryKey = 'idasientomaestro';
    protected $fillable = ['idtipocomprobante',
                            'cont_comprobante',
                            'idperfilcuentamaestro',
                            'fecharegistro',
                            'tipodocumento',
                            'numdocumento',
                            'glosa',
                            'idfilial',
                            'idmodulo',
                            'estado',
                            'idrevertido',
                            'loteprestamos',
                            'observaciones',
                            'agrupacion',
                            'segcobranza',
                            'u_registro',
                            'u_registro_tesoreria',
                            'u_obs_val',
                            'u_eliminacion',
                            'fact_modificada',
                            'idsolccuenta'
                        ];

    
}
