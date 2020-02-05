<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Excedenteaporte extends Model
{
    protected $primaryKey = 'idexcedente';
    protected $table = 'apo__excedenteaportes';
    protected $fillable = ['idaporte','numpapeleta','fechaaporte'];
}
