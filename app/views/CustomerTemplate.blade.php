@extends ('CustomerLayout')

@section('content')
<div class="container">     
    <div id="loginbox" style="margin-top:50px;" 
         class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Ingresa tus datos chavo!!!</div>
            </div>
            <div style="padding-top:30px" class="panel-body" >
                <div style="display:none" id="login-alert" class="alert 
                     alert-danger col-sm-12"></div>
                {{Form::open(array('url' => 'login',
                            'method'=>'post','class'=>'form-horizontal'))}}
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i 
                                class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" 
                               name="name" value="" placeholder="Nombre">                                        
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon 
                                glyphicon-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" 
                               name="password" placeholder="Contraseña" value="">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon-barcode"></i></span>
                        <select id="miselect" name="nameCustomer" 
                                class="form-control" style="float:right">
                            @foreach($customers as $customer)
                                <option>{{$customer->name}}</option>
                            @endforeach                               
                        </select>                            
                    </div>                        
                    <div style="margin-top:10px" class="form-group">
                        <div class="col-md-12 control">
                            <button id="btn-login" type="btn btn-success" type="submit"
                                class="btn btn-info">Login</button> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 control">
                            <div style="border-top: 1px solid#888; 
                                 padding-top:15px; font-size:85%" >
                                ¿No existe la Empresa?
                            <a href="#" onClick="$('#loginbox').hide(); 
                                $('#signupbox').show()">
                                Alta Empresa
                            </a>
                            </div>
                        </div>
                    </div>    
                {{ Form::close() }}
            </div>                     
        </div>  
    </div>        
<div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 
     col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Sign Up</div>
            <div style="float:right; font-size: 85%; position: relative; 
                 top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); 
                     $('#loginbox').show()">Sign In</a></div>
        </div>  
        <div class="panel-body" >
            <form id="signupform" class="form-horizontal" role="form" method="post" 
                  action="/customer">
                <div id="signupalert" style="display:none" class="alert alert-danger">
                    <p>Error:</p>
                    <span></span></div>
                <div class="form-group">
                    <label for="firstname" class="col-md-3 control-label">Empresa</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="customerName" 
                               placeholder="Empresa">
                    </div></div>
                <div class="form-group">
                    <label for="icode" class="col-md-3 control-label">Logotipo</label>
                    <div class="col-md-9">
                        <input class="form-control" type="file" name="Logo" id="archivo">
                    </div></div>               
                <div class="form-group">
                    <!-- Button -->                                        
                    <div class="col-md-offset-3 col-md-9">
                        <button id="btn-signup" type="btn btn-success" type="submit"
                            class="btn btn-info">Sign Up</button>                        
                    </div></div>
            </form>
        </div>
    </div>
</div> 
</div>
@stop
@section('scripts')
<script>
    $(".dropdown-menu li a").click(function(){
        //echo algo;
        //$("#select_company").text($(this).text());
        $("#select_company").val("plomeria selecta");
        //$("#select_company").val($(this.id()));
        //echo "hola";die();
        //window.location="algo";
    });  
    $("#miselect").change(function() {
     //alert("Han cambiado mi valor"); 
    });  
</script>  
@stop
