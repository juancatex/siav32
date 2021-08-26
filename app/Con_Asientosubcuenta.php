<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Asientosubcuenta extends Model
{
    protected $table = 'con__asientosubcuentas';
    protected $primaryKey = 'idasientosubcuenta';
    protected $fillable = ['idasientomaestro',
                            'tipo_subcuenta',
                            'idsubcuenta',
                            'subcuenta',
                            'idcuenta',
                            'subdetalle',
                            'subdebe',
                            'subhaber',
                        ];
}
