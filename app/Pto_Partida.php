<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pto_Partida extends Model
{
    protected $primaryKey = 'idpartida';
    protected $fillable = ['codigo','nompartida','nivel','descripcion','gestion','idcuenta','tipocuenta','tipopartida',
    'ptoinicial','ptomodificado','ptototal'];
}
