let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.webpackConfig({ node: { fs: 'empty' }});
mix.styles([
    'resources/assets/plantilla/css/font-awesome.min.css',
    'resources/assets/plantilla/css/simple-line-icons.min.css',
    'resources/assets/plantilla/css/cropper.css',
    'resources/assets/plantilla/css/bootstrap.css', 
    'resources/assets/plantilla/css/style.css',
    'resources/assets/plantilla/css/daterangepicker.css',
    'resources/assets/plantilla/css/animated.css',
    'resources/assets/plantilla/css/vue-select.css',
    'resources/assets/plantilla/css/ion.css',
    'resources/assets/plantilla/css/spinkit.min.css',
    'resources/assets/plantilla/css/coreui-icons.min.css',
    'resources/assets/plantilla/css/theme/blue/pace-theme-minimal.css',
    'resources/assets/plantilla/css/ion.css',
    'resources/assets/plantilla/css/datetimepicker.css',
    'resources/assets/plantilla/css/dataTables.bootstrap4.min.css',
    'resources/assets/plantilla/css/jquery.toolbar.css'
], 'public/css/plantilla.css')
.styles(['resources/assets/plantilla/css/login.css',
'resources/assets/plantilla/css/font-awesome.min.css'
], 'public/css/login.css')
.scripts([
    'resources/assets/plantilla/js/jquery.js',
    'resources/assets/plantilla/js/qrious.js',
    'resources/assets/plantilla/js/jquery-ui.js',
    'resources/assets/plantilla/js/popper.min.js',
    'resources/assets/plantilla/js/bootstrap.min.js',
    'resources/assets/plantilla/js/Chart.min.js',
    'resources/assets/plantilla/js/pace.min.js',
    'resources/assets/plantilla/js/template.js',  
    'resources/assets/plantilla/js/cropper.js', 
    'resources/assets/plantilla/js/sweetalert2.all.js',
    'resources/assets/plantilla/js/moment.min.js',
    'resources/assets/plantilla/js/daterangepicker.js',
    'resources/assets/plantilla/js/datetimepicker.js',
    'resources/assets/plantilla/js/ion.js',
    'resources/assets/plantilla/js/trfdp.js',
    'resources/assets/plantilla/js/ttrfdp.js',
    'resources/assets/plantilla/js/autocomplete.js',
    'resources/assets/plantilla/js/Sortable.js',
    'resources/assets/plantilla/js/tooltips.js', 
    'resources/assets/plantilla/js/jquery.toolbar.js',  
    'resources/assets/plantilla/js/jquery.dataTables.min.js', 
    'resources/assets/plantilla/js/dataTables.buttons.min.js', 
    'resources/assets/plantilla/js/datatables.min.js', 
    'resources/assets/plantilla/js/buttons.flash.js', 
    'resources/assets/plantilla/js/buttons.html5.js', 
    'resources/assets/plantilla/js/buttons.print.js'  
], 'public/js/plantilla.js')
.scripts([
    'resources/assets/plantilla/js/jquery.js', 
    'resources/assets/plantilla/js/login.js'
], 'public/js/login.js')
 
.js(['resources/assets/js/app.js'],'public/js/app.js') ;