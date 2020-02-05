<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daa_Descuento extends Model
{
    protected $primaryKey = 'iddescuentos';
    protected $fillable = ['cobranzas','contabilidada','servicio','juridica','daaro','retencion'];

    public function devolucion() {        
        return $this->belongTo('App\Daa_Devolucion', 'iddescuento');
    }

}




