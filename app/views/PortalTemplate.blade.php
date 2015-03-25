@extends ('BaseLayout')

@section ('content')
    <!-- No message comparison -->
    @if (!isset($messages)) 
    <table style="width: 100%">
        <tr style="width: 100%">
            <td style="width: 50%">    
              @if (isset($step))              
                @if ($step == 'start')
                <form class="navbar-form navbar-left" role="form" method="post"
                      action="/read/start_read">   
                @elseif ($step == 'show_read')
                <form class="navbar-form navbar-left" role="form" method="post"
                      action="/read/show_read">             
                @elseif ($step == 'check')
                <form class="navbar-form navbar-left" role="form" method="post"
                      action="/read/checkfolio">          
                @endif
                @if ($step == 'check')
                  <div class="form-group">
                    <input name="folio" type="text" class="form-control" placeholder="Folio">
                  </div>                    
                    <select id="miselect" name="type" style="float:left" class="form-control">
                            <option>Entrada</option>
                            <option>Salida</option>
                    </select>    
                    <button type="submit" class="btn btn-default">Comparar</button>
                @endif
                @if ($step == 'start')
                    <button type="submit" class="btn btn-default">Iniciar</button>
                @elseif ($step == 'show_read')
                    <button type="submit" class="btn btn-default">Ver</button>    
                @elseif ($step == 'refresh_read')    
                    {{ Form::open(array('url' => '/read/refresh_read', 'class' => 'pull-center')) }}
                        {{ Form::hidden('_method', 'POST') }}
                        {{ Form::submit('Actualizar', array('class' => 'btn btn-default')) }}
                    {{ Form::close() }}     
                    {{ Form::open(array('url' => '/read/checkfolio', 'class' => 'pull-center')) }}
                        {{ Form::hidden('_method', 'POST') }}
                        {{ Form::submit('Comparar', array('class' => 'btn btn-default')) }}
                    {{ Form::close() }}                      
                @if ($step == 'check')
                    <!--button type="submit" class="btn btn-default">Comparar</button-->
                @endif
                @endif                           
                </form>
              @endif
            </td>
            <td style="width: 50%" align="right">
                <form role="form" method="get" action="/reset_read"> 
                    <button type="submit" class="btn btn-default">
                        Reset
                    </button>                    
                </form>        
            </td>
        </tr>
    </table>    
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">
            @if ($step == 'check' || $step == 'refresh_read')
            <table data-toggle="table" id="table-pagination" data-url="/upc/data_pending" 
                data-pagination="true" data-search="true">           
            @else
            <table data-toggle="table" id="table-pagination" data-url="" 
                data-pagination="true" data-search="true">                       
            @endif
                <thead>
                    <tr>
                        <th data-field="name" data-align="left" data-sortable="true">Nombre</th>
                        <th data-field="upc" data-align="left" data-sortable="true">UPC</th>
                        <th data-field="quantityf" data-align="center" data-sortable="true">Cant. Leeida</th>
                        @if ( Auth::user()->pclient->useMode->id == 4 )
                            <th data-field="quantity" data-align="center" data-sortable="true">Cant.</th>                        
                            <th data-field="ok" data-checkbox="true" data-sortable="true">OK</th>
                        @endif
                    </tr>
                </thead>
            </table>                           
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
