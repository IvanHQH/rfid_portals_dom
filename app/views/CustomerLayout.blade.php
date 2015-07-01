<!DOCTYPE html>
<html>
  <head>     
    <title>@yield('title', 'Cliente')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Bootstrap --}}
    {{ HTML::style('assets/css/bootstrap.min.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/bootstrap-table.min.css', array('media' => 'screen')) }}

    {{-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --}}
  </head>
  <body style="background-color: #0d0d0d">
    {{-- Wrap all page content here --}}
    @yield('content')
    {{-- jQuery (necessary for Bootstrap's JavaScript plugins) --}}
    <script src="//code.jquery.com/jquery.js"></script>
    
    <div class="modal fade" id="smwModal" tabindex="-1" role="dialog" 
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" 
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalTitle">Modal Title</h4>
                </div>
                <div class="modal-body" id="modal-body-dinamic">
                    ...
                </div>
                <div class="modal-body" id="modal-body-static">  
                    <br>
                    Autenticación
                    <div class="form-group" style="border-top: 1px solid#888; 
                        padding-top:15px;" >
                        <div class="form-group">
                            <label for="user_name">Nombre Super usuario</label>
                            <input type="text" class="form-control" id="user_name_aut" 
                                    placeholder="Nombre usuario">
                        </div>            
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input id="user_pass_aut" type="password" 
                                   class="form-control" placeholder="Contraseña" 
                                   value="" >
                        </div>              
                    </div>  
                </div>                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" 
                            data-dismiss="modal">Cerrar</button>
                    <!--button type="button" class="btn btn-primary" 
                    id="add-product-btn">Agregar</button-->
                </div>
            </div>
        </div>
    </div>            
    @yield('scripts')
    {{-- Include all compiled plugins (below), or include individual files as needed --}}
    {{ HTML::script('assets/js/jquery.min.js') }}
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/bootstrap-table.js') }}             
    
  </body>
</html>