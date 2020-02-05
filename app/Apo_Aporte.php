<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Aporte extends Model
{
    protected $primaryKey = 'idaporte';
    protected $fillable = ['idlote','numpapeleta','idtipoaporte','aporte','fechaaporte','metodoaporte','activo','idperfilcuentamaestro','obsaporte','validadoconta'];

    public function apo_tipoaporte()
    {
        return $this->belongsTo('App\Apo_Tipoaporte');
    }

    public function apo_canal()
    {
        return $this->belongsTo('App\Apo_Canal');
    }

    public function apo_fuerza()
    {
        return $this->belongsTo('App\Apo_Fuerza');
    }
    
    
}
