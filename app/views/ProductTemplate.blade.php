@extends ('BaseLayout')

@section ('content')    
<div class="tab-header">Productos</div>
<div class="content-container">        
    <div class="table-responsive container" style="width: 100%; padding: 10px;">  
        <button class="btn btn-sm" data-toggle="modal" data-target="#smwModal" id="add_product">Agregar</button>
        <table id="events-table" data-toggle="table" 
            data-pagination="true" data-search="true" data-show-columns = "true">     
            <thead>
                <tr>
                    <th data-align="left">Nombre</th>
                    <th data-align="left">Descripción</th> 
                    <th data-align="center">UPC</th>
                    <th data-align="center"></th>
                </tr>
            </thead>
            @foreach($products as $product)
                <tr>
                    <td>{{$product->name}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->upc}}</td>
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
                <textarea id="product_description" placeholder="Descripción del producto" class="form-control"></textarea>
            </div>
            @if (Auth::user()->pclient->useMode->id == 2)            
                <div class="form-group">
                    <label for="product_warehouse">Zona</label>
                    <select id="product_warehouse" class="form-control">                            
                        @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach                              
                    </select>
                </div>
            @endif
        </form>
    </div>    
    
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
            //alert('load products');
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
                        product_warehouse: $('#smwModal').find('#product_warehouse').val()
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
                                else{
                                    alert(data.errors);
                                }                        
                            },
                            dataType: 'json'
                        });
                        //window.location.reload();
                });
            }

            $('#add_product').on('click', function() {
                //alert('add product');
                prepareModal(0);
            });

            $('#events-table').off('click', '.action-edit').on('click', '.action-edit', function(e) {
                    var o = $(this),
                    id = o.parents('div:first').find('span.product-id').text();
                    prepareModal(id);
                    $('#smwModal').modal();         
                    //window.location.reload();
            });

            $('#events-table').off('click', '.action-delete').on('click', '.action-delete', function(e) {
                    var o = $(this),
                    id = o.parents('div:first').find('span.product-id').text();
                    if (!confirm('Desea borrar el Producto?'))
                            return false;
                    $.ajax({
                            type: "DELETE",
                            url: '{{ URL::to('/product') }}' + '/' + id,
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
