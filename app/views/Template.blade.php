@extends ('BaseLayout2')

@section ('content')
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="btn-group" >
                <button class="btn btn-default" type="button">
                     <em class="glyphicon glyphicon-list-alt">                                         
                     </em> Reporte</button> 
                <button class="btn btn-default" type="button">
                         <em class="glyphicon glyphicon-plus-sign">                                             
                         </em> Alta Evento</button> 
                <button class="btn btn-default" type="button">
                             <em class="glyphicon glyphicon-list">
                             </em> Intinerario</button> 
                <button class="btn btn-default" type="button">
                    <em class="glyphicon glyphicon-send">                                        
                    </em> 
                    <a id="modal-208729" href="#modal-container-208729" role="button" data-toggle="modal">Enviar Invitacion</a>                     
                </button>               
            </div>                    
        </div>  
    </div>    
    <h3>
            Alta Evento
    </h3> 
    <br>
    <div class="row clearfix">
        <div class="col-md-6 column">
            <form role="form">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nombre</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" 
                           value="Junta Trimestral 2015"/>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Sede</label>
                    <input type="text" class="form-control" id="exampleInputPassword1"
                           value="Hotel NH Collection, Guadalajara"/>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Dirección</label>
                    <input type="text" class="form-control" id="exampleInputPassword1"
                           value="Sao Paulo 2334, Col. Providencia"/>
                </div>                                
                <div class="form-group">
                    <label for="exampleInputPassword1">Ciudad</label>
                    <select id="use_mode" class="form-control" style="float:right">   
                        <option>Guadalajara, Jalisoco</option>
                    </select>
                </div>                                       
                <div class="form-group">
                    <label for="exampleInputPassword1">Fecha</label>
                    <input type="date" class="form-control" id="exampleInputPassword1" 
                           value = "15/07/2015"/>
                </div>                                      
                <div class="form-group">
                    <label for="exampleInputPassword1">Tarifa Por Noche</label>
                    <input type="text" class="form-control" id="exampleInputPassword1"
                           value="1,250.00"/>
                </div>                                
                <div class="form-group">
                    <label for="exampleInputFile">Cargar Meeting Agenda</label>
                    <input type="file" id="exampleInputFile" />
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
        <div class="col-md-6 column">  
            <div class="panel panel-default">
                    <div class="panel-heading">
                            <h3 class="panel-title">
                                    Incluye
                            </h3>
                    </div>
                    <div class="panel-body">
                            Desayuno
                    </div>
                    <div class="panel-body">
                            Gimnasio
                    </div>      
                    <div class="panel-body">
                            Internet
                    </div>   
                    <div class="panel-body">
                        <button type="button" class="btn btn-default">Agregar</button>
                    </div>                                 
            </div>             
            <div class="panel panel-default">
                    <div class="panel-heading">
                            <h3 class="panel-title">
                                    Recomendaciones
                            </h3>
                    </div>
                    <div class="panel-body">
                            Fecha límite de Registro : 22 de junio 2015
                    </div>
                    <div class="panel-body">
                            Vestimenta : Traje Completo
                    </div>           
                    <div class="panel-body">
                        <button type="button" class="btn btn-default">Agregar</button>
                    </div>                    
            </div>               
        </div>        
    </div>
</div>
<div class="modal fade" id="modal-container-208729" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">
                       Enviar Invitación
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleInputPassword1">Evento</label>
                    <select id="use_mode" class="form-control" style="float:right"> 
                        <option>Junta Trimestral 12/07/2015</option>
                    </select>     
                </div><br><br>
                <div class="form-group">
                    <label for="exampleInputPassword1">Nombre</label>
                    <input type="text" class="form-control" id="exampleInputPassword1"
                           value ="Juan Pérez Sánchez"/>
                </div>       
                <div class="form-group">
                    <label for="exampleInputPassword1">E-Mail</label>
                    <input type="email" class="form-control" id="exampleInputPassword1" 
                           value="juan.perez@corporativox.com"/>
                </div>                 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cancelar</button> <button type="button" class="btn btn-primary">
                        Enviar</button>
            </div>
        </div>

    </div>

</div>
@stop
			
