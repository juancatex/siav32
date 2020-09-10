<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Perfilcuentamaestro extends Model
{
    protected $table = 'con__perfilcuentamaestros';
    protected $primaryKey = 'idperfilcuentamaestro';
    protected $fillable = ['nomperfil','descripcion','idtipocomprobante','idmodulo','idusuario'];
    
    /*public function con__tipocomprobante()
    {
        return $this->belongsTo('App\Con_Tipocomprobante');
    }*/
}
