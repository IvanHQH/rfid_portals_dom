@extends ('BaseLayout')

@section ('content')
    <table data-toggle="table" id="table-pagination"  
           data-pagination="true" data-search="true" style="background-color: #ffffff">            
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
@stop
