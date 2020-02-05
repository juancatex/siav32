<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class par_prestamo_estado extends Model
{
    protected $table = 'par__prestamos__estados';
    protected $primaryKey = 'idestado';
    protected $fillable = ['nombreestado'];
}
