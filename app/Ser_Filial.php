<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ser_Filial extends Model
{
    protected $primaryKey='idfilial';
    protected $fillable = ['idfilial','codfilial','iddepartamento','idmunicipio','activo'];
}
