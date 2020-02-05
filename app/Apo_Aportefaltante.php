<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Aportefaltante extends Model
{
    protected $primaryKey = 'idaportefaltante';
    protected $table = 'apo__aportefaltantes';
    protected $fillable =['numpapeleta',
                            'faltantes_obligatorio',
                            'aporte_ultimomes_obligatorio',
                            'suma_aporte_obligatorio',
                            'faltantes_jubilacion',
                            'aporte_ultmimomes_jubilacion',
                            'suma_aportes_jubilacion'];
}
