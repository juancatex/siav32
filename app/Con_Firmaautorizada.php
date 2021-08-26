<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Firmaautorizada extends Model
{
    protected $table = 'con__firmaautorizadas';
    protected $primaryKey = 'idfirmaautorizada';
    protected $fillable = ['idpersona','tipo_persona','activo','orden'];
}
