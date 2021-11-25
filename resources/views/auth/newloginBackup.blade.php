@extends('layouts.app')

@section('content')
<div class="materialContainer"> 
	
		@if ($errors->has('username')||$errors->has('password'))
          <div class="alert alert-danger" style="border-radius: 11px;" role="alert">
				<h4 class="alert-heading">Error</h4>
				<p>El usuario y/o contraseña son incorrectas, vuelva a intentarlo.</p> 
			</div>
		@elseif(strlen($status)>0)
		<div class="card-body">
				<div class="alert alert-danger" style="border-radius: 11px;" role="alert">
					<h4 class="alert-heading">Error</h4>
				<p>{{$status}}</p> 
				</div>
		</div> 
		@elseif(strlen($nota)>0)
		<div class="card-body">
				<div class="alert alert-warning" style="border-radius: 11px;" role="alert">
					<h4 class="alert-heading">Información</h4>
				<p>{{$nota}}</p> 
				</div>
		</div> 
		@endif
		<div style="display:none" class="card-body" id="mensaje">
				<div class="alert alert-danger" style="border-radius: 11px;" role="alert">
					<h4 class="alert-heading">Error</h4>
					<p>Debe introducir los datos requeridos</p> 
				</div>
		</div> 
	<div class="box"> 
	
		 <form id="loginform" class="form-horizontal" method="POST" action="{{ route('login') }}">
			@csrf   
			   <div class="title">LOGIN</div> 
			   <div class="input">
				  <label class="" for="name">Usuario :</label>
				  <input type="text" name="username" autofocus value="{{ old('username') }}" id="name" required>
			   </div>

			   <div class="input">
				  <label class="label" for="pass">Contraseña :</label>
				  <input type="password" name="password" value="{{ old('password') }}"  id="pass" required>
				  <span class="spin"></span>
			   </div>
			  
			   <div class="input" style="height: 26px;">
				<label>
					<input style="margin-right: 11px; height: 22px; width: 22px; top: -3px;" type="checkbox" name="remember" {{ old('remember') !== null ? 'checked' : '' }}> Recordar contraseña
				</label>
			   </div>
			   
				<div >
				   <div class="material-button"> </div>
				</div> 

				<div class="button login">
			    <button type="submit" ><span>Ingresar</span> 
	           </div>

			   <div class="title" style="height: 0; letter-spacing: normal; font-size: 15px;margin-bottom: 16px;"> 
			      <div  style="  color: black;"><b>
               			 <?php use App\Http\Controllers\AdmUserController;
               			 echo  AdmUserController::fecha_sistema_login() ?></b>
          		  </div>
	           </div>
	   </form> 
	</div> 
	 
</div>
@endsection
