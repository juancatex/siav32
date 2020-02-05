<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Carga_ascii extends Model
{
    protected $primaryKey = 'idcargaascii';
    protected $fillable = ['gestion',
                            'codaportecodfuerza',
                            'fuerza',
                            'coddestino',
                            'destino',
                            'codaportedestino',
                            'descaport',
                            'cuentaasscinals',
                            'numpapeleta',
                            'cisocio',
                            'grado',
                            'especialidad',
                            'nombres',
                            'aporte',
                            'aporte2',
                            'comision',
                            'idlote',
                            'idpersonal',
                            'idtipoaporte',
                            'fechaaporte',
                            'idcanal'
                        ];

    public function csv_data()
    {
        return $this->belongsTo('App\CsvData');
    }
    public function apo_canal()
    {
        return $this->belongsTo('App\Apo_Canal');
    }
}
