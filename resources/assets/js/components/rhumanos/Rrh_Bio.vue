<template>
<main class="main">
    <div class="breadcrumb titmodulo">RRHH > Operaciones</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header titcard">
                        Biométrico
                    </div>
                    <div class="card-body text-center">
                        <button class="btn btn-primary">Ver asistencia</button>
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
            </div>
 

        </div>
    </div>
</main>
</template>

<script> 

export default {
    data(){ return {
       
        arrayPlanillas:[], 
    }},

    methods:{
        
         

        listaPlanillas(){
            axios.get('/rrh_biometrico/getUsers').then(response=>{
                
            });
        } 
        
    },

    mounted(){
      
    }
}
</script>