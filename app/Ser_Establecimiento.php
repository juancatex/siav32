<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ser_Establecimiento extends Model
{
    protected $primaryKey='idestablecimiento';
    protected $fillable = ['idestablecimiento','idfilial','idtiposervicio','nomestablecimiento','direccion','descripcion','activo'];

}
