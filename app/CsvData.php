<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    protected $table = 'csv_data';
    protected $primaryKey = 'idcsvdata';
    protected $fillable = ['idlote','csv_filename','fecha_archivo','activo','tipodocdebito','numdocdebito'];

    public function apo_carga_ascii()
    {
        return $this->hasMany('App\Apo_Carga_ascii');
    }
}
