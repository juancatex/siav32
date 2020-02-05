<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Estadomodulo extends Model
{
    protected $table = 'par_estadomodulos';
    protected $primaryKey = 'idestadomodulo';
    protected $fillable = ['nomestado','idmodulo','idestado','activo'];

    public function par_estadomodulos()
    {
        return $this->hasMany('App\Par_Estadomodulo');
    }
    public function socios()
    {
        return $this->hasMany('App\Socios');
    }
}
