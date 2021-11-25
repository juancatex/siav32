@extends('layouts.login')

@section('content')
  
<form class="login" method="POST" action="{{ route('login') }}" style="text-align: center;">
            @csrf  
				@if ($errors->has('username')||$errors->has('password'))
          
		        	<div   style="border-radius: 11px;
                        margin: 0px;
                        background-color: #ff000091;
                        padding: 14px;
                        color: white;
                        margin-bottom: 44px;" > 
                        <p style="margin: 0;padding: 0;">El usuario y/o contraseña son incorrectas, vuelva a intentarlo.</p>
                       
                    </div>
		@elseif(strlen($status)>0) 
		<div   style="border-radius: 11px;
                        margin: 0px;
                        background-color: #ff000091;
                        padding: 14px;
                        color: white;
                        margin-bottom: 44px;" > 
                        <p style="margin: 0;padding: 0;">{{$status}}</p>
                       
                    </div>
		@elseif(strlen($nota)>0) 
		<div   style="border-radius: 11px;
                        margin: 0px;
                        background-color: #ff000091;
                        padding: 14px;
                        color: white;
                        margin-bottom: 44px;" > 
                        <p style="margin: 0;padding: 0;">{{$nota}}</p>
                       
                    </div> 
		@elseif($errors->any())
                    <div   style="border-radius: 11px;
                        margin: 0px;
                        background-color: #ff000091;
                        padding: 14px;
                        color: white;
                        margin-bottom: 44px;" >
                        @foreach ($errors->all() as $error)
                        <p style="margin: 0;padding: 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
		 
                <img src="img/favicon.png" alt="UEFAB" width="65%">
                <p class="title">SIA ASCINALSS</p>
                <input type="text" placeholder="Usuario" name="username" autofocus/>
                <i class="fa fa-user"></i>
                <input type="password" placeholder="Contraseña" name="password"/>
                <i class="fa fa-key"></i>
				
                <button>
                <i class="spinner"></i>
                <span class="state">Ingresar</span>
                </button>
            </form>
			<div  style="color: white;
    text-align: center;
	font-size: large;
    margin-top: 23px;"><b>
               			 <?php use App\Http\Controllers\AdmUserController;
               			 echo  AdmUserController::fecha_sistema_login() ?></b>
          		  </div>
@endsection
