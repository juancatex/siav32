<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apo_Estado extends Model
{
    protected $table = 'apo__estados';
    protected $primaryKey = 'idestado';
    protected $fillable = ['nomestado','permite_aportes','activo'];

    
}
