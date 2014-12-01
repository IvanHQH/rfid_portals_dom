<!DOCTYPE html>
<html>
  <head>
    <!--style>
        body {
            display: block;
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto;
            background-color:#0d0d0d;
            border-right: 1px solid #eee;
            background: radial-gradient(black 15%, transparent 16%) 0 0,
            radial-gradient(black 15%, transparent 16%) 8px 8px,
            radial-gradient(rgba(255,255,255,.1) 15%, transparent 20%) 0 1px,
            radial-gradient(rgba(255,255,255,.1) 15%, transparent 20%) 8px 9px;
            background-color: #0d0d0d;
            background-size: 16px 16px;
        }
    </style-->      
    <title>@yield('title', 'Cliente')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Bootstrap --}}
    {{ HTML::style('assets/css/bootstrap.min.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/bootstrap-table.min.css', array('media' => 'screen')) }}

    {{-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --}}
    <!--[if lt IE 9]>
        {{ HTML::script('assets/js/html5shiv.js') }}
        {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->
  </head>
  <body style="background-color: #0d0d0d">
    {{-- Wrap all page content here --}}
    @yield('content')
    {{-- jQuery (necessary for Bootstrap's JavaScript plugins) --}}
    <script src="//code.jquery.com/jquery.js"></script>
    {{-- Include all compiled plugins (below), or include individual files as needed --}}
    {{ HTML::script('assets/js/jquery.min.js') }}
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/bootstrap-table.js') }}
    @yield('scripts')
  </body>
</html>