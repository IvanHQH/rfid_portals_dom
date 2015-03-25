@extends ('BaseLayout')

@section ('content')     
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">  
            <div class="alert alert-success" id="events-result" data-es="Aquí se muestra el resultado del evento">
                Lecturas
            </div>
            <table id="events-table" data-toggle="table" 
                data-pagination="true" data-search="true" data-show-columns = "true">     
            <!-- Folio Comparison-->
            @if ($idUseMode == 1 || $idUseMode == 4 )
                <thead>
                    <tr>
                        <th  data-align="center">UPC</th>
                        <th  data-align="center">Dif</th> 
                        <th  data-align="center" data-sortable="true">Fecha/Hora</th>
                        <th  data-align="left" data-sortable="true">Folio</th>
                        <th  data-align="center" data-sortable="true">Tipo</th>
                        <th  data-align="center" data-sortable="true">X</th>
                    </tr>
                </thead>
                @foreach($ordern_es_ms as $order)
                    <tr>
                        <td>
	 		{{ Form::open(array('url' => 'showUseMode/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('≡', array('class' => 'btn btn-default')) }}
			{{ Form::close() }}                            
                        </td>
                        <td>
	 		{{ Form::open(array('url' => 'comparison/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('||', array('class' => 'btn btn-default')) }}
			{{ Form::close() }}                         
                        </td>                        
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->folio}}</td>
                        <td>{{$order->type}}</td>
                        <td>
	 		{{ Form::open(array('url' => 'ordenesm/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'DELETE') }}
				{{ Form::submit('x', array('class' => 'btn btn-default')) }}
			{{ Form::close() }}                         
                        </td>                          
                    </tr>
                @endforeach
                </table>      
            <!-- Inventory Place | Inventory -->
            @elseif ($idUseMode == 2 || $idUseMode == 3)
                <thead>
                    <tr>
                        <th  data-align="center">UPC's</th>
                        <th  data-align="center">Comp.</th> 
                        <th  data-align="center" data-sortable="true">Fecha/Hora</th>
                        @if ($idUseMode == 2)
                            <th  data-align="left" data-sortable="true">Almacén</th>
                        @endif
                    </tr>
                </thead>
                @foreach($ordern_es_ms as $order)
                    <tr>
                        <td>
	 		{{ Form::open(array('url' => 'showUseMode/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('ver', array('class' => 'btn btn-default')) }}
			{{ Form::close() }}                            
                        </td>
                        <td>
	 		{{ Form::open(array('url' => 'comparison/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('ver', array('class' => 'btn btn-default')) }}
			{{ Form::close() }}                         
                        </td>                        
                        <td>{{$order->created_at}}</td>
                        @if ($idUseMode == 2)
                            <td>{{$order->warehouse->name}}</td>
                        @endif
                    </tr>
                @endforeach
                </table>             
            @endif             
        </div>
    </div>   
@stop