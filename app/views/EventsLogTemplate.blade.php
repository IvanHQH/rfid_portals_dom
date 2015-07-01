@extends ('BaseLayout')

@section ('content')
    <!-- name_warehouse -->
    <div class="content-container">        
        <div class="table-responsive container" style="width: 100%; padding: 10px;">  
            <div class="alert alert-success" id="events-result" data-es="Aquí se muestra el resultado del evento">
                @if (isset($description))
                    {{$description}}
                @endif
            </div>    
            <table data-toggle="table" id="table-pagination"  
                   data-pagination="true" data-search="true" 
                   style="background-color: #ffffff"
                   class="table table-striped">            
                <thead>
                    <tr>
                        <th data-field="description" data-align="left" data-sortable="true">Descripción</th>
                    </tr>
                </thead>
                @foreach($rescomps as $comp)
                    <tr>
                        <td>{{$comp}}</td>
                    </tr>        
                @endforeach
            </table>             
        </div>
    </div>     
@stop
