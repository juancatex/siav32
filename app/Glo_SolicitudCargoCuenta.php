<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Glo_SolicitudCargoCuenta extends Model
{
    protected $table = 'glo__solicitud_cargo_cuentas';
    protected $primaryKey = 'idsolccuenta';
    protected $fillable = ['idsolccuenta','subcuenta','idusuario','idrole','glosa','monto','estado_aprobado','idasientomaestro','modificado','fecha_desembolso','fecha_apertura_cuenta'];
}
