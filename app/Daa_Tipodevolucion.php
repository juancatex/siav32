<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daa_Tipodevolucion extends Model
{
    protected $primaryKey = 'idtipodevolucion';
    protected $fillable = ['nomtipodevolucion','idconfig',];
    
}

