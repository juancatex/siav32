<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'configs';
    protected $primaryKey = 'idconfig';
    protected $fillable = ['codigo','descripcion','valor'];
}
