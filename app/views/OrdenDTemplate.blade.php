@extends ('BaseLayout')

@section ('content')     
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">  
            <div class="alert alert-success" id="events-result" data-es="Aquí se muestra el resultado del evento">
                @if (isset($folio))
                    Folio {{$folio}}
                @endif
            </div>
            <table id="events-table" data-toggle="table" 
                data-pagination="true" data-search="true" data-show-columns = "true">                                          
                <thead>
                    <tr>
                        <th data-field="name" data-align="left" data-sortable="true">Nombre</th>
                        <th data-field="upc" data-align="left" data-sortable="true">UPC</th>
                        <th data-field="quantity" data-align="center" data-sortable="true">Cantidad</th>
                    </tr>
                </thead>
                @if (isset($ordenesd))
                    @foreach($ordenesd as $order)
                        <tr>
                            <td>{{$order->name}}</td>
                            <td>{{$order->upc}}</td>
                            <td>{{$order->quantity}}</td>
                        </tr>                
                    @endforeach                
                @endif
            </table>            
        </div>
    </div>   
@stop