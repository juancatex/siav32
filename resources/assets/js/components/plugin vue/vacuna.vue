<template>
            <main class="main"> 
 <div class="breadcrumb titmodulo">Global > Vacuna</div>
    <div class="container-fluid">
              <div class="card">
  

                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Credencial Vacuna Covid 
                    </div> 
                    <div class="card-body">
                   
                    <div class="form-group row">
                        <div class="col-lg-12 row">
                          
                                                <div class="custom-file col-md-8">
                                                    <input ref="files"  @change="valuefotos" accept=".pdf" type="file" class="custom-file-input" id="customFileLang"  lang="es">
                                                    <label class="custom-file-label" for="customFileLang">{{file?' Pdf seleccionados':'Seleccionar Archivo PDF'}}</label>
                                                </div>
                                                <div class="col-md-4" v-if="file">
                                                    <button class="btn btn-outline-success active" type="button"
                                                @click="cargarimagenes" >Cargar Pdf</button>
                                                </div>
                                
                        </div> 
                    </div>
 
                    </div>
             </div>
             <div class="col-lg-12 row">
<div class="card col-lg-6" v-if="imagen"> 
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Credencial Anverso 
                    </div> 
                    <div class="card-body"> 
                    <div class="form-group row"> 
                        <div class="col-lg-12 row ">
                          <div id="imgbene"></div>
                        </div>
                    </div>
 
                    </div>
              </div>
               <div class="card col-lg-6" v-if="imagen"> 
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Credencial Reverso
                    </div> 
                    <div class="card-body"> 
                    <div class="form-group row"> 
                        <div class="col-lg-12 row ">
                          <div id="imgbene2"></div>
                        </div>
                    </div>
 
                    </div>
             </div>
             <div class="col-lg-12" v-if="nuevafotoatraz&&nuevafotodelante">
               <button  type="button" @click="generarcrdencial()" class="btn btn-warning btn-sm">
                                        <i class="icon-camera"> Generar Credencial</i>
                 </button> 
             </div>
             </div>

              
               
            </div>



     <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="credencial">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Carnet de vacuna</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.closeModal('credencial')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body-plandepagos"> 
            <iframe  name="planout" id="planout" style="width:100%;height:100%;"></iframe>  
          </div>

           <div class="modal-footer">  
               <button class="btn btn-secondary" type="button"   @click="classModal.closeModal('credencial')">cerrar</button>
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
                 file: '',
                 nuevafotodelante: '',
                 nuevafotoatraz: '',
                 imagen:''
            }
        },
       
        methods:{
          generarcrdencial(){
            let me=this;
            this.classModal.closeModal('credencial');
                swal({
                    title: "Generando reporte",
                    allowOutsideClick: () => false,
                    allowEscapeKey: () => false,
                    onOpen: function() {
                    swal.showLoading();
                    }
                });
 
                axios.post('/vacunafinalin',{ 
                    'foto':me.nuevafotodelante,
                    'foto2':me.nuevafotoatraz
                }).then(function (response) {
                    _pl._vvp2521_cr02_vacuna(response.data.anverso,response.data.reverso,()=>{
                                swal.close()
                                me.classModal.openModal('credencial');
                     });
                });

                
          },
          valuefotos(event){
               $('#imgbene').html(''); 
               $('#imgbene2').html(''); 
               this.imagen='';
            this.nuevafotodelante='';
            this.nuevafotoatraz='';
                this.file = this.$refs.files.files[0]; 
            },
          cargarimagenes(){ 
            this.imagen='';
            this.nuevafotodelante='';
            this.nuevafotoatraz='';
            $('#imgbene').html(''); 
            $('#imgbene2').html(''); 
            swal({
                title: "Obteniendo imagen",
                allowOutsideClick: () => false,
                allowEscapeKey: () => false,
                onOpen: function() {
                swal.showLoading();
                }
            }); 
                let me = this;
               let formData = new FormData();
                formData.append('file', this.file);
                
                axios.post('/pdftoimage', formData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) { 
                    me.$refs.files.value=null;
                     me.file= '';
                      me.imagen=response.data.vacuna;
                       
                   swal("¡Se cargaron los archivos correctamente!", "", "success").then(()=>{
                     me.initimage();
                       me.initimage2();
                   });
                        
                }).catch(function (error) {
                    me.$refs.files.value=null;
                    me.file= '';
                    console.log(error);
                   swal("¡Error!","", "error");
                });

               
                
            },
            initimage(){
              let me=this;
              this.nuevafotodelante='';  
              $('#imgbene').html(''); 
               $(`<img src='${this.imagen}' id="idimagen" style="max-width: 100%;margin: auto;border: 1px solid gray;">
                    <div style="padding: 9px;" id="fotoimagenbenecrood">
                    <button  type="button" class="btn btn-success" id="btnselecctbene">Seleccionar</button> 
                    <button  type="button" class="btn btn-danger" id="btncancelbene">Cancelar</button>
                    </div>`).appendTo('#imgbene');
                     $("#idimagen").cropper({ 
                      viewMode: 1,
                      dragMode: 'move',
                      autoCropArea: 1,
                      restore: false, 
                      guides: false, 
                      rotatable: false,
                      multiple: false
                      });  
                    $("#btncancelbene").click(function(){
                     $('#idimagen').cropper('destroy');  
                     me.initimage();
                    }); 

                    $("#btnselecctbene").click(function(){ 
                         me.nuevafotodelante=$("#idimagen").cropper('getCroppedCanvas',{
         imageSmoothingEnabled: true,
         imageSmoothingQuality: 'high' }).toDataURL('image/jpeg', 1); 
                        $('#idimagen').cropper('destroy');
                        $("#idimagen").attr("src",me.nuevafotodelante); 
                        // $('#fotoimagenbenecrood').html(''); 
                    }); 
            },
            initimage2(){
              let me=this; 
              this.nuevafotoatraz=''; 
              $('#imgbene2').html(''); 
               $(`<img src='${this.imagen}' id="idimagen2" style="max-width: 100%;margin: auto;border: 1px solid gray;">
                    <div style="padding: 9px;" id="fotoimagenbenecrood2">
                    <button  type="button" class="btn btn-success" id="btnselecctbene2">Seleccionar</button> 
                    <button  type="button" class="btn btn-danger" id="btncancelbene2">Cancelar</button>
                    </div>`).appendTo('#imgbene2');
                     $("#idimagen2").cropper({ 
                      viewMode: 1,
                      dragMode: 'move',
                      autoCropArea: 1,
                      restore: false, 
                      guides: false, 
                      rotatable: false,
                      multiple: false
                      });  
                    $("#btncancelbene2").click(function(){
                     $('#idimagen2').cropper('destroy'); 
                     me.initimage2();

                    }); 

                    $("#btnselecctbene2").click(function(){ 
                         me.nuevafotoatraz=$("#idimagen2").cropper('getCroppedCanvas',{
         imageSmoothingEnabled: true,
         imageSmoothingQuality: 'high' }).toDataURL('image/jpeg', 1); 
                        $('#idimagen2').cropper('destroy');
                        $("#idimagen2").attr("src",me.nuevafotoatraz); 
                        // $('#fotoimagenbenecrood2').html(''); 
                    }); 
            }
             
        },
        mounted() {
            moment.locale('es-us'); 
            this.imagen='';
            $('#imgbene').html(''); 
            $('#imgbene2').html(''); 
            this.classModal = new _pl.Modals();
            this.classModal.addModal("credencial");  
            }
 }
 </script>
