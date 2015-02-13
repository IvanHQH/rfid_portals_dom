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
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">                                        
                    <li id="menu_dashboard"><a href="/showread">Lectura Portal</a></li>
                    <li id="menu_ordenesm"><a href="/ordenesm">Lecturas</a></li>
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
                    <li id="menu_dashboard"><a href="/showread">Lectura Portal</a></li>
                    <li id="menu_ordenesm"><a href="/ordenesm">Lecturas</a></li>
                </ul>              
                <h3 class="sub-header">Antenas</h3>
                <ul class="nav nav-sidebar" style="padding-left: 20px">
                    <li id="antena_1">
                        <button type="button" class="btn btn-default">
                        <img src="/img/antena_rfid_mini.png"/>1</button>
                    </li><br />
                    <li id="antena_2">
                        <button type="button" class="btn btn-default">
                        <img src="/img/antena_rfid_mini.png"/>2</button>
                    </li>                                        
                </ul>
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
    @yield('scripts')
    {{-- Include all compiled plugins (below), or include individual files as needed --}}
    {{ HTML::script('assets/js/jquery.min.js') }}
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/bootstrap-table.js') }}
  </body>
</html>