@extends ('BaseLayout')

@section ('content')     
<div class="col-lg-12">
    <h3 class="page-header">Arqueo</h3>
</div>	
<div class="col-md-12">
    <div class="panel panel-info">
        <div class="panel-heading">     
            @if (isset($step))
                @if ($step == 'inv_init')
                    Paso 1 : Selecciona la lectura inicial
                @elseif ($step == 'inv_end')   
                    Paso 2 : Selecciona la lectura final
                @elseif ($step == 'up_file')
                    Paso 3 : Selecciona el Archivo de Salidas de Inventario del Punto de Venta
                    <br> o Crea el Arqueo
                @elseif ($step == 'show_arching')
                    Arqueo
                @endif                          
            @endif
        </div>                
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Arqueo Personalizado</h3>
          </div>                
          <div class="panel-body">          
            <div class="row">
            <div class="col-md-4">
              <div class="input-group">   
                <div class="input-group">
                    @if (isset($inv_init))
                    <span class="input-group-addon" 
                          style="background-color: #d9edf7" id="sizing-addon2">
                        Lectura Inicial</span>                                  
                        <input type="text" class="form-control" 
                               value="{{$inv_init}}" readonly id="inv_init">                                                               
                    @endif                                
                </div>                                                                    
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->    
            <div class="col-md-4">
              <div class="input-group">   
                  <div class="input-group">
                      @if (isset($inv_end))
                        <span class="input-group-addon" 
                              style="background-color: #d9edf7"  id="sizing-addon2">
                            Lectura Final</span>                                               
                        <input type="text" class="form-control" 
                               value="{{$inv_end}}" readonly id="inv_end">      
                      @endif  
                  </div>        
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 --> 
            @if (isset($inv_init) && isset($inv_end))
            <div class="col-lg-4">
                <div class="input-group">                 
                @if (isset($file))
                  <div class="input-group">
                    <span class="input-group-addon" 
                          style="background-color: #d9edf7"  id="sizing-addon2">Archivo Salidas</span>                  
                        <input type="text" class="form-control" value="{{$file}}" readonly id="name_file">                                                                                      
                  </div>                     
                @elseif ($step != 'show_arching')                   
                    {{ Form::open(array(
                         'url'=>'upload/'. $inv_init_id.'+'.$inv_end_id, 
                         'method' => 'post',
                         'enctype'=>'multipart/form-data'
                    ) )}}
                    {{ Form::file('archivo') }}
                    {{ Form::submit('subir',array('class' => 'btn btn-info')) }}
                    {{ Form::close()}}                      
                @endif
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->                      
            @endif
            </div><!-- /.row -->                                    
            @if (isset($inv_init) && isset($inv_end))
            @if ($step != 'show_arching')
            <br>
            <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-primary" 
                      id="create_arching">Crear Arqueo</button>
            </div>       
            @endif 
            @endif                
          </div>
        </div>        
        @if ($step != 'up_file' && $step != 'show_arching')
        <table id="events-table" data-toggle="table" 
            data-pagination="true" data-search="true" 
            class="table table-striped"
            data-show-columns = "true">     
            <thead>
            <tr>
                <th  data-align="center" data-sortable="true">Fecha / Hora</th>                        
                <th  data-align="center">Activos</th>  
                <th  data-align="center">Seleccionar</th>                             
            </tr>
            </thead>
            @if (isset($ordern_es_ms))   
            @foreach($ordern_es_ms as $order)
            <tr>
                <td>{{$order->created_at}}</td>
                <td>                         
                    {{ Form::open(array('url' => 'showAssetsUseMode/' . $order->id,
                                'class' => 'pull-center')) }}
                        {{ Form::hidden('_method', 'GET') }}
                        {{ Form::submit('ver', array('class' => 'btn btn-default')) }}                                                           
                    {{ Form::close() }}                                                                                                                        
                </td>                                                                                                                                                  
                <td>
                    @if ($step == 'inv_init')
                    {{ Form::open(array('url' => 'arching_inv_end/' . $order->id,
                        'class' => 'pull-center')) }}                                
                        {{ Form::hidden('_method', 'GET')}}
                        {{ Form::submit('■', array('class' => 'btn btn-default')) }}
                    {{ Form::close() }}                                
                    @elseif ($step == 'inv_end')   
                    {{ Form::open(array('url' => 'arching_up_file/' . $id_inv_init.'+'.$order->id,
                        'class' => 'pull-center')) }}                                
                        {{ Form::hidden('_method', 'GET') }}
                        {{ Form::submit('■', array('class' => 'btn btn-default')) }}
                    {{ Form::close() }}                                 
                    @endif   
                </td>    
            </tr> 
            @endforeach   
            @else  
            @endif                
        </table> 
        @else
        @if ($step == 'show_arching')
            <table id="events-table" data-toggle="table" 
                data-pagination="true" data-search="true" 
                data-show-columns = "true"
                class="table table-striped">     
                <thead>
                <tr>
                    <th  data-align="center" data-sortable="true">Nombre</th>  
                    <th  data-align="center" data-sortable="true">UPC</th>                        
                    <th  data-align="center">Lectura Inicial</th>  
                    <th  data-align="center">Salidas P.V.</th>           
                    <th  data-align="center">Lectura Final</th>                          
                    <th  data-align="center">       
                        <font size="1">Lectura Inicial - Salidas P.V.</font><br>LISP
                    </th>                      
                    <th  
                        data-align="center">
                        <font size="1">Lectura Inicial - LISP</font><br>Diferencia
                    </th>
                </tr>
                </thead>   
                @if (isset($archings))
                @foreach($archings as $arching)
                <tr>
                    <td>{{$arching->name}}</td> 
                    <td>{{$arching->upc}}</td>
                    <td>{{$arching->qtyInvInit}}</td>
                    <td>{{$arching->qtyOutPOS}}</td>
                    <td>{{$arching->qtyInvEnd}}</td>                        
                    <td>{{$arching->qtyInitLessPOS}}</td>
                    @if ($arching->qtyDiff < 0)
                        <td><div style="color: red"> {{$arching->qtyDiff}}</div></td>
                    @elseif($arching->qtyDiff > 0)
                        <td><div style="color : "> +{{$arching->qtyDiff}}</div></td>
                    @else
                    <td>{{$arching->qtyDiff}}</td>
                    @endif
                </tr> 
                @endforeach                     
                @endif
            </table>    
            @endif
        @endif  
    </div>          
</div>   

@stop

@section('scripts')
<script>        
$(document).ready(function() {

    $('#create_arching').on('click', function() {    
        // inv_init & inv_end fomat dd/MM/YY hh:mm:ss        
        var inv_init = document.getElementById("inv_init")
        var inv_end = document.getElementById("inv_end")
        var file = document.getElementById("name_file")
        var params;
        try{
            params = inv_init.value+"+"+inv_end.value+"+"+file.value;
        }
        catch(err) {
            params = inv_init.value+"+"+inv_end.value;
        }
        //alert(inv_init.value+","+inv_end.value+","+file.value);
        //alert("okj");
        window.location.replace("http://rfid.dev/arching_do/"+params);
    });          
});
</script>
@stop