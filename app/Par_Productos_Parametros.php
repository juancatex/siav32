<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Productos_Parametros extends Model
{
    protected $table = 'par__productos__parametros';
    protected $primaryKey = 'idprodparam';
    protected $fillable = ['idfactor','valorparametro','nombreparam','orden'];
    
    public function par_productos_criterios()
    {
        return $this->hasMany('App\Par_Productos_Criterios');
    }
 
}
