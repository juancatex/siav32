<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pto_Pei extends Model
{
    protected $primaryKey = 'idpei';
    protected $fillable = ['mision','vision','gestion','descripcion'];
}
