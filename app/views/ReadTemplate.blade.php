@extends ('BaseLayout')



@section ('content')
    @if (!isset($messages)) 
    <table style="width: 100%">
        <tr style="width: 100%">
            <td style="width: 33%">    
            @if (isset($step))
                @if ($step == 'start')
                <form class="navbar-form navbar-left" role="form" method="post"
                      action="/read/start_read">   
                @elseif ($step == 'show_read')
                <form class="navbar-form navbar-left" role="form" method="post"
                      action="/read/show_read">             
                @elseif ($step == 'check')
                <form class="navbar-form navbar-left" role="form" method="post"
                      action="/checkfolio">
                @endif           
                  @if ($step == 'check')
                  <div class="form-group">
                    <input name="folio" type="text" class="form-control" placeholder="Folio">
                  </div>
                  @endif
                    @if ($step == 'start')
                        <button type="submit" class="btn btn-default">Iniciar lectura</button>
                    @elseif ($step == 'show_read')
                        <button type="submit" class="btn btn-default">Ver lectura</button>      
                    @elseif ($step == 'check')
                        <button type="submit" class="btn btn-default">checar lectura</button>
                    @endif                           
                </form>
            @endif
            </td>
            <td style="width: 40%" align="center">
                @if (isset($customer))
                    {{$customer->name}}
                @endif                
            </td>
            <td style="width: 27%" align="center">Miercoles 12 de Noviembre del 2014</td>
        </tr>
    </table>    
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">  
            @if ($step == 'check')
            <table data-toggle="table" id="table-pagination" data-url="/upc/data" 
                data-pagination="true" data-search="true">           
            @else
            <table data-toggle="table" id="table-pagination" data-url="" 
                data-pagination="true" data-search="true">                       
            @endif
                <thead>
                    <tr>
                        <th data-field="name" data-align="left" data-sortable="true">Nombre</th>
                        <th data-field="upc" data-align="left" data-sortable="true">UPC</th>
                        <th data-field="quantity" data-align="center" data-sortable="true">Cantidad</th>
                    </tr>
                </thead>
            </table>            
        </div>
    </div>
    
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
