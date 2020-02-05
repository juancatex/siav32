<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    //protected $table = 'socios'; si es que la tabla no es el plurar del modelo
    //protected $primaryKey = 'id';
    //protected $table = 'socios';
    protected $primaryKey='idsocio';
    /*
    protected $fillable = [ 'codsocio',
                            'numpapeleta',
                            'nombre',
                            'apaterno',
                            'amaterno',
                            'aesposo',
                            'sexo',
                            'carnetmilitar',
                            'ci',
                            'cisocio',
                            'iddepartamentoexpedido',
                            'fechanacimiento',
                            'fechaegreso',
                            'fechaincorporacion',
                            'idmunicipionacimiento',
                            'idgrado',
                            'idfuerza',
                            'carnetestado',
                            'iddestino',
                            'idescalafon',
                            'idespecialidad',
                            'idestadocivil',
                            'direcciondomicilio',
                            'telfijo',
                            'telcelular',
                            'email',
                            'observaciones',
                            'bloqueo',
                            'idestado',
                            'idtiposocio',
                            'rutafoto',
                            'idusuarioregistro',
                            'idusuariomodificacion',
                            'activo'
                        ];
    */

    /*
    public function con_cuentasocios()
    {
        return $this->hasMany('App\Con_Cuentasocio');
    }

    public function par_departamento()
    {
        return $this->belongsTo('App\Par_Departamento');
    }
	
	public function par_fuerza()
    {
        return $this->belongsTo('App\Par_Fuerza');
    }

    public function par_municipio()
    {
        return $this->belongsTo('App\Par_Municipio');
    }

    public function par_grado()
    {
        return $this->belongsTo('App\Par_Grado');
    }
    public function par_destino()
    {
        return $this->belongsTo('App\Par_Destino');
    }
    public function par_escalafon()
    {
        return $this->belongsTo('App\Par_Escalafon');
    }

    public function par_especialidad()
    {
        return $this->belongsTo('App\Par_Especialidad');
    }

    public function par_estadocivil()
    {
        return $this->belongsTo('App\Par_Estadocivil');
    }

    public function par_estado()
    {
        return $this->belongsTo('App\Par_Estado');
    }

    public function par_tiposocio()
    {
        return $this->belongsTo('App\Par_Tiposocio');
    }
    */


 }
