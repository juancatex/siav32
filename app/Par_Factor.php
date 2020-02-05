<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Factor extends Model
{
    protected $table='par__factors';
    protected $primaryKey='idf';
    protected $fillable = ['nomfactor'];
}