<style> 
.cuerpo{
      height: 100%;
    vertical-align: middle;
    display: flex;
}
.stage {
      height: 101px;
    width: 100%;
    margin: auto;
    position: relative;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    transform-style: preserve-3d;
}

.layer {
  width: 100%;
  height: 100%;
  position: absolute;
  transform-style: preserve-3d;
  animation: ಠ_ಠ 5s infinite alternate ease-in-out -7.5s;
  animation-fill-mode: forwards;
  transform: rotateY(15deg) rotateX(15deg) translateZ(0);
}

.layer:after {
  font: 100px/0.65 'Pacifico', 'Kaushan Script', Futura, 'Roboto', 'Trebuchet MS', Helvetica, sans-serif;
  content: 'Sistema ASCINALSS';
  white-space: pre;
  text-align: center;
  height: 100%;
  width: 100%;
  position: absolute;
   
  color: whitesmoke;
  letter-spacing: -2px;
  text-shadow: 4px 0 10px rgba(0, 0, 0, 0.13);
}

.layer:nth-child(1):after {
  transform: translateZ(0px);
}

.layer:nth-child(2):after {
  transform: translateZ(-1.5px);
}

.layer:nth-child(3):after {
  transform: translateZ(-3px);
}

.layer:nth-child(4):after {
  transform: translateZ(-4.5px);
}

.layer:nth-child(5):after {
  transform: translateZ(-6px);
}

.layer:nth-child(6):after {
  transform: translateZ(-7.5px);
}

.layer:nth-child(7):after {
  transform: translateZ(-9px);
}

.layer:nth-child(8):after {
  transform: translateZ(-10.5px);
}

.layer:nth-child(9):after {
  transform: translateZ(-12px);
}

.layer:nth-child(10):after {
  transform: translateZ(-13.5px);
}

.layer:nth-child(11):after {
  transform: translateZ(-15px);
}

.layer:nth-child(12):after {
  transform: translateZ(-16.5px);
}

.layer:nth-child(13):after {
  transform: translateZ(-18px);
}

.layer:nth-child(14):after {
  transform: translateZ(-19.5px);
}

.layer:nth-child(15):after {
  transform: translateZ(-21px);
}

.layer:nth-child(16):after {
  transform: translateZ(-22.5px);
}

.layer:nth-child(17):after {
  transform: translateZ(-24px);
}

.layer:nth-child(18):after {
  transform: translateZ(-25.5px);
}

.layer:nth-child(19):after {
  transform: translateZ(-27px);
}

.layer:nth-child(20):after {
  transform: translateZ(-28.5px);
}

.layer:nth-child(n+10):after {
  -webkit-text-stroke: 3px rgba(0, 0, 0, 0.25);
}

 

.layer:nth-child(n+12):after {
  -webkit-text-stroke: 6px #0077ea;
}

.layer:last-child:after {
  -webkit-text-stroke: 10px rgba(0, 0, 0, 0.1);
}

.layer:first-child:after {
  color: #fff;
  text-shadow: none;
}

@keyframes ಠ_ಠ {
  100% {
    transform: rotateY(-25deg) rotateX(-20deg);
  }
}
@media (max-width: 768px) {
  .layer:after {
  font-size: 40px;
}
}
@media (min-width: 768px) {
  .layer:after {
  font-size: 50px;
}
}

@media (min-width: 992px) {
  .layer:after {
  font-size: 70px;
}
}

@media (min-width: 1200px) {
  .layer:after {
  font-size: 103px;
}
}
</style>
