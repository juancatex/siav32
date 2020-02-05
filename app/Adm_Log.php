<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adm_Log extends Model
{
    protected $table = 'telescope_entries';
    protected $primaryKey = 'uuid';        
}
