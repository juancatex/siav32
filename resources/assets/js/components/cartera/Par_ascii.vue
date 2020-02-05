<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
      

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Cobranza Manual</div>
                    <div class="card-body">
                        <div class="form-group row" style="justify-content: flex-end;">


                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Cobranza Por ASCII</div>
                    <div class="card-body">
                        <div class="form-group row" style="justify-content: flex-end;">


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

        <div class="modal fade " tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true"
            id="primarymodal">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalOneLabel">titulo modal </h4>
                        <button type="button" class="close" aria-hidden="true" aria-label="Close"
                            @click="classModal.closeModal('primarymodal')"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body-modalPlugin">

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            @click="classModal.closeModal('primarymodal')">Cerrar</button>
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
               classModal:null,
               
                
            }
        },  
        methods : { 
              
        },
        mounted() {
            this.classModal=new _pl.Modals();
            this.classModal.addModal('primarymodal'); 
           
        }
    }
</script>
<style> 
 
      
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
  

::-webkit-scrollbar {
  width: 6px;
  border-radius: 10px;
}
::-webkit-scrollbar-track {
  background: transparent;  
}
::-webkit-scrollbar-thumb {
  background: #2186aab0; 
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #276f8a; 
}
</style>
