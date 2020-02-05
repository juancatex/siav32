<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Escala extends Model
{
    protected $table = 'par__escalas';
    protected $primaryKey = 'id';
    protected $fillable = ['idescala','minmonto','maxmonto','minanios','maxanios'];
}
