@extends ('BaseLayout')

@section ('content')
    <!-- No message comparison -->
    <div class="col-lg-12">
            <h3 class="page-header">Lectura Antenas</h1>
    </div>			    
    @if (!isset($messages))  
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-6">
                        @if (isset($step))              
                          @if ($step == 'start')
                              @if (Auth::user()->pclient->use_mode_id == 4)
                              <form class="navbar-form navbar-left" role="form" 
                                    method="post" action="/read/start_read_v4" >                       
                              @endif
                          @elseif ($step == 'show_read')
                          <form class="navbar-form navbar-left" role="form" method="post"
                                action="/read/show_read">             
                          @elseif ($step == 'check')
                          <form class="navbar-form navbar-left" role="form" method="post"
                                action="/read/checkfolio">          
                          @endif
                          <!--@if ($step == 'check')                           
                              <select id="miselect" name="type" style="float:left" class="form-control">
                                      <option>Entrada</option>
                                      <option>Salida</option>
                              </select>    
                          <button type="submit" class="btn btn-default">Comparar</button>
                          @endif-->
                          @if ($step == 'start')
                              @if ( Auth::user()->pclient->use_mode_id == 4 )                              
                              <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-7">
                                        <input name="folio" type="text" class="form-control" 
                                             placeholder="Folio" value="GREEN_RE005267_1">   
                                    </div> 
                                    <div class="col-lg-5">
                                        <select id="miselect" name="type" style="float:left" class="form-control">
                                                <option>Entrada</option>
                                                <option>Salida</option>
                                        </select>                                          
                                    </div> 
                                </div> 
                              </div>   
                              @endif                              
                            <button type="submit" class="btn btn-default">Iniciar</button>
                          @elseif ($step == 'show_read')
                              <button type="submit" class="btn btn-default">Ver</button>    
                          @elseif ($step == 'refresh_read')      
                              {{ Form::open(array('url' => '/read/check_folio', 'class' => 'pull-center')) }}
                                  {{ Form::hidden('_method', 'POST') }}
                                  {{ Form::submit('Comparar', array('class' => 'btn btn-default')) }}
                              {{ Form::close() }}                       
                          @endif                           
                          </form>
                        @endif
                    </div>
                    <div class="col-lg-5"> 
                    </div>      
                    <div class="col-lg-1">
                         <form role="form" method="get" action="/reset_read"> 
                            <button type="submit" class="btn btn-default">
                                Reset
                            </button>                    
                        </form>  
                    </div>                     
                </div>
            </div>
                <div class="panel-body"> 
            @if ($step == 'check' || $step == 'refresh_read')
            <table data-toggle="table" id="table-pagination" data-url="/upc/data_pending" 
                data-pagination="true" data-search="true" data-show-refresh="true">           
            @else
            <table data-toggle="table" id="table-pagination" data-url="" 
                data-pagination="true" data-search="true" data-show-refresh="true">                       
            @endif
                <thead>
                    <tr>
                        <th data-field="name" data-align="left" data-sortable="true">Nombre</th>
                        <th data-field="upc" data-align="left" data-sortable="true">UPC</th>
                        <th data-field="quantityf" data-align="center" data-sortable="true">Cant. Leeida</th>
                        @if ( Auth::user()->pclient->use_mode_id == 4 )
                            <th data-field="quantity" data-align="center" data-sortable="true">Cant.</th>                        
                            <th data-field="ok" data-checkbox="true" data-sortable="true">OK</th>
                        @endif
                    </tr>
                </thead>
            </table>                           
		</div>                           
	</div>				
</div>
    <!-- Message comparison -->
    @else
    <div class="panel panel-default">
      <div class="panel-heading">Lectura guardada</div>
      <div class="panel-body" style="background-color: #F9F8F8">
        <p>Resultado de comparación de tags leeidas y folio de orden de entrada o salida :</p>
      </div>          
      <ul class="list-group">  
        @foreach($messages as $message)
            <li class="list-group-item">{{$message}}</li>
        @endforeach
      </ul>
    </div>    
    @endif
@stop

@section('scripts')
<script>
    /*var myVar = setInterval(function () {myTimer()}, 1000);
    function myTimer() {
        //alert("ok");
        
    }*/
    /*var table = $('#table_reads').DataTable( {
        ajax: "data.json"
    } );*/
$(document).ready(function() {
   /* var table = $('#table_reads').DataTable( {
        ajax: "data.json"    
    } );*/
    setInterval( function () {
        //alert("ok");
        //table.ajax.reload();
        $("#refresh").click();
        //var refresh = document.getElementsByTagName('refresh');
        //refresh[0].click();
    }, 1000 );   
    /*
    $("#get_csv").click(function(){
        var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
        window.location="/products/csv"+"?"+$.param(oParams);        
        window.location="/download_xls";
    });*/    
});   
</script>  
@stop
