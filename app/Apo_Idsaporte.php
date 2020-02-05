<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Idsaporte extends Model
{
    protected $primaryKey = 'ididsaportes';
    protected $table = 'apo__idsaportes';
    protected $fillable = ['idaporte','numpapeleta','tipodevolucion'];
}
