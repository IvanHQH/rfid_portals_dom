@extends ('BaseLayout')

@section ('content')    
<div class="col-lg-12">
	<h3 class="page-header">Activos</h3>
</div>			
<div class="col-md-12">
    <div class="panel panel-info">
        <div class="panel-heading">     
            <button class="btn btn-default" data-toggle="modal" 
                data-target="#smwModal" id="add_product">Agregar</button>
	</div>            
	<div class="panel-body">          
            <table id="events-table" data-toggle="table" data-pagination="true" 
                   data-search="true" data-show-columns = "true"  
                   class="table table-striped">     
                <thead>
                    <tr>
                        <th data-align="left">Nombre</th>
                        @if (Auth::user()->pclient->commerce_type_id == 1)
                            <th data-align="left">Descripción<br> <font size = "2"> 
                                Marca | Color | Talla</font></th>   
                        @else
                            <th data-align="left">Descripción</th>                       
                        @endif
                        <th data-align="center">UPC</th>
                        @if (Auth::user()->pclient->use_mode_id == 5)
                        <th data-align="center">Traz.</th>
                        @endif                                            
                        <th data-align="center">Editar | Eliminar</th>                    
                    </tr>
                </thead>
                @foreach($products as $product)
                    <tr>
                        <td>{{$product->name}}</td>
                        <td>{{$product->description}}</td>                    
                        <td>{{$product->upc}}</td>
                        @if (Auth::user()->pclient->use_mode_id == 5)
                        <td>
                            <span class="order-id" style="display:none">
                                {{$product->id}}
                            </span>                        
                            <button class="btn btn-info btn-sm action-trace" >
                                <span class="glyphicon glyphicon-edit">                                    
                                </span>
                            </button>                        
                        </td>                        
                        @endif    
                        <td>
                        <div class="action-buttons">
                            <span class="product-id" style="display:none">
                                {{$product->id}}
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
    
    <div style="display: none;" id="add-product">
        <form role="form">
            <div class="form-group">
                <label for="product_name">Nombre del Producto</label>
                <input type="text" class="form-control" id="product_name" placeholder="Nombre del Producto">
            </div>
            <div class="form-group">
                <label for="product_upc">UPC</label>                
                @if (isset($upcSuggest))   
                <input type="text" class="form-control" id="product_upc" 
                       placeholder="UPC" value="{{$upcSuggest}}">
                @endif
            </div>            
            <div class="form-group">
                <label for="product_description">Descripción del producto</label>
                <textarea id="product_description" placeholder="Descripción del producto" 
                          class="form-control"></textarea>
            </div>
            @if (Auth::user()->pclient->use_mode_id == 2)            
                <div class="form-group">
                    <label for="product_warehouse">Zona</label>
                    <select id="product_warehouse" class="form-control">                            
                        @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach                              
                    </select>
                </div>
            @endif
            @if (Auth::user()->pclient->use_mode_id == 5)            
                <div class="form-group">
                    <label for="user_name">Instalo Usuario</label>
                    <select id="user_name" class="form-control">                            
                        @foreach($users as $user)
                            <option>{{$user->name}}</option>
                        @endforeach                              
                    </select>                    
                </div>
                <div class="form-group">
                    <label for="created_at">Fecha/Hora Instalación</label>
                    <input id="created_at" class="form-control">                    
                </div>            
            @endif            
        </form>
    </div>    
    
    <div style="display: none;" id="add-trace">
        <form role="form">
            <div class="form-group">
                <label>Usuario 1 / 2009-01-21 18:54</label>
                <input class="form-control" readonly value="descripcion">
            </div>
            <div class="form-group">
                <label>Usuario 1 / 2009-01-21 18:54</label>  
                <input class="form-control" readonly value="descripcion">
            </div>            
            <div class="form-group">
                <label>Usuario 1 / 2009-01-21 18:54</label>
                <input class="form-control" readonly value="descripcion">
            </div>          
            <div class="form-group">
                <label>Agrgar a Historial:</label>
                <input class="form-control">                    
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
            //setActiveMenu('menu_list_assets');
            function prepareModal(id) {
                $('#smwModal').find('#modalTitle').html('Agregar Producto');
                $('#smwModal').find('.modal-body').html($('#add-product').html());
                if (id != 0) {
                        $('#smwModal').find('.modal-body').html('Por favor espere...');
                        $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
                        $.ajax({
                            type: 'GET',
                            url: '{{ URL::to('/product/get') }}' + '/' + id,
                            dataType: 'json',
                            success: function(d) {
                                $('#smwModal').find('.modal-body').html($('#add-product').html())
                                    .find('#product_name').val(d.name).end()
                                    .find('#product_upc').val(d.upc).end()
                                    .find('#user_name').val(d.user_name).end()
                                    .find('#created_at').val(d.created_at).end()
                                    .find('#product_description').val(d.description).end()
                                    .data('id', d.id)
                                ;
                                $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-product-btn">Modificar Producto</button>')
                            }
                        });
                }
                else {
                    $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-product-btn">Agregar Producto</button>');                
                }
                $('#smwModal').off('click', '#add-product-btn').on('click', '#add-product-btn', function() {                    
                    var data = {
                        product_name: $('#smwModal').find('#product_name').val(),
                        product_upc: $('#smwModal').find('#product_upc').val(),
                        product_description: $('#smwModal').find('#product_description').val(),
                        product_warehouse: $('#smwModal').find('#product_warehouse').val(),
                        user_name: $('#smwModal').find('#user_name').val(),
                        created_at: $('#smwModal').find('#created_at').val()
                    }, 
                    id = $('#smwModal').find('.modal-body').data('id');
                        $.ajax({
                            type: "POST",
                            url: '{{ URL::to('/product') }}' + (typeof id !== 'undefined'?('/' + id):''),
                            data: data,
                            success: function(data, textStatus, jqXHR) {                        
                                if(data.success == true){
                                    /*$('#smwModal').modal('hide');
                                    dt.fnDraw();    */
                                    window.location.reload();
                                }
                                else{alert(data.errors);}                        
                            },
                            dataType: 'json'
                        });
                        //window.location.reload();
                });
            }

            function prepareModalTrace() {
                //alert("ok");
                $('#smwModal').find('#modalTitle').html('Trazabilidad');
                $('#smwModal').find('.modal-body').html($('#add-trace').html());
                $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-product-btn">Agregar Producto</button>');                                
            }

            $('#add_product').on('click', function() {
                //alert('add product');
                prepareModal(0);                
            });

            $('#events-table').off('click', '.action-trace').on('click', '.action-trace', function(e) {
                    prepareModalTrace();
                    $('#smwModal').modal();
            });

            $('#events-table').off('click', '.action-edit').on('click', '.action-edit', function(e) {
                    var o = $(this),
                    id = o.parents('div:first').find('span.product-id').text();
                    prepareModal(id);
                    $('#smwModal').modal();         
                    //window.location.reload();
                    var input = document.getElementById('product_upc');
                    input.setAttribute('readonly', 'readonly');   
            });

            $('#events-table').off('click', '.action-delete').on('click', '.action-delete', function(e) {
                    var o = $(this),
                    id = o.parents('div:first').find('span.product-id').text();
                    if (!confirm('Desea borrar el Producto?'))
                            return false;
                    $.ajax({
                            type: "POST",
                            url: '{{ URL::to('/product/delete') }}' + '/' + id,
                            success: function(data, textStatus, jqXHR) {                         
                                dt.fnDraw();
                            },
                            dataType: 'json'
                    });                                      
                    
                    window.location.reload();
                    window.location.reload();
                    //$("#refresh").click();
            });
        });
    </script>
@stop
