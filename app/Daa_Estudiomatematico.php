<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daa_Estudiomatematico extends Model
{
    protected $primaryKey = 'idestudiomatematico';
    protected $fillable = ['nomestudiomatematico','anioinicio','aniofinal'];
}
