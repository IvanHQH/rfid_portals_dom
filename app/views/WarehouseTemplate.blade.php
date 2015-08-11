@extends ('BaseLayout')

@section ('content')    
<div class="col-lg-12">
    <h3 class="page-header">Zonas</h1>
</div>			
<div class="col-md-12">
    <div class="panel panel-info">
        <div class="panel-heading">       
            <button class="btn btn-default" data-toggle="modal" 
                    data-target="#smwModal" id="add_warehouse">Agregar</button>
	</div>        
	<div class="panel-body">         
            <table id="events-table" data-toggle="table" 
            data-pagination="true" data-search="true" data-show-columns = "true">     
            <thead>
                <tr>
                    <th data-align="left">Nombre</th>
                    <th data-align="left">Descripción</th> 
                    <th data-align="center"></th> 
                </tr>
            </thead>
            @foreach($warehouses as $wh)
                <tr>
                    <td>{{$wh->name}}</td>
                    <td>{{$wh->description}}</td> 
                    <td>                        
                        <div class="action-buttons">
                            <span class="warehouse-id" style="display:none">
                                {{$wh->id}}
                            </span>
                            <button class="btn btn-info btn-sm action-edit" >
                                <span class="glyphicon glyphicon-pencil">                                    
                                </span>
                            </button>
                            <button class="btn btn-danger btn-sm action-delete" >
                                <span class="glyphicon glyphicon-remove">                                    
                                </span>
                            </button>
                        </div>                       
                    </td>                                                
                </tr>
            @endforeach                
        </table>                 
        </div>                           
    </div>				
</div>
    
    <div style="display: none;" id="add-warehouse">
        <form role="form">
            <div class="form-group">
                <label for="warehouse_name">Nombre de la zona</label>
                <input type="text" class="form-control" id="warehouse_name" placeholder="Nombre de la zona">
            </div>
            <div class="form-group">
                <label for="warehouse_description">Descripción de la zona</label>
                <textarea id="warehouse_description" placeholder="Descripción del la zona" class="form-control"></textarea>
            </div>
        </form>
    </div>    
    
 
<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-header">
		<h1>Procesando...</h1>
	</div>
	<div class="modal-body">
		<div class="progress progress-striped active">
                    <div class="bar" style="width: 100%;"></div>
                </div>
	</div>
</div>	
@stop
@section('scripts')
<script>        
    $(document).ready(function() {
        setActiveMenu('menu_list_warehouse');
        function prepareModal(id) {
            $('#smwModal').find('#modalTitle').html('Agregar zona');
            $('#smwModal').find('.modal-body').html($('#add-warehouse').html());
            if (id != 0) {
                    $('#smwModal').find('.modal-body').html('Por favor espere...');
                    $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
                    $.ajax({
                        type: 'GET',
                        url: '{{ URL::to('/warehouse/get') }}' + '/' + id,
                        dataType: 'json',
                        success: function(d) {
                            $('#smwModal').find('.modal-body').html($('#add-warehouse').html())
                                .find('#warehouse_name').val(d.name).end()
                                .find('#warehouse_description').val(d.description).end()
                                .data('id', d.id)
                            ;
                            $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-warehouse-btn">Modificar Zona</button>')
                        }
                    });
                    window.location.reload();
            }
            else {
                $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-warehouse-btn">Agregar Zona</button>');
            }
            $('#smwModal').off('click', '#add-warehouse-btn').on('click', '#add-warehouse-btn', function() {
                //alert("add wh");
                var data = {
                    warehouse_name: $('#smwModal').find('#warehouse_name').val(),
                    warehouse_description: $('#smwModal').find('#warehouse_description').val(),
                }
                id = $('#smwModal').find('.modal-body').data('id');
                $.ajax({
                    type: "POST",
                    url: '{{ URL::to('/warehouse') }}' + (typeof id !== 'undefined'?('/' + id):''),
                    data: data,
                    success: function(data, textStatus, jqXHR) {
                        $('#smwModal').modal('hide');
                        dt.fnDraw();
                    },
                    dataType: 'json'
                });
                window.location.reload();
            });
        }

        $('#add_warehouse').on('click', function() {    
            prepareModal(0);
        });

        $('#events-table').off('click', '.action-edit').on('click', '.action-edit', function(e) {
                var o = $(this),
                id = o.parents('div:first').find('span.warehouse-id').text();
                prepareModal(id);
                $('#smwModal').modal();
                //window.location.reload();
        });

        $('#events-table').off('click', '.action-delete').on('click', '.action-delete', function(e) {
                var o = $(this),
                    id = o.parents('div:first').find('span.warehouse-id').text();
                if (!confirm('Desea borrar la zona?'))
                        return false;
                $.ajax({
                        type: "POST",
                        url: '{{ URL::to('/warehouse/delete') }}' + '/' + id,
                        success: function(data, textStatus, jqXHR) {
                                dt.fnDraw();
                        },
                        dataType: 'json'
                });
                window.location.reload();
        });
    });
</script>
@stop