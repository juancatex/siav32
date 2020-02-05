<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adm_Roleuser extends Model
{
    //
    protected $table = 'adm__roleusers';
    protected $primaryKey = 'idroleusers';
    protected $fillable = ['idrole','iduser','activo'];
}
