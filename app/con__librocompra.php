<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class con__librocompra extends Model
{
    protected $table = 'con__librocompras';
    protected $primaryKey = 'idlibrocompra';
    protected $fillable = ['fecha_factura','idproveedor','numfactura','importe','impnocredfiscal','subtotal','descuentos','impcreditofiscal','credfiscal','codigocontrol'];

    
}
