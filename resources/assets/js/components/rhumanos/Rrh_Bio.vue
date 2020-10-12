<template>
<main class="main">
    <div class="breadcrumb titmodulo">RRHH > Biométrico</div>
    <div class="container-fluid">
        <div class="row">
              
          <div class="col-md-4" v-if="asistencia.length>0">
                <div class="card">
                    <div class="card-header titcard">
                        Total asistencia  : <br>{{getnameuser()}}
                    </div>
                    <div class="card-body">
                         

                         <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th> 
                                    <th>Hora</th>
                                    <th>Modo</th>
                                    <th>Tipo</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(dato, keyin) in asistencia" :key="keyin">
                                   
                                    <td v-text="dato.date"></td>
                                    <td v-text="dato.time"></td>
                                    <td v-text="dato.state"></td>
                                    <td v-text="dato.type"></td>  
                                </tr>                                
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
              <div class="col-md-4" v-if="asistencianow.length>0">
                <div class="card">
                    <div class="card-header titcard">
                        Asistencia en fecha :  {{getdateBio()}} <br>{{getnameuser()}}
                    </div>
                    <div class="card-body">
                         

                         <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th> 
                                    <th>Hora</th>
                                    <th>Modo</th>
                                    <th>Tipo</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(dato, keyin) in asistencianow" :key="keyin">
                                   
                                    <td v-text="dato.date"></td>
                                    <td v-text="dato.time"></td>
                                    <td v-text="dato.state"></td>
                                    <td v-text="dato.type"></td>  
                                </tr>                                
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
            </div>
<div class="row">
             <div class="col-md-6" v-if="users.length>0">
                <div class="card">
                    <div class="card-header titcard">
                        Empleados registrados en el biometrico
                    </div>
                    <div class="card-body">
                       <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>No.</th> 
                                    <th>Id biometrico</th> 
                                    <th>Nombre</th> 
                                    <th>Rol</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(dato, keyin) in users" :key="keyin"> 
                                    <td v-text="keyin+1"></td>
                                    <td v-text="dato.userid"></td>
                                    <td v-text="dato.name"></td>
                                    <td v-text="dato.role"></td> 
                                </tr>                                
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>

            <div class="col-md-6" v-if="usersnow.length>0">
                <div class="card">
                    <div class="card-header titcard">
                        Asistencia de Empleados a fecha:  {{getdateBio()}}
                    </div>
                    <div class="card-body">
                       <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>No.</th>  
                                    <th>Nombre</th> 
                                    <th>Rol</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(dato, keyin) in usersnow" :key="keyin"> 
                                    <td v-text="keyin+1"></td> 
                                    <td v-text="dato.name"></td>
                                    <td v-text="dato.role"></td> 
                                </tr>                                
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
 

        </div>
    </div>
</main>
</template>

<script> 

export default {
     props: ['idmodulo','object','idventanamodulo'],
    data(){ return { 
        arrayPermisos: { 
                    Total_Asistencias: 0,
                    Asistencia_Hoy: 0,
                    Lista_Empleados_Registrados: 0,
                    Lista_Empleados_Asistencia_Hoy: 0 
                }, 
                arrayPermisosIn: {},
        attendance:[], 
        users:[], 
        usersnow:[], 
        databio:[], 
        asistencia:[], 
        asistencianow:[], 
        idbio:'', 
    }},

    methods:{
        getnameuser(){
            let me=this;
           var user= _.find(this.users, function(o) { return o.userid==me.idbio; });
           return user.name;
        },
        getdateBio() {
            let date = new Date(this.databio.fecha);
            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear(); 
            return day+'-'+month+'-'+year;
        },
        getAsistenciaBiometrico(){
            axios.get('/rrh_biometrico/getUsers').then(response=>{
                
                this.attendance=response.data.attendance; 
                this.databio=response.data.data; 
                this.idbio=response.data.bio; 
                swal.close();
                if(this.check('Lista_Empleados_Registrados')){this.users=response.data.users;}
                if(this.check('Total_Asistencias')){this.getAttendanceWithId();}
                if(this.check('Asistencia_Hoy')){this.getAttendanceWithNow();}
                if(this.check('Lista_Empleados_Asistencia_Hoy')){this.getUsersNow();}
                  
            });
        },
        getAttendanceWithId(){
            this.asistencia= _.reduce(this.attendance,(result, value, key)=>{
                         if (value.id == this.idbio) {
                             if (!_.isArray(result)) {  result = []; }
                             result.push(value);
                         }

                        return result; 
                    }, {}); 
        }, 
        getAttendanceWithNow(){
             let date = new Date(this.databio.fecha);
            this.asistencianow= _.reduce(this.attendance,(result, value, key)=>{
                        var dateatten = new Date(value.timestamp);
                         if (value.id == this.idbio&&(dateatten.getDate()==date.getDate()&&dateatten.getMonth()==date.getMonth()&&dateatten.getFullYear()==date.getFullYear())) {
                             if (!_.isArray(result)) {  result = []; }
                             result.push(value);
                         }
                        return result; 
                    }, {}); 
        },
         getUsersNow(){
             let me=this;
             let date = new Date(this.databio.fecha);
            var out= _.reduce(this.attendance,(result, value, key)=>{
                        var dateatten = new Date(value.timestamp);
                         if ((dateatten.getDate()==date.getDate()&&dateatten.getMonth()==date.getMonth()&&dateatten.getFullYear()==date.getFullYear())) {
                             if (!_.isArray(result)) {  result = []; }
                               result.push(value.id);
                         }
                        return result; 
                    }, {}); 
              out= _.uniq(out); 
              var usersout=[];
             _.forEach(out, function(value) {  
                var user= _.find(me.users, function(o) { return o.userid==value; }); 
                usersout.push(user);
            }); 
           
            this.usersnow=_.orderBy(usersout, ['name'], ['asc']);
        } ,
         getPermisos() {
                var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo + '&idventanamodulo=' + this.idventanamodulo;
                let me = this; 
                axios.get(url).then(function (response) {
                    me.arrayPermisosIn=[];
                    if(response.data.datapermiso.length>0){
                        var respuesta=response.data.datapermiso[0].permisos; 
                        me.arrayPermisosIn = JSON.parse((respuesta));
                    } 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            check(n){
             return _pl.validatePermission(this.arrayPermisosIn,n);
          }

        
    },

    mounted(){
        this.asistencia=[];
        this.asistencianow=[];
        this.getPermisos();
       swal({
            title: "Obteniendo datos del biometrico", 
            allowOutsideClick: () => false,
            allowEscapeKey:() => false, 
            onOpen: function() { 
                swal.showLoading() ; 
            }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 
        this.getAsistenciaBiometrico();
        
        
    }
}
</script>