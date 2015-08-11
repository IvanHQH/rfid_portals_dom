@extends ('BaseLayout')

@section ('content')     
<div class="col-lg-12">
    @if ($type == 'excess')
        <h3 class="page-header">Excedentes</h3>            
    @elseif ($type == 'missing')
        <h3 class="page-header">Faltantes</h3>                                 
    @endif 
</div>	
<div class="col-md-12">
    <div class="panel panel-info">           
        <div class="panel-heading">                         
            <div class="row">
            <div class="col-md-4">
              <div class="input-group">   
                <label for="date_ini">Fecha Inicial</label>
                @if (isset($date_init))
                    <input type="date" class="form-control" id="date_init" 
                       value="{{$date_init}}">
                @else
                    <input type="date" class="form-control" id="date_init" />                        
                @endif                                                                  
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->    
            <div class="col-md-4">
              <div class="input-group">   
                <label for="date_end">Fecha Final</label>
                @if (isset($date_end))
                    <input type="date" class="form-control" id="date_end"
                        value="{{$date_end}}"/>
                @else
                    <input type="date" class="form-control" id="date_end" />
                @endif    
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 --> 
            <div class="col-lg-4">
                <div class="input-group"><br>        
                    @if ($type == 'excess')
                    <button type="button" class="btn btn-default" id="btn-show-excess">
                        Mostrar
                    </button>             
                    @elseif ($type == 'missing')
                    <button type="button" class="btn btn-default" id="btn-show-missing">
                        Mostrar
                    </button>                                   
                    @endif
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->                      
            </div><!-- /.row -->                          
        </div>         
        <div class="panel-body">       
            <table id="events-table" data-toggle="table" 
            data-pagination="true" data-search="true" 
            class="table table-striped"
            data-show-columns = "true">     
            <thead>
            <tr>
                <th  data-align="center" data-sortable="true">Nombre</th>                        
                <th  data-align="center">UPC</th>  
                <th  data-align="center">Origen</th>    
                @if ($type == 'excess')
                    <th  data-align="center">Actual</th> 
                @endif
                <th  data-align="center">Ultima Lectura</th> 
            </tr>
            </thead>
            @if ($step == 'show_assets')
            @foreach($assets as $asset)
            <tr>
                <td>{{$asset->name}}</td>
                <td>{{$asset->upc}}</td>                                                                                                                                                  
                <td>{{$asset->origin}}</td> 
                @if ($type == 'excess')
                <td>{{$asset->actual}}</td>
                @endif
                <td>{{$asset->dateTime}}</td>  
            </tr> 
            @endforeach  
            @endif               
        </table> 
        </div>     
    </div>          
</div>   

@stop

@section('scripts')
<script>        
$(document).ready(function() {

    $('#btn-show-excess').on('click', function() {    
        var di = document.getElementById("date_init")
        var de = document.getElementById("date_end")
        var params;
        try
            {params = di.value+"+"+de.value;}
        catch(err) 
            {params = "0";}        
        window.location.replace("http://rfid.dev/rpt_excess/"+params);
        //window.location.replace("http://www.hqhrfid.com/rpt_excess/"+params);
    });          
    
    $('#btn-show-missing').on('click', function() {    
        var di = document.getElementById("date_init")
        var de = document.getElementById("date_end")
        var params;
        try
            {params = di.value+"+"+de.value;}
        catch(err) 
            {params = "0";}     
        window.location.replace("http://rfid.dev/rpt_missing/"+params);    
        //window.location.replace("http://www.hqhrfid.com/rpt_missing/"+params);
    });          
    
});
</script>
@stop