<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adm_Ventanamodulo extends Model
{
    protected $fillable = ['nomventanamodulo','template','idrol'];
    protected $primaryKey = 'idventanamodulo';
    
}
