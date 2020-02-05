<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Con_Segbancario extends Model
{
    protected $table = 'con__segbancarios';
    protected $primaryKey = 'idsegbancario';
    protected $fillable = ['idcuenta','fecha_movimiento','concepto',
                            'iddepartamento',
                            'num_operacion',
                            'monto',
                            'tipomovimiento',
                            'activo',
                            'idasientomaestro'];
}
