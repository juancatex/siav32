<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_TotalAporte extends Model
{
    protected $primaryKey = 'idtotalaporte';
    protected $table = 'apo__total_aportes';
    protected $fillable =['numpapeleta',
                            'cantobligados',
                            'totalobligados',
                            'obligatorios',
                            'cantjubilacion',
                            'totaljubilacion',
                            'jubilados',
                            'fecha_inicioaporte_obligados',
                            'fecha_finalaporte_obligados',
                            'fecha_inicioaporte_jubilacion',
                            'fecha_finalaporte_jubilacion'];
}
