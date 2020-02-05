<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Configuracion extends Model
{
    protected $table='con__configuracions';
    protected $primaryKey='idconconfig';
    protected $fillable=['codigo','descripcion','valor','tipoconfiguracion'];
}
