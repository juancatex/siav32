<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Asientodetalle extends Model
{
    protected $table = 'con__asientodetalles';
    protected $primaryKey = 'idasientodetalle';
    protected $fillable = ['idasientomaestro',
                            'idcuenta',
                            'monto',
                            'moneda',
                            'usuarioregistro',
                            'usuariomodifica'];

    public function con__tipocomprobante()
    {
        return $this->belongsTo('App\Con_Tipocomprobante');
    }
}
