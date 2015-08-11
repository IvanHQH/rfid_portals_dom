@extends ('BaseLayout')

@section ('content')     
<div class="col-lg-12">
	<h3 class="page-header">Activos </h3>
</div>			
<div class="col-md-12">
    <div class="panel panel-info">
        <div class="panel-heading">
            @if (isset($description))
                {{$description}}
            @endif
        </div>
        @if (Auth::user()->pclient->id == 1 )                
            <div id="id_order" class="action-buttons" style="display: none">                                                    
                    {{$idOrderM}}                         
            </div>                                 
            <button id="get_xls" class="btn btn-default">Exportar</button>
        @endif
        <table id="events-table" data-toggle="table" 
            data-pagination="true" data-search="true" 
            data-show-columns = "true"
            class="table table-striped">                                          
            <thead>
                <tr>
                    <th data-field="name" data-align="left" data-sortable="true">Nombre</th>
                    <th data-field="upc" data-align="left" data-sortable="true">UPC</th>
                    @if (Auth::user()->pclient->use_mode_id == 3 || 
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
                        @if (Auth::user()->pclient->use_mode_id == 3 || 
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
@section('scripts')
<script>
$(document).ready(function() {

    $("#get_xls").click(function(){
        var id = $("#id_order").text();
        /*var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
        window.location="/products/csv"+"?"+$.param(oParams);*/
        //alert(id);
        window.location="/download_xls/"+id;
        /*alert("1");
        $.ajax({
                type: "POST",
                url: '{{ URL::to('/download_xls') }}' + '/' + id,
                success: function(data, textStatus, jqXHR) {                         
                    alert("2");
                },
                dataType: 'json'
        });    */   
    }); 
});   
</script>  
@stop