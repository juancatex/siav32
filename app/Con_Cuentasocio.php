<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Cuentasocio extends Model
{
    
    protected $primaryKey = 'idcuentasocio';
    protected $fillable = ['idsocio','identidadbancaria','numcuentasocio','activo'];

    public function socio()
    {
        return $this->belongsTo('App\Socio');
    }
    public function con_entidadbancaria()
    {
        return $this->belongsTo('App\Con_Entidadbancaria');
    }

}
