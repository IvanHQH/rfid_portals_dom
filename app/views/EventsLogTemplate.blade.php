@extends ('BaseLayout')

@section ('content')
    <table data-toggle="table" id="table-pagination" data-url="/events_logs/rows_data" 
           data-pagination="true" data-search="true" style="background-color: #ffffff">            
        <thead>
            <tr>
                <th data-field="created_at" data-align="left" data-sortable="true">Fecha Hora</th>                    
                <th data-field="name" data-align="left" data-sortable="true">Usuario</th>
                <th data-field="description" data-align="left" data-sortable="true">Descripción</th>
            </tr>
        </thead>
    </table>            
@stop
