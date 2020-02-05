<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Tiposocio extends Model
{
    
    //protected $table= 'par__tiposocios';
    protected $primaryKey = 'idtiposocio';
    protected $fillable = ['nomtiposocio','activo'];

    public function par_ciudades()
    {
        return $this->hasMany('App\Socio');
    }
}
