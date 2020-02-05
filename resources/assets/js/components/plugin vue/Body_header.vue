<template>
   <main class="ml-auto"> 
  <ul class="nav navbar-nav ml-auto"> 
        <li id="valueSession" class=" d-md-down-none" style="font-size: 13px; padding-right: 10px;">
            <a class="nav-link" href="#">
                <div class="text-center" style="width: 156px;">
                    <span>Tu sesión expira en :</span> <strong id="timeout"></strong>
                </div>
            </a> 
        </li>
        <li id="clockSession" class="nav-item dropdown navItem d-md-down-none" style="font-size: 26px;">
            <a class="nav-link spring" href="#"> <i class="icon-clock"></i> </a>
        </li>

       <li class="nav-item dropdown navItem">
           <a class="nav-link" href="#" data-toggle="dropdown">
               <i class="icon-bell"></i>
               <span class="badge badge-pill badge-danger">5</span>
           </a>
           <div class="dropdown-menu dropdown-menu-right">
               <div class="dropdown-header text-center">
                   <strong>Notificaciones</strong>
               </div>
               <a class="dropdown-item" href="#">
                   <i class="fa fa-envelope-o"></i> Prestamos
                   <span class="badge badge-success">3</span>
               </a>
               <a class="dropdown-item" href="#">
                   <i class="fa fa-tasks"></i> Devoluciones
                   <span class="badge badge-danger">2</span>
               </a>
           </div>
       </li>
       <li class="nav-item dropdown navItem">
           <a class="avatar nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false"
               style="width: auto; padding: 0px 14px 0px 17px; display: inline-flex;  vertical-align: middle;">
                 <span class="d-md-down-none"
                   style="align-content: center; width: inherit;display: grid;    padding: 0 10px 0 10px;">
                   <b>{{usuario}}</b> </span>
               <img class="img-avatar" src="img/avatars/avatar.png" style="    margin: 0px !important;"
                   alt="admin@bootstrapmaster.com">
             
           </a>
           <div class="dropdown-menu dropdown-menu-right">
               <div class="dropdown-header text-center">
                   <strong>Cuenta </strong> 
               </div>
               <a class="dropdown-item" id="logoutUser" href="#" @click="logout"><i class="fa fa-lock"></i> Cerrar sesión</a>
           </div>
       </li>
        
    </ul>
  </main>
</template>

<script> 
    export default {
         props : ['user'],
        data (){
            return {
                usuario:this.user
            }
        },  
        methods : {  
            logout(event){
            event.preventDefault(); 
            $(".onloaditem").fadeIn( "fast", function() { 
                    axios.post('/logout2').then(function() {location.reload(); });
              });  
            }
            
        }  
    }
</script>
<style> 

</style>
