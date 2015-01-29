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
    {{-- Include all compiled plugins (below), or include individual files as needed --}}
    {{ HTML::script('assets/js/jquery.min.js') }}
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/bootstrap-table.js') }}
    @yield('scripts')
  </body>
</html>