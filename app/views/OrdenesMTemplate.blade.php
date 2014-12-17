@extends ('BaseLayout')

@section ('content')     
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">  
            <div class="alert alert-success" id="events-result" data-es="Aquí se muestra el resultado del evento">
                Here is the result of event.
            </div>
            <table id="events-table" data-toggle="table" 
                data-pagination="true" data-search="true" data-show-columns = "true">                       
                <thead>
                    <tr>
                        <th  data-align="center">UPC's</th>                        
                        <th  data-align="center" data-sortable="true">Fecha/Hora</th>
                        <th  data-align="left" data-sortable="true">Cliente</th>
                        <th  data-align="left" data-sortable="true">Folio</th>
                        <th  data-align="center" data-sortable="true">Tipo</th>
                    </tr>
                </thead>
                @foreach($ordern_es_ms as $order)
                    <tr>
                        <td>
	 		{{ Form::open(array('url' => 'ordenesd/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('ver', array('class' => 'btn btn-default')) }}
			{{ Form::close() }}                            
                        </td>
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->customer_id}}</td>
                        <td>{{$order->folio}}</td>
                        <td>{{$order->type}}</td>
                    </tr>
                @endforeach
            </table>              
        </div>
    </div>   
@stop