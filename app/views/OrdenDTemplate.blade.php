@extends ('BaseLayout')

@section ('content')     
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">  
            <div class="alert alert-info" id="events-result" data-es="Aquí se muestra el resultado del evento">
                @if (isset($description))
                    {{$description}}
                @endif
            </div>
            <table id="events-table" data-toggle="table" 
                data-pagination="true" data-search="true" 
                data-show-columns = "true"
                class="table table-striped">                                          
                <thead>
                    <tr>
                        <th data-field="name" data-align="left" data-sortable="true">Nombre</th>
                        <th data-field="upc" data-align="left" data-sortable="true">UPC</th>
                        @if ( Auth::user()->pclient->use_mode_id == 1 || 
                            Auth::user()->pclient->use_mode_id == 3 || 
                            Auth::user()->pclient->use_mode_id == 4)
                            <th data-field="quantity" data-align="center" 
                                data-sortable="true">Cantidad</th>
                        @endif
                    </tr>
                </thead>
                @if (isset($ordenesd))
                    @foreach($ordenesd as $order)
                        <tr>
                            <td>{{$order->name}}</td>
                            <td>{{$order->upc}}</td>
                            @if ( Auth::user()->pclient->use_mode_id == 1 || 
                                Auth::user()->pclient->use_mode_id == 3 || 
                                Auth::user()->pclient->use_mode_id == 4)                            
                                <td>{{$order->quantity}}</td>
                            @endif
                        </tr>                
                    @endforeach                
                @endif
            </table>            
        </div>
    </div>   
@stop