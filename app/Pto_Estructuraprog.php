<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pto_Estructuraprog extends Model
{
    protected $primaryKey = 'idestructuraprog';
    protected $fillable = ['idpei','idobjestrategico'];
}
