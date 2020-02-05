<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ser_Ambiente extends Model
{
    protected $primaryKey='idambiente';    
    protected $fillable = ['idambiente','idestablecimiento','codambiente','descripcion','activo'];
}
