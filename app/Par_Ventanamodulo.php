<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Ventanamodulo extends Model
{
    protected $fillable = ['nomventanamodulo','template'];
    protected $primaryKey = 'idventanamodulo';
}
