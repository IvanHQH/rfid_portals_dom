@extends ('BaseLayout')

@section ('content')     
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">  
            <div class="alert alert-success" id="events-result" data-es="Aquí se muestra el resultado del evento">
                Lecturas
            </div>
            <table id="events-table" data-toggle="table" data-pagination="true" 
                   data-search="true" data-show-columns = "true" 
                   class="table table-striped">     
            <!-- Folio Comparison-->
            @if ($idUseMode == 1 || $idUseMode == 4 )
                <thead>
                    <tr>
                        <th  data-align="center">UPC</th>
                        <th  data-align="center">Dif</th> 
                        <th  data-align="center" data-sortable="true">Fecha / Hora</th>
                        <th  data-align="left" data-sortable="true">Folio</th>
                        <th  data-align="center" data-sortable="true">Tipo</th>
                        <th  data-align="center" data-sortable="true">Eliminar</th>
                    </tr>
                </thead>
                @foreach($ordern_es_ms as $order)
                    <tr>
                        <td>
	 		{{ Form::open(array('url' => 'showUseMode/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('≡', array('class' => 'btn btn-info')) }}                                                           
			{{ Form::close() }}                    
                        </td>
                        <td>
	 		{{ Form::open(array('url' => 'comparison/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('||', array('class' => 'btn btn-info')) }}
			{{ Form::close() }}                        
                        </td>                        
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->folio}}</td>
                        <td>{{$order->type}}</td>
                        <td>               
                        <div class="action-buttons">
                            <span class="order-id" style="display: none">                                                        
                                {{$order->id}}
                            </span>                        
                            <button class="btn btn-danger btn-sm action-delete" >
                                    <span class="glyphicon glyphicon-remove">                                    
                                    </span>
                            </button>   
                        </div>                                                                                            
	 		<!--{{ Form::open(array('url' => '/ordenesm/delete/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'POST') }}
				{{ Form::submit('X', array('class' => 'btn btn-danger btn-sm action-delete')) }}
			{{ Form::close() }}    -->                         
                        </td>                          
                    </tr>
                @endforeach
                </table>      
            <!-- Inventory Place | Inventory -->
            @elseif ($idUseMode == 2 || $idUseMode == 3 || $idUseMode == 5)
                <thead>
                    <tr>
                        <th  data-align="center">UPC's</th>
                        @if ($idUseMode != 5 && $idUseMode != 3)
                            <th  data-align="center">Comp.</th> 
                        @endif           
                        <th  data-align="center" data-sortable="true">Fecha / Hora</th>
                        @if ($idUseMode == 2 || $idUseMode == 5)
                            <th  data-align="left" data-sortable="true">Área</th>
                        @endif                      
                        <th  data-align="center" data-sortable="true">Eliminar</th>
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
                        @if ($idUseMode != 5 && $idUseMode != 3)
                        <td>                        
	 		{{ Form::open(array('url' => 'comparison/' . $order->id, 'class' => 'pull-center')) }}
				{{ Form::hidden('_method', 'GET') }}
				{{ Form::submit('ver', array('class' => 'btn btn-default')) }}
			{{ Form::close() }}                                                                                            
                        </td>                        
                        @endif
                        <td>{{$order->created_at}}</td>
                        @if ($idUseMode == 2 || $idUseMode == 5)
                            <td>{{$order->warehouse->name}}</td>
                        @endif
                        <td>            
                        <div class="action-buttons">
                            <span class="order-id" style="display:none">
                                {{$order->id}}
                            </span>                        
                            <button class="btn btn-danger btn-sm action-delete" >
                                    <span class="glyphicon glyphicon-remove">                                    
                                    </span>
                            </button>                        
                        </div>
                        </td>                                                 
                    </tr>
                @endforeach
                </table>             
            @endif
        </div>
    </div>   
@stop

@section('scripts')
<script>        
    $(document).ready(function() {

        $('#events-table').off('click', '.action-delete').on('click', '.action-delete', function(e) {
            var o = $(this),
            id = o.parents('div:first').find('span.order-id').text();
            if (!confirm('Desea borrar la lectura?')){
                    return false;
            }
            $.ajax({
                    type: "POST",
                    url: '{{ URL::to('/ordenesm/delete') }}' + '/' + id,
                    success: function(data, textStatus, jqXHR) {
                            dt.fnDraw();
                            $("#refresh").click();
                    },
                    dataType: 'json'
            });
            window.location.reload();
            window.location.reload();
            
        });           
    });
</script>
@stop