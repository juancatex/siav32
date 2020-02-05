<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Productos_Factores extends Model
{
    protected $table = 'par__productos__factores';
    protected $primaryKey = 'idfactor';
    protected $fillable = ['nombrefactor','descripcion','refvalor'];
    
    public function par_productos_parametros()
    {
        return $this->hasMany('App\Par_Productos_Parametros');
    }
}
