<template>
            <main class="main">  
                <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="primarymodalview" >
                     <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn"> 
                                <div class="modal-header"> 
                                    <h4 class="modal-title" id="visortitulo"></h4>
                                    <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarmodal('primarymodalview')"><span aria-hidden="true">×</span></button>
                                </div>  
                                <div class="modal-body"> 
                                   <div class="col-md-12">
                                     <div class="row contenedor" id="visor">  
                                    </div>
                                    </div> 
                                </div>  
                                <div class="modal-footer">  
                                <button type="button" class="btn btn-secondary" @click="cerrarmodal('primarymodalview')">Volver</button>   
                                </div>    
                        </div>
                    </div>
                </div>
  
        </main>
</template>

<script>
  
    export default { 
        data (){
            return {
                arrayPermisos:{Nuevo:0,Edición:0},
               classModal3:null 
            }
        } ,
        methods : { 
            
            cerrarmodal(id){ 
                this.classModal3.closeModal(id);
                this.$emit('cerrarvue');
            },
            showVue(id,nameperfil,tipoc,cod,namep){ 
                let me=this;
             $('.modal-title[id="visortitulo"]').text(nameperfil); 
              try{
                _pl._mf362353_225(id,'visor',tipoc,cod,namep);
                this.classModal3.openModal('primarymodalview'); 
              }catch(error){   
                    console.error(error.stack);
                    swal("¡Existe un error en la compilacion de la formula!",error.name + ' <b>:</b><span style="font-weight: 400;"> ' + error.message+'</span>', "error").then((result) => {
                          me.$emit('cerrarvue');
                      })  
                    $(".swal2-modal").css('z-index', '2000'); 
                    $(".swal2-container").css('z-index', '2000');
                    
              } 
            } 
        } ,
        mounted() {
             this.classModal3=new _pl.Modals();
             this.classModal3.addModal('primarymodalview');  
        }
    }
</script>
 