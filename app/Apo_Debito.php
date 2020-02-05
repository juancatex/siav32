<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Debito extends Model
{
    protected $table = 'apo__debitos';
    protected $primaryKey = 'iddebito';
    protected $fillable = ['idaporte','monto','activo','idperfilcuentamaestro','obsdebito'];

    public function apo__aportes()
    {
        return $this->belongsTo('App\Apo_Aportes');
    }
    public function perfilcuentamaestro()
    {
        return $this->hasMany('App\Con_Perfilcuentamaestro');
    }

}
