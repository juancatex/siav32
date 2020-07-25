<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Prestamos extends Model
{
    protected $table = 'par__prestamos';
    protected $primaryKey = 'idprestamo';
    protected $fillable = ['idprestamo_antiguo','idref','idrefaux','idejecucion','idproducto',
    'idsocio','idcuentasocio','idestado','idsupervisor','idoperario','monto','cuota','plazo','interesdiferido','fecharegistro','factor',
    'obs','no_prestamo','idtransaccionD','numDocD','fechardesembolso','detalle_desembolso','idusuario','fecharde_apro_conta',
    'apro_conta','lote','idasiento','liquidocomputable','cuotasvigentes','b_frontera','b_prolibro','b_familiar','b_riesgo','cuota_aprox','planPagosMap','idprestamo_lista'];
 
}
         
 
