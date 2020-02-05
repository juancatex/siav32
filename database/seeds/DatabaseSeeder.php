<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables([
            'par_fuerzas',
            'par_departamentos',
            'par_escalafones',
            'par_especialidades',
            'par_estadocivil',
            'par_grados',
            'par_estadomodulos',
            'par_municipios',
            'par__destinos',
            'par__documentos',
            'par__ventanamodulos',
            'par__tiposocios',
            'par__modulos',
            'par__monedas',
            'ser__servicios',
            'ser__establecimientos',
            'ser__ambientes',
            'ser__civils',
            'ser__implementos',
            'apo__estados',
            'apo__tipoaportes',
            'con__cuentanivel1',
            'con__cuentanivel2',
            'con__cuentanivel3',
            'con__cuentanivel4',
            'con__cuentanivel5',
            'con__cuentas',
            'con__perfilcuentamaestros',
            'con__perfilcuentadetalles',
            'con__entidadbancarias',
            'con__tipocomprobantes',
            'par__prestamos__tipo__ejecucion',
            'daa__tipodevolucions',
            'adm__permisos',
            'adm__roles',
            'adm__users',
            'par__prestamos__estados',
            'par__prestamos__escalas',
            'par__escalas',
            'par__factors',
            'par__productos__factores',
            'par__productos__parametros',
            'par__productos', 
            'configs',
            'par__productos__criterios', 
            'par__fecha__sistemas',
            'daa__estudiomatematicos',
            'afi__beneficiarios',
            'fil__filials',
            'fil__unidads',
            'fil__oficinas',
            'fil__directivos',
            'act__grupos',
            'act__auxiliars',
            'act__motivos',
            'act__ambientes',
            'alm__grupos',
            'alm__medidas',
            'alm__proveedors',
            'con__configuracions',
            'rrh__empleados',
            'rrh__formacions',
            'rrh__profesions',
            'rrh__cargos',
            'rrh__motivos',
            'rrh__seguros',
            'rrh__tipocontratos',
            'rrh__contratos'
        ]);
        //$this->call(UsersTableSeeder::class);
        $this->call(Par_FuerzasSeeder::class);
        $this->call(Par_DepartamentosSeeder::class);
        $this->call(Par_EscalafonesSeeder::class);
        $this->call(Par_EspecialidadesSeeder::class);
        $this->call(Par_EstadocivilSeeder::class);
        $this->call(Par_GradosSeeder::class);
        $this->call(Par_EstadomodulosSeeder::class);
        $this->call(Par_MunicipiosSeeder::class);
        $this->call(Par_DestinosSeeder::class);
        $this->call(Par_DocumentosSeeder::class);
        $this->call(Par_VentanamodulosSeeder::class);        
        $this->call(Par_TiposocioSeeder::class);
        $this->call(Par_ModulosSeeder::class);   
        $this->call(Par_MonedasSeeder::class); 
        $this->call(Ser_ServiciosSeeder::class);
        $this->call(Ser_EstablecimientosSeeder::class);
        $this->call(Ser_AmbientesSeeder::class);
        $this->call(Ser_CivilsSeeder::Class);
        $this->call(Ser_ImplementosSeeder::class);
        $this->call(Apo_EstadoAporte::class);
        $this->call(Apo_TipoaportesSeeder::class);
        $this->call(Con_Cuentanivel1Seeder::class);
        $this->call(Con_Cuentanivel2Seeder::class);
        $this->call(Con_Cuentanivel3Seeder::class);
        $this->call(Con_Cuentanivel4Seeder::class);
        $this->call(Con_Cuentanivel5Seeder::class);
        $this->call(Con_CuentasSeeder::class);
        $this->call(Con_PerfilcuentamaestrosSeeder::class);
        $this->call(Con_PerfilcuentadetallesSeeder::class);
        $this->call(Con_EntidadbancariasSeeder::class);
        $this->call(Con_TipocomprobanteSeeder::class);
        $this->call(Par_prestamos_tipo_ejecucionSeeder::class);        
        $this->call(Daa_TipodevolucionSeeder::class);
        $this->call(Adm__PermisosSeeder::class);
        $this->call(Adm__RolesSeeder::class);
        $this->call(Adm__UsersSeeder::class);        
        $this->call(Par_prestamos_estadoSeeder::class);           
        $this->call(Par_Prestamos_escalasSeeder::class);
        $this->call(Par_EscalasSeeder::class);
        $this->call(Par_Factor::class);
        $this->call(Par_Productos_Factores::class);
        $this->call(Par_Producto_Parametro::class);
        $this->call(Par_productosSeeder::class);
        $this->call(ConfigSeeder::class);
        $this->call(Par_Producto_Criterio::class);
        $this->call(Par_Fecha_SistemaSeeder::class);
        $this->call(Daa_Estudiomatematico::class);
        $this->call(Afi_BeneficiariosSeeder::class);
        $this->call(Fil_FilialsSeeder::class);
        $this->call(Fil_UnidadsSeeder::class);
        $this->call(Fil_OficinasSeeder::class);
        $this->call(Fil_DirectivosSeeder::class);
        $this->call(Act_GruposSeeder::class);
        $this->call(Act_AuxiliaresSeeder::class);
        $this->call(Act_MotivosSeeder::class);
        $this->call(Act_AmbientesSeeder::class);
        $this->call(Alm_GruposSeeder::class);
        $this->call(Alm_SuministrosSeeder::class);
        $this->call(Alm_ProveedoresSeeder::class);
        $this->call(Alm_MedidasSeeder::class);
        $this->call(ConConfiguracionsSeeder::class);
        $this->call(Rrh_EmpleadosSeeder::class);
        $this->call(Rrh_FormacionsSeeder::class);
        $this->call(Rrh_ProfesionsSeeder::class);
        $this->call(Rrh_CargosSeeder::class);
        $this->call(Rrh_MotivosSeeder::class);
        $this->call(Rrh_SegurosSeeder::class);
        $this->call(Rrh_TipocontratosSeeder::class);
        $this->call(Rrh_ContratosSeeder::class);
    }

    protected function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS =0');

        foreach($tables as $table)
        {
            DB::table($table)->truncate(); // para vaciar la tabla
        }
        
        
        //DB::statement('SET FOREIGN_KEY_CHECKS =1');
    }
}
