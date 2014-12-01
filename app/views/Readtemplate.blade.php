@extends ('Readlayout')

@section ('header')
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">       
        <table style="width: 100%">
            <tr style="width: 100%">
                <td style="width: 20%">
                    <img src="/img/plomeria_selecta_logo.jpeg"></td>
                <td style="width: 80%" align=center>
                    Lectura de bloque de prodctos por folio</td>
                <td style="width: 20%" >
                    <img src="/img/grupo_hqh_logo.png"></td>
            </tr>
        </table>
      </a>
    </div>
  </div>
</nav>
@stop

@section ('navbar')
    <table style="width: 100%">
        <tr style="width: 100%">
            <td style="width: 38%">
                <form class="navbar-form navbar-left" role="search">
                  <div class="form-group">
                    <input type="text" class="form-control" placeholder="Folio">
                  </div>
                  <button type="submit" class="btn btn-default">Aceptar</button>
                </form></td>
            <td style="width: 50%" align="center">Miercoles 12 de Noviembre del 2014</td>
            <td style="width: 12%" >
                <div class="btn-group">
                <button type="button" class="btn btn-default"><img src="/img/antena_rfid_mini.png"/>1</button>
                <button type="button" class="btn btn-default"><img src="/img/antena_rfid_mini.png"/>2</button>
                </div></td>
        </tr>
    </table>
@stop

@section ('table')
<table data-toggle="table" id="table-pagination" data-url="readstags.json" 
data-pagination="true" data-search="true">
<!--table data-toggle="table" id="table-pagination" data-url="/dates/lastfolio"  
       data-side-pagination="server" data-pagination="true" 
       data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"-->
    <thead>
        <tr>
            <!--th data-field="descripcion" data-align="right" data-sortable="true">Descripción</th-->
            <th data-field="epc" data-align="left" data-sortable="true">EPC</th>
            <th data-field="quantity" data-align="center" data-sortable="true">Cantidad</th>
        </tr>
    </thead>
</table>
@stop

@section('pagination')

@stop