<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pto_Asignacion extends Model
{
    protected $primaryKey = 'idasignacion';
    protected $fillable = ['idpei','idfilial','idunidad','idestructuraprog','idpartida','observacion'];
}
