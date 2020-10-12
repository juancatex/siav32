<template>
<main class="main">
    <div class="breadcrumb titmodulo">RRHH > Biométrico</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header titcard">
                        Biométrico
                    </div>
                    <div class="card-body text-center">
                        <button class="btn btn-primary">Ver asistencia</button>
                        <button class="btn btn-primary">Ver Empleados</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header titcard">
                        Asistencia
                    </div>
                    <div class="card-body">
                         
                         
                    </div>
                </div>
          
                <div class="card">
                    <div class="card-header titcard">
                        Empleados
                    </div>
                    <div class="card-body">
                         
                         
                    </div>
                </div>
            </div>
 

        </div>
    </div>
</main>
</template>

<script> 

export default {
    data(){ return { 
        attendance:[], 
        users:[], 
        databio:[], 
        idbio:'', 
    }},

    methods:{ 
        getAsistenciaBiometrico(){
            axios.get('/rrh_biometrico/getUsers').then(response=>{
                
                this.attendance=response.data.attendance;
                this.users=response.data.users;
                this.databio=response.data.data; 
                this.idbio=response.data.bio;
                console.log(response.data) 
                swal.close();
                 
            });
        },
        getAttendanceWithId(){
            var asistencia= _.reduce(this.attendance,(result, value, key)=>{
                         if(value.id==this.idbio){
                            if(!_.isArray(result)){ result = [];  }
                           result.push(value);
                        }
                        return result; 
                    }, {});
                    console.log(asistencia)
        } 
        
    },

    mounted(){
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