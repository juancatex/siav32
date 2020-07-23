<?php
$ip=$_SERVER['SERVER_ADDR'];
if ($_SERVER['REMOTE_ADDR']=='192.168.100.101')
$ip='127.0.0.1';
$ip='192.168.100.60';

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'ASCINALSS'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'America/La_Paz',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
		Intervention\Image\ImageServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
		'Image' => Intervention\Image\Facades\Image::class,

    ],

    'db_fields' => [
        'gestion',
        'codaporte',
        'codfuerza',
        'fuerza',
        'coddestino',
        'destino',
        'codaportedestino',
        'descaporte',
        'cuentaasscinals',
        'numpapeleta',
        'cisocio',
        'grado',
        'especialidad',
        'nombres',
        'aporte',
        'aporte2',
        'comision'
    ],
    'tipo_operacion'=>[
        1=>'Abono',
        2=>'Debito'
    ],

    'ruta_imagen' =>[
        'DIRE_FOTO_SOCIO'=>'img/socios/', 
        'DIRE_FOTO_SOCIO_REPORTES'=>'/var/lib/tomcat8/webapps/birt-viewer/reportes/fotos/'
    ],    
    

    'rutaReportes' => [
     // 'RUTE_REPORT' => 'http://localhost:8080/birt-viewer/frameset?__report=reportes/afi/kardex.rptdesign&id=',
        'RUTE_REPORT' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/afi/kardex.rptdesign&__format=pdf',
        'REP_FUERZA' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/afi/rep_fuerza.rptdesign&__format=pdf', 
        'REP_KARDEX' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/afi/rep_kardex.rptdesign&__format=pdf',
        'REP_RESUMEN' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/global/rep_resumen_desem.rptdesign&__format=pdf',  //mover a contabilidad
        'REP_INSCRIPCION' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/afi/rep_inscripcion.rptdesign&__format=pdf',
        'REP_EGRESO' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/afi/rep_egreso.rptdesign&__format=pdf',
      //'REP_CARNET' => 'http://localhost:8080/birt-viewer/frameset?__report=reportes/afi/carnetSocio.rptdesign&id_socio=',
        'REP_CARNET' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/afi/carnetSocio_simple.rptdesign&__format=pdf&id_socio=',
        'SER_CASACOM_ENT' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/servicios/ser_casacomentrada.rptdesign&idasignacion=',
        'SER_CASACOM_SAL' => 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/servicios/ser_casacomsalida.rptdesign&idasignacion=',
    ],
    'rutaApoReportes' => [
        'REP_ASCII'            =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/apo_rep_carga_ascci.rptdesign&__format=pdf&mes=', 
        'REP_OBSASCII'         =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/apo_rep_ascii_obs.rptdesign&__format=pdf&mes=',
        'REP_NUEVOSASCII'      =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/apo_rep_ascii_nuevos.rptdesign&__format=pdf&mes=',
        'REP_ABONOAP'          =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_abono_aportes.rptdesign&__format=pdf',
        'REP_APO_VERTICAL'     =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/reporte_vertical.rptdesign&__format=pdf&numpapeleta=',
        'REP_APO_HORIZONTAL'   =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&__format=pdf&numpapeleta=',
        'REP_APO_ABONO'        =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_abono_aportes.rptdesign&__format=pdf&idaporte=',
        'REP_APO_DEBITO'       =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_debito_aportes.rptdesign&__format=pdf&iddebito=',
        'REP_APO_FALTANTES'    =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_aportes_faltantes.rptdesign&__format=pdf&numpapeleta=',
        'REP_APO_DEBITO_ASCII' =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/apo_rep_debito_ascii.rptdesign&__format=pdf&idlote=',
        'REP_APO_DEVOLUCION'   =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/apo_rep_horizontal_devolucion.rptdesign&__format=pdf&numpapeleta=',
        'REP_DETALLEASCII'     =>'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/apo_reportes/apo_rep_ascii_detalle.rptdesign&__format=pdf&mes=',
        
    ],

    'rutaConReportes' => [
        'REP_ASIENTO_AUTOMATICO'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/con_reportes/asiento_automatico.rptdesign&__format=pdf&idasientomaestro=', 
        'REP_LIBRO_COMPRAS'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/con_reportes/libro_compras.rptdesign&__format=pdf&mes=', 
        'REP_SEG_CCUENTAS'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/con_reportes/seg_ccuentas.rptdesign&__format=pdf&idsolccuenta=', 
        'REP_PLAN_CUENTAS'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/con_reportes/plan_cuentas.rptdesign&__format=pdf', 
        'REP_DOC_OBLIGACION'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/con_reportes/doc_obligacion.rptdesign&__format=pdf&idsolccuenta=', 
        'REP_DOC_OBLIGACION_DIRECTORIO'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/con_reportes/doc_obligacion_directorio.rptdesign&__format=pdf&idsolccuenta=', 
        
      ],
      
      'rutaPrestamos' => [
        'REP_PRECALIFICACION'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/pre_calificacion/prestamo_info.rptdesign&__format=pdf&prestamo=', 
        'REP_OBS'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/pre_calificacion/prestamo_obs.rptdesign&__format=pdf&prestamo=', 
        'REP_COBRANZA'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/pre_calificacion/cobranza.rptdesign&__format=pdf&presamo=', 
        'REP_DESEMBOLSO'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/pre_calificacion/desembolso.rptdesign&__format=pdf&presamo=', 
    ],

    'rutaDaaros'=>[
        'REP_DEV_SOCIOS'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/daaro/dev_individual.rptdesign&__format=pdf',         
    ],

    'rutaServicios' => [
        'REP_CONTRATOJP'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/servicios/ser_jpintocontrato.rptdesign&__format=pdf&idasignacion=', 
        'REP_ENTRADACC'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/servicios/ser_casacomentrada.rptdesign&__format=pdf&idasignacion=', 
        'REP_SALIDACC'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/servicios/ser_casacomsalida.rptdesign&__format=pdf&idasignacion=', 
    ],

    'rutaDaaros'=>[
        'REP_DEV_SOCIOS'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/daaro/dev_individual.rptdesign&__format=pdf',         
    ],

    'rutaLiquidarsaldos'=>[
        'LIQ_SALDOS_MENORES'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/prestamos/liq_saldosmenores.rptdesign&__format=pdf',         
    ],

    'rutaLiquidaracreedor'=>[
        'LIQ_SALDOS_ACREEDOR'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/prestamos/liq_saldosacreedor.rptdesign&__format=pdf',         
    ],

    'rutaDevacreedor'=>[
        'REP_DEV_ACREEDOR'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/prestamos/liq_acreedordev.rptdesign&__format=pdf',         
    ],

    'rutaLiqReportes'=>[
        'REP_GENERAL'=> 'http://'.$ip.':8080/birt-viewer/frameset?__report=reportes/prestamos/liq_general.rptdesign&__format=pdf',         
    ],
    
    'ip'=>[$ip],
];
