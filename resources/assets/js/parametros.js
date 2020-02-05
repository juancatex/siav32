//constantes 
//direccion ip del equipo donde esta instalado el sistema
var ip_server = '192.168.100.132:8080'
//var ip_server ='localhost'
//kardex en modulo de afiliacion
export const RUTE_REPORT = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/afi/kardex.rptdesign&id=';
//export const KARDEX = 'kardex.rptdesign&id=';

//reportes de afiliacion
//reporte kardex por num_papeleta
export const REP_KARDEX = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/afi/rep_kardex.rptdesign';
//reporte agrupado por fuerza
export const REP_FUERZA = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/afi/rep_fuerza.rptdesign';
export const REP_INSCRIPCION = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/afi/rep_inscripcion.rptdesign';
//reporte agrupado por fuerza y por anio de egreso
export const REP_EGRESO = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/afi/rep_egreso.rptdesign';

//reportes de Aportes
//reporte muestra todos los aportes de archivos ascii
export const REP_ASCII = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/apo/rep_ascii.rptdesign';
//reporte muestra los observados al cargar ascii
export const REP_OBSASCII = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/apo/rep_obsascii.rptdesign';
//reporte x
export const REP_X = 'http://'+ip_server+'/birt-viewer/frameset?__report=reportes/apo/rep_x.rptdesign';


