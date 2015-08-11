@extends ('BaseLayout')

@section ('content')
<div class="col-lg-12">
    <h3 class="page-header">Comparación</h3>
</div>			
    <!-- name_warehouse -->
<div class="col-md-12">
    <div class="panel panel-info"> 
        <div class="panel-heading">
            @if (isset($description))
                {{$description}}
            @endif
        </div>    
        <!-- Excedents and Missing -->
        @if (isset($rescompsExce) || isset($rescompsMiss))  
        <div class="panel-body">
            <div class="row">
                <!-- Excessing results -->
                @if (isset($rescompsExce))
                    @if (isset($rescompsExce) && isset($rescompsMiss))
                    <div class="col-md-6">
                    @elseif(isset($rescompsExce) &&  isset($rescompsMiss) == false)    
                    <div class="col-md-12">
                    @endif    
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Excedentes
                            </div>
                            <div class="panel-body">
                                <table data-toggle="table" id="table-pagination"  
                                       data-pagination="true" data-search="true" 
                                       style="background-color: #ffffff"
                                       class="table table-striped">            
                                    <thead>
                                        <tr>
                                            <th data-field="description" data-align="left" 
                                                data-sortable="true">Descripción</th>
                                        </tr>
                                    </thead>
                                    @foreach($rescompsExce as $comp)
                                        <tr>
                                            <td>{{$comp}}</td>
                                        </tr>        
                                    @endforeach                       
                                </table>                                 
                            </div>                           
                        </div>
                    </div>        
                @endif
                <!-- / Excessing results -->
                <!-- Missings results -->
                @if (isset($rescompsMiss))
                    @if (isset($rescompsExce) && isset($rescompsMiss))
                    <div class="col-md-6">
                    @elseif(isset($rescompsExce) == false &&  isset($rescompsMiss))    
                    <div class="col-md-12">                           
                    @endif          
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Faltantes
                            </div>
                            <div class="panel-body">
                                <table data-toggle="table" id="table-pagination"  
                                       data-pagination="true" data-search="true" 
                                       style="background-color: #ffffff"
                                       class="table table-striped">            
                                    <thead>
                                        <tr>
                                            <th data-field="description" data-align="left" 
                                                data-sortable="true">Descripción</th>
                                        </tr>
                                    </thead>
                                    @foreach($rescompsMiss as $comp)
                                        <tr>
                                            <td>{{$comp}}</td>
                                        </tr>        
                                    @endforeach                       
                                </table>                             
                            </div>
                        </div>               
                    </div>    
                @endif
                <!-- / Missings results -->    
            </div> 
        </div>             
        @endif          
        <!-- / Excedents and Missing -->
        <!-- Only one type of result -->
        @if (isset($rescomps))  
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
        @endif   
        <!-- / Only one type of result -->
    </div>    				
</div>  
@stop
