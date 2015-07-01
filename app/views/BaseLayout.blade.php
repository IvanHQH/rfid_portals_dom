<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Portal RFID')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Bootstrap --}}
    {{ HTML::style('assets/css/bootstrap.min.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/bootstrap-table.min.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/dashboard.css', array('media' => 'screen')) }}
    {{-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --}}
  </head>
  <body>
    {{-- Wrap all page content here --}}	
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                    
                </button>
                <img src="/img/grupo_hqh_logo.png"></td>
            </div>
            <div class="navbar-header" style="margin-left: 10px;margin-top: 10px;color: white">
                {{Auth::user()->pclient->name}}
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">  
                    @if (Auth::user()->pclient->use_mode_id == 1 ||
                        Auth::user()->pclient->use_mode_id == 4)
                        <li id="menu_dashboard"><a href="/showread">Lectura Antenas</a></li>
                    @endif
                    <li id="menu_ordenesm"><a href="/ordenesm">Lecturas</a></li>
                    <li id="menu_list_assets"><a href="/product">Activos</a></li>
                    @if (Auth::user()->pclient->use_mode_id != 3)
                    <li id="menu_list_zones"><a href="/warehouse">Zonas</a></li>                    
                    @endif
                    @if (Auth::user()->pclient->use_mode_id == 3)
                        <li id="menu_list_zones"><a href="/arching_inv_init">Arqueo</a></li>                      
                    @endif                    
                    <li id="menu_logout"><a href="/logout">Cerrar Sesión</a></li>
                </ul>
            </div>            
        </div>
    </div>    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <h3 class="sub-header">Principal</h3>
                <ul class="nav nav-sidebar">
                    @if (Auth::user()->pclient->use_mode_id == 1 ||
                        Auth::user()->pclient->use_mode_id == 4)
                        <li id="menu_dashboard"><a href="/showread">Lectura Antenas</a></li>
                    @endif
                    <li id="menu_ordenesm"><a href="/ordenesm">Lecturas</a></li>
                </ul> 
                
                <h3 class="sub-header">Catálogos</h3>
                <ul class="nav nav-sidebar">
                    <li id="menu_list_assets"><a href="/product">Activos</a></li>
                    @if (Auth::user()->pclient->use_mode_id == 1 || 
                        Auth::user()->pclient->use_mode_id == 2)
                        <li id="menu_list_warehouse"><a href="/warehouse">Zonas</a></li>  
                    @endif                        
                </ul>                                             
                @if (Auth::user()->pclient->use_mode_id == 3)
                    <h3 class="sub-header">Inventario</h3>
                    <ul class="nav nav-sidebar">
                        <li id="menu_arching"><a href="/arching_inv_init">Arqueo</a></li>
                    </ul>                      
                @endif
                <h3 class="sub-header">Usuario</h3>
                <ul class="nav nav-sidebar">
                    <li id="menu_logout"><a href="/logout">Cerrar Sesión</a></li>
                </ul>                                                                            
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="box">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>    
    {{-- jQuery (necessary for Bootstrap's JavaScript plugins) --}}
    <script src="//code.jquery.com/jquery.js"></script>
    
    <div class="modal fade" id="smwModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalTitle">Modal Title</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>    
    
    <div class="modal fade" id="smwModalTrace" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalTitle">Trazabilidad</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>       
    
    <!--@yield('javascripts')-->    
    @yield('scripts')
    {{-- Include all compiled plugins (below), or include individual files as needed --}}
    {{ HTML::script('assets/js/jquery.min.js') }}
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/bootstrap-table.js') }}
    {{ HTML::script('assets/js/functions.js') }}
  </body>
</html>