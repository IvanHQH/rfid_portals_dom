@extends ('CustomerLayout')

@section('content')
<div class="container">     
    <div id="loginbox" style="margin-top:50px;" 
         class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Iniciar sesión</div>
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
                               name="password" placeholder="Contraseña" value="" >
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon-barcode"></i></span>
                        <select id="miselect" name="nameClient" 
                                class="form-control" style="float:right">
                            @foreach($clients as $client)
                                <option>{{$client->name}}</option>
                            @endforeach                               
                        </select>                            
                    </div>                                                                                                    
                    <div style="margin-top:10px" class="form-group">
                        <div class="col-md-12 control">
                            <button id="btn-login" type="btn btn-success" type="submit"
                                class="btn btn-info">Login</button> 
                        </div>
                    </div> 
                {{ Form::close() }}
                <div class="form-group">
                    <div class="col-md-12 control">
                        <div style="border-top: 1px solid#888; 
                             padding-top:15px; font-size:85%" >
                            <button class="btn btn-sm" data-toggle="modal" 
                                    data-target="#smwModal" id="add_client">
                                Agregar Cliente</button>
                            <button class="btn btn-sm" data-toggle="modal" 
                                    data-target="#smwModal" id="add_user">
                                Agregar Usuario</button>                              
                        </div>
                    </div>
                </div>                   
            </div>                     
        </div>  
    </div>        
    <div style="display: none;" id="add-client">
        <form role="form">
            <div class="form-group">
                <label for="cliente_name_new">Nombre cliente</label>
                <input type="text" class="form-control" id="client_name_new" 
                       placeholder="Nombre cliente">
            </div>            
            <div class="form-group">
                <label for="use_mode">Modo de uso</label>
                <select id="use_mode" class="form-control" style="float:right">
                    @foreach($useModes as $um)
                        <option>{{$um->name}}</option>
                    @endforeach                               
                </select>                            
            </div>           
        </form>
    </div>   
    
    <div style="display: none;" id="add-user">
        <form role="form">
            <div class="form-group">
                <label for="user_name_new">Nombre nuevo usuario</label>
                <input type="text" class="form-control" id="user_name_new" 
                       placeholder="Nombre nuevo usuario">
            </div>
            <div class="form-group">
                <label for="upc">UPC</label>
                <input type="text" class="form-control" id="upc" 
                       placeholder="UPC">
            </div>            
            <div class="form-group">
                <label for="user_password_new">Contraseña</label>
                <input id="user_password_new" type="password" class="form-control" 
                       placeholder="Contraseña" value="" >                 
            </div>           
            <div class="form-group">
                <label for="cliente_name">Cliente</label>
                <select id="client_name" class="form-control" style="float:right">
                    @foreach($clients as $client)
                        <option>{{$client->name}}</option>
                    @endforeach                               
                </select>                            
            </div>              
        </form>
    </div>    
</div>
</div>
@stop
@section('scripts')
<script>
    $(document).ready(function() {
        function prepareModal(id) {               
            if(id == 1){//Client
                $('#smwModal').find('#modalTitle').html('Agregar cliente');
                $('#smwModal').find('#modal-body-dinamic').html($('#add-client').html());    
                $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-client-btn">Agregar Cliente</button>');
            }
            if(id == 2){//User
                $('#smwModal').find('#modalTitle').html('Agregar usuario');
                $('#smwModal').find('#modal-body-dinamic').html($('#add-user').html());          
                $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-user-btn">Agregar Usuario</button>');
            }
            
            $('#smwModal').off('click', '#add-client-btn').on('click', '#add-client-btn', function() {                
                var data = {
                    pclient_name: $('#smwModal').find('#client_name_new').val(),
                    pclient_use_mode: $('#smwModal').find('#use_mode').val(),
                    user_name: $('#smwModal').find('#user_name_aut').val(),
                    user_password: $('#smwModal').find('#user_pass_aut').val(),
                }
                $.ajax({
                    type: "POST",
                    url: '{{ URL::to('/pclient') }}',
                    data: data,
                    success: function(data, textStatus, jqXHR) {                        
                        if(data.success == true){
                            $('#smwModal').modal('hide');
                            dt.fnDraw();                            
                        }
                        else{
                            alert(data.errors);
                        }                        
                    },
                    dataType: 'json',
                });
            });             
            
            $('#smwModal').off('click', '#add-user-btn').on('click', '#add-user-btn', function() {                
                var data = {
                    user_name_new: $('#smwModal').find('#user_name_new').val(),
                    user_password_new: $('#smwModal').find('#user_password_new').val(),
                    client_name: $('#smwModal').find('#client_name').val(),
                    upc: $('#smwModal').find('#upc').val(),
                    user_name_aut: $('#smwModal').find('#user_name_aut').val(),
                    user_password_aut: $('#smwModal').find('#user_pass_aut').val(),
                }
                $.ajax({
                    type: "POST",
                    url: '{{ URL::to('/user') }}',
                    data: data,
                    success: function(data, textStatus, jqXHR) {                        
                        if(data.success == true){
                            $('#smwModal').modal('hide');
                            dt.fnDraw();                            
                        }
                        else{
                            alert(data.errors);
                        }                        
                    },
                    dataType: 'json',
                });
            });            
            
        }
        
        $('#add_client').on('click', function() {     
            prepareModal(1);
        });
        
        $('#add_user').on('click', function() {     
            prepareModal(2);
        });        
    });  
</script>  
@stop
