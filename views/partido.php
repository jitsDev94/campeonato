<?php

include 'clases/conexion.php';
require_once 'clases/parametros.php';

session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
} else {
    $idRol = $_SESSION['idRol'];   
    $usuario = $_SESSION['usuario'];  
    $idEquipoDelegado = $_SESSION['idEquipo'];
    $nombreEquipoDelegado= $_SESSION['nombreEquipo'];
   
}

$parametro = new parametros();


$cobros = $parametro->traerPrecio('Observaciones');
 $ultimoPartido = $parametro->TraerUltimoPartido();
$torneo = $parametro->TraerUltimoTorneo();
 $sede = $parametro->TraerUltimoSede();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Partido</title>
  <link rel="icon" type="image/jpg" href="img/image.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
   <!-- DataTables -->
   <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
   <!-- SweetAlert2 -->
   <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  
   <style>
    .card{
      border-top-color: cornflowerblue;
      border-top-width: 3px;
    }
  </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse">
<div class="wrapper">

    <?php  
        require "Navegador.php";
    ?>

    <?php  
        require "Menus.php";
    ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-2 col-8 pb-3">
            <h1 class="m-0">Generar Partido</h1>
          </div>
          <div class="col-sm-2 col-4" id="AlertaTorneo"></div>
            <div class="col-md-5 col 4" id="cargando_add1"></div>
          <div class="col-12 col-md-3">
           <button type="button" class="btn btn-success btn-block" id="btn_nuevo_add1" onclick="GuardarPartido()"><i class="fas fa-save"></i> Guardar Partido</button>                                
          </div>  
          <!-- /.col
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div> /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div id="cargando_add1"></div>

        <section class="col-lg-12 col-md-12">
            <div class="row">        
                <section class="col-lg-5 col-md-5">
                    <div class="card info-box shadow-lg">
                        <div class="card-header">             
                            <div class="row"> 
                                <div class="col-md-12">
                                    <label for=""><h4>Datos del Partido</h4></label>
                                </div>       
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="POST"> 
                                <div class="mb-3 row">
                                   
                                    <div class="col-sm-8">
                                        <input type="hidden" readonly class="form-control-plaintext" id="idPartido" value="<?php echo $ultimoPartido; ?>" disabled>
                                    </div>
                                </div>
                               
                             
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Equipo Local(*)</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="idEquipo1"> 
                                            <?php 
                                                $parametro->DropDownListarEquiposInscritos();
                                            ?>
                                        </select>  
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Equipo Visitante(*)</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="idEquipo2"> 
                                         
                                            <?php 
                                                $parametro->DropDownListarEquiposInscritos();
                                            ?>
                                           
                                        </select>       
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Fecha(*)</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id ="fecha">            
                                    </div>
                                </div> 
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-5 col-form-label">Walkover</label>
                                    <div class="col-sm-7">
                                      <input class="form-check-input" type="checkbox" value="" id="walkover" onchange="ActivarWalkover()">
                                    </div>
                                </div>  
                                <div class="mb-3 row" id="walkoverActivado">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Equipo Ganador(*)</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="idEquipo3"> 
                                            <?php 
                                                $parametro->DropDownBuscarEquipos2($_GET["idEquipo2"],$_GET["idEquipo1"]);
                                            ?>
                                        </select>       
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Sede(*)</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="sede" value="<?php echo $sede->nombreSede; ?>" disabled>
                                    </div>
                                </div>                              
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Torneo(*)</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="torneo" value="<?php echo $torneo->nombre; ?>" disabled>         
                                    </div>
                                </div>  
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Tipo Partido(*)</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="Modo"> 
                                            <option selected>Clasificatoria</option>
                                            <option>8vo de Final</option>
                                            <option>4to de Final</option>
                                            <option>Semifinal</option>
                                            <option>3er y 4to Lugar</option>
                                            <option>Final</option>
                                        </select>       
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-5 col-form-label">Observar Partido</label>
                                    <div class="col-sm-7">
                                      <h2><input class="form-check-input" type="checkbox" value="" id="observarPartido" onchange="observar()"></h2>
                                    </div>
                                </div>   
                                <div id="EquipoObservado">
                                  <div class="mb-3 row">
                                      <label for="inputPassword" class="col-sm-4 col-form-label">Motivo de la observación</label>
                                      <div class="col-sm-8">
                                      <textarea class="form-control" id="observacion" rows="3" placeholder="Explicacion Brave"></textarea>
                                      </div>
                                  </div> 
                                  <div class="mb-3 row" >
                                      <label for="inputPassword" class="col-sm-4 col-form-label">Equipo Observado(*)</label>
                                      <div class="col-sm-8">
                                          <select class="form-control" id="idEquipoObservado">                                          
                                          <?php 
                                                $parametro->DropDownBuscarEquipos2($_GET["idEquipo2"],$_GET["idEquipo1"]);
                                            ?>
                                          </select>  
                                      </div>
                                  </div>
                                  <div class="mb-3 row" id="Precio">
                                      <label for="inputPassword" class="col-sm-4 col-form-label">Precio(*)</label>
                                      <div class="col-sm-8">
                                      
                                          <input type="text" class="form-control" id="precioObservacion" value="<?php echo $cobros->precio; ?>" placeholder="Precio de la Observación">         
                                      </div>
                                  </div>  
                                </div> 
                                <div class="col-md-6">               
                                    <input type="hidden" class="form-control" id ="idSede" value="<?php echo $sede->id; ?>">                                          
                                </div> 
                                <div class="col-md-6">               
                                    <input type="hidden" class="form-control" id ="idCampeonato" value="<?php echo $torneo->id; ?>" >                                          
                                </div>                         
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
        </section>

        <section class="col-lg-7 col-md-7">
                    <div class="card info-box shadow-lg">
                        <div class="card-header">             
                            <div class="row"> 
                                <div class="col-12 col-md-8">
                                    <label for=""><h4>Hechos del Partido</h4></label>
                                </div>       
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                             <div class="row">                   
                                <div class="col-12 col-md-5" id="Equipos">
                                    <div class="form-group">                            
                                        <label for="inputCasa" class="form-label"><b>Hecho(*)</b></label> 
                                        <select class="form-control" id="Hecho"> 
                                          <?php 
                                                $parametro->DropDownHechosPartido();
                                            ?>
                                           
                                        </select>                       
                                    </div>
                                </div> 
                                <div class="col-10 col-md-6">
                                    <label for="inputName" class="form-label"><b>Jugador(*)</b></label>
                                    <input type="text" class="form-control" id ="nombreJugador" placeholder="Ingresar Nombre" disabled>                    
                                </div> 
                                <div class="col-1 col-md-1">
                                    <button type='button' class='btn btn-secondary btn-md checkbox-toggle' style="margin-top: 32px;" onclick="FiltroJugadoresEquipos();"><i title= 'Buscar' class='fas fa-search'></i></button>
                                </div>   
                               
                            </div>  
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <br>
                                    <button type='button' class='btn btn-primary btn-md checkbox-toggle btn-block' id="btn_nuevo_add" onclick="agregarHecho();">Agregar</button>
                                </div>   
                                <div class="col-md-12" id="cargando_add"></div>
                                <div class="col-md-6">               
                                    <input type="hidden" class="form-control" id ="idJugador">                                          
                                </div> 
                                <div class="col-md-6">               
                                    <input type="hidden" class="form-control" id ="nombreEquipo">                                          
                                </div> 
                            </div>
                            <div id="contenerdor_tabla" class="table-responsive">
                                <br>
                                <table id="example" class="table table-bordered table-striped" >
                                    <thead>
                                    <tr>                                     
                                        <th class="table-secondary">Hecho</th>
                                        <th class="table-secondary">Jugador</th>
                                        <th class="table-secondary">Equipo</th>
                                        <th class="table-secondary">Acción</th>
                                        <!-- <th style="display:none">idHecho</th> -->
                                        <th style="display:none">idHecho</th>
                                        <th style="display:none">idJugador</th>
                                    </tr>
                                    </thead>
                                    <tbody id="RellenarTabla">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </section>
            </div>
            <br>
        </section>


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
      </div>

           <?php $año = date('Y'); ?>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0
            </div>
            <strong>Copyright &copy; Software Bolivia <?php echo $año ?></strong> Todos los derechos reservados.
        </footer>
    
    </div>
    <!-- ./wrapper -->


<!--        MODAL         -->


 <!-- Modal datos jugador --> 
    <div class="modal fade" id="ModalDatosJugador">
        <div class="modal-dialog">
          <div class="modal-content modal-md">
            <div class="modal-header">
                <h4 class="modal-title" id="tituloJugador"><i class="fas fa-running"></i> Jugadores</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div id="contenerdor_tabla1" class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th></th>
                      <th>Nombre</th>
                      <th>Equipo</th>
                      <th>Nro Polera</th>
                    </tr>
                    </thead>
                    <tbody >
                      
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
 
  <script>


    function ActivarWalkover(){
      
      var walkover = $("#walkover").prop("checked");
     
      if(!walkover){
        $("#walkoverActivado").hide();
       // $("#Hecho").prop(disabled,false);
      
      }else{
        $("#walkoverActivado").show();
       // $("#Hecho").prop(disabled,true);
      }
     
    }

    function observar(){
      
      var observacion = $("#observarPartido").prop("checked");
     
      if(!observacion){
        $("#EquipoObservado").hide();
        $("#precio").hide();
      }else{
        $("#EquipoObservado").show();
        $("#precio").hide();
      }
     
    }

    function actualizarTablaPosiciones(){

      var idCampeonato = $("#idCampeonato").val();
      var equipo1 =$("#idEquipo1").val();
      var equipo2 = $("#idEquipo2").val();
      var equipo3 = $("#idEquipo3").val();
      var walkover = $("#walkover").prop("checked");
      var codProgramacion = '<?php echo $_GET["codProgramacion"]; ?>';

      if(!walkover){
        let count = 0;
          $("#example tbody#RellenarTabla tr").each(function() {
            count++;
          })

          if(count == 0){
            swal.fire('Sin Hechos..!','No ha ingresado ningun hecho del partido','warning')
            return false;
          }
      }

        let arreglo_idHecho = [];
        let arreglo_equipo = [];

        $("#example tbody#RellenarTabla tr").each(function() {
          arreglo_idHecho.push($(this).find('td').eq(4).text());
          arreglo_equipo.push($(this).find('td').eq(2).text());
        })

        let idHecho = arreglo_idHecho.toString();
        let equipo = arreglo_equipo.toString();

        
        $.ajax({
            url: 'clases/Cl_Partido.php?op=ActualizarTablaPosiciones',
            type: 'POST',
            data:{
              idCampeonato: idCampeonato,
              equipo1: equipo1,
              equipo2: equipo2,
              equipo3: equipo3,
              idHecho: idHecho,
              equipo: equipo,
              walkover: walkover
            },
            success: function(data) {
              if(data == "ok"){
                $("#btn_nuevo_add1").prop("disabled", false);
                $("#cargando_add1").html('');
                Swal.fire('Exito..!','Partido registrado correctamente!','success');
                if(codProgramacion != ""){
                  location.href="programacionPartidos.php";
                }
                else{
                  location.reload();
                }              
              }
              else{
                if(data == "1"){
                  Swal.fire('Oops..!','Error al actualizar la tabla del equipo local!','error');
                  $("#btn_nuevo_add1").prop("disabled", false);
                  $("#cargando_add1").html('');
                } 
                else{
                  if(data == "2"){
                    Swal.fire('Oops..!','Error al actualizar la tabla del equipo visitante!','error');
                    $("#btn_nuevo_add1").prop("disabled", false);
                    $("#cargando_add1").html('');
                  }
                }
              }       
            }            
          })   
    }

    function GuardarPartido(){

      $("#btn_nuevo_add1").prop("disabled", true);
      $("#cargando_add1").html('<div class="col-md-5 loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Guardando Partido ...</center></div>');

        var idPartido = $("#idPartido").val(); 
        var idCampeonato = $("#idCampeonato").val();
        var idSede = $("#idSede").val();
        var equipo1 =$("#idEquipo1").val();
        var equipo2 = $("#idEquipo2").val();
        var equipo3 = $("#idEquipo3").val();
        var fecha = $("#fecha").val();
        var modo = $("#Modo").val();
        var observacion = $("#observacion").val();
        var idEquipoObservado = $("#idEquipoObservado").val();
        var precioObservacion = $("#precioObservacion").val();
        var walkover = $("#walkover").prop("checked");
        var codProgramacion = '<?php echo $_GET["codProgramacion"]; ?>';
       
        if(equipo1 == "" || equipo2 == "" || fecha == "" || modo == ""){
            swal.fire("Campos Vacios..!","Debe completar todos los datos del Partido","warning");
            $("#btn_nuevo_add1").prop("disabled", false);
            $("#cargando_add1").html('');
            return false;
        }

        if(equipo1 == equipo2){
            swal.fire("Oopss..!","Debe completar todos los datos del Partido","warning");
            $("#btn_nuevo_add1").prop("disabled", false);
            $("#cargando_add1").html('');
            return false;
        }

        if(!walkover){
          let count = 0;
          $("#example tbody#RellenarTabla tr").each(function() {
            count++;
          })

          if(count == 0){
            swal.fire('Sin Hechos..!','No ha ingresado ningun hecho del partido','warning');
            $("#btn_nuevo_add1").prop("disabled", false);
            $("#cargando_add1").html('');
            return false;
          }
        }
        else{
          if(equipo3 == ""){
            swal.fire("Campos Vacios..!","Debe seleccionar al equipo ganador del walkover","warning");
            $("#btn_nuevo_add1").prop("disabled", false);
            $("#cargando_add1").html('');
            return false;
          }
        }
      
        
        let arreglo_idHecho = [];
        let arreglo_idJugador = [];
        let arreglo_equipo = [];

        $("#example tbody#RellenarTabla tr").each(function() {
          arreglo_idHecho.push($(this).find('td').eq(4).text());
          arreglo_idJugador.push($(this).find('td').eq(5).text());
          arreglo_equipo.push($(this).find('td').eq(2).text());
        })

        let idHecho = arreglo_idHecho.toString();
        let idJugador = arreglo_idJugador.toString();
        let equipo = arreglo_equipo.toString();

        $.ajax({                
                 url:'clases/Cl_Partido.php?op=RegistrarPartido',
                 type:'POST',
                 data:{
                    idPartido: idPartido,
                    idCampeonato: idCampeonato,
                    idSede: idSede,
                    equipo1: equipo1,
                    equipo2: equipo2,
                    fecha: fecha,
                    modo: modo,
                    observacion: observacion, 
                    idEquipoObservado: idEquipoObservado,
                    precioObservacion: precioObservacion,
                    walkover: walkover,

                    idHecho: idHecho,
                    idJugador: idJugador,
                    equipo: equipo,

                    codProgramacion: codProgramacion
                 },
                 error: function(){
                  
                 },
                 success: function(resp){  
          
                      if(resp == 1){
                        // Swal.fire('Exito..!','Partido registrado correctamente!','success');
                        if(modo == 'Clasificatoria'){
                          actualizarTablaPosiciones();
                        }
                        else{
                          Swal.fire('Exito..!','Partido registrado correctamente!','success');
                          <?php 
                            if(isset($_GET["idEquipo1"])) 
                            { ?>
                              location.href="tablaPosiciones.php";
                              <?php 
                            } 
                            else{ ?>
                                location.reload();
                              <?php 
                            } ?>
                        }
                      }
                      else{
                          if(resp == 3){

                            Swal.fire('Error!','Ha ocurrido un error al registrar el partido!','error');
                            $("#btn_nuevo_add1").prop("disabled", false);
                            $("#cargando_add1").html('');
                        }  
                        else{
                          if(resp == 2){
                            Swal.fire('Error!','Ha ocurrido un error al registrar los hechos del partido!','error');
                            $("#btn_nuevo_add1").prop("disabled", false);
                            $("#cargando_add1").html('');
                          }  
                          else{
                            if(resp == 4){
                              Swal.fire('Advertencia!','El torneo a finalizado, ya no se puede registrar mas partidos!','warning');
                              $("#btn_nuevo_add1").prop("disabled", false);
                            $("#cargando_add1").html('');
                            }           
                          }               
                        }            
                      }           
                 }
            });             
    }

    function agregarHecho(){

            $("#btn_nuevo_add").prop("disabled", true);
            $("#cargando_add").html('<div class="co-md-12 loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Añadiendo ...</center></div>');

            var idJugador = $("#idJugador").val();
            var nombre = $("#nombreJugador").val();
            var Equipo = $("#nombreEquipo").val();
            var idHecho = $("#Hecho").val();

            var combo = document.getElementById("Hecho");
            var Hecho = combo.options[combo.selectedIndex].text;

            if(nombre == "" || idHecho ==null){
                Swal.fire("Campos Vacios","Debe completar todos los campos","warning");
                $("#btn_nuevo_add").prop("disabled", false);
                $("#cargando_add").html('');
                return false;
            }
            

            let datos_agregar = "<tr>";
            datos_agregar+="<td for='id'>"+Hecho+"</td>";
            datos_agregar+="<td>"+nombre+"</td>";
            datos_agregar+="<td>"+Equipo+"</td>";
            datos_agregar+="<td><button class='btn btn-danger' onclick='Remover(this)'><i title='Eliminar' class='nav-icon fas fa-trash-alt'></button></td>";
            datos_agregar+="<td style='display:none'>"+idHecho+"</td>";
            datos_agregar+="<td style='display:none'>"+idJugador+"</td>";
            $("#RellenarTabla").append(datos_agregar);

            $("#btn_nuevo_add").prop("disabled", false);
            $("#cargando_add").html('');

            $("#idJugador").val("");
            $("#nombreJugador").val("");
            $("#nombreEquipo").val("");
            $("#Hecho").val(0);
    }

      function Remover(id){
        var td = id.parentNode;
        var tr = td.parentNode;
        var table = tr.parentNode;
        table.removeChild(tr);
      }


      function FiltroJugadoresEquipos(){

        var idequipo1 = $("#idEquipo1").val();
        var idequipo2 = $("#idEquipo2").val();
        var idHecho = $("#Hecho").val();

        if(idequipo1 == null || idequipo2 == null){
          swal.fire('Oopss..!','Debe seleccionar primero los equipos que estan jugando','warning');
          return false;
        }

        if(idequipo1 == idequipo2 ){
          swal.fire('Oopss..!','Debe seleccionar equipos diferentes','warning');
          return false;
        }

        if(idHecho == null || idHecho == 0){
          swal.fire('Oopss..!','Debe seleccionar primero un hecho','warning');
          return false;
        }
        
        $.ajax({
            url: 'clases/Cl_Partido.php?op=FiltroJugadoresEquipos',
            type: 'POST',
            data:{
              idequipo1: idequipo1,
              idequipo2: idequipo2
            },
            success: function(data) {
             
                $("#contenerdor_tabla1").html('');
                $('#example1').DataTable().destroy();
                $("#contenerdor_tabla1").html(data);
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');  

                $("#ModalDatosJugador").modal("show");
                          
            },
            error: function(jqXHR, textStatus, errorThrown) {
                       alert("Error AJAX: " +errorThrown);
            }          
          })   
        
      }

    function DatosJugador(id){

      $.ajax({
            url: 'clases/Cl_Partido.php?op=DatosJugador',
            type: 'POST',
            data:{
              idJugador : id
            },
            success: function(data) {
              if(data == "error"){
                Swal.fire("Error..!", "Error al traer los tados del jugador", "error");      
              }
              else{
                var resp= $.parseJSON(data);             
                $("#idJugador").val(resp.idJugador);
                $("#nombreJugador").val(resp.nombreJugador);
                $("#nombreEquipo").val(resp.nombreEquipo);
                $("#ModalDatosJugador").modal("hide");
              }              
            }            
          })   
    }

   

    function TorneoFinalizado(){
        $.ajax({
            url: 'clases/Cl_Partido.php?op=TorneoFinalizado',
            type: 'POST',
            success: function(data) {
              if(data == "error"){
                Swal.fire("Error..!", "Ha ocurrido un error al validar el registro de un equipo campeón", "error");      
              }
              else{
                if(data == "abierto"){
                  $("#AlertaTorneo").html("<span class='badge badge-white lef'>as</span>")
                }
                else{
                  if(data == "cerrado"){
                    $("#AlertaTorneo").html("<span class='badge badge-danger left'>Torneo Finalizado</span>");
                  }
                }
              }              
            }            
          })   
    }


    function CargarCampeonato(){
        // $.ajax({
        //     url: 'clases/Cl_Partido.php?op=UltimoTorneo',
        //     type: 'POST',
        //     success: function(data) {
        //       if(data == "error"){
        //         Swal.fire("Error..!", "Ha ocurrido un error al obtener el nombre del torneo actual", "error");      
        //       }
        //       else{
        //         var resp= $.parseJSON(data);
        //         $("#idCampeonato").val(resp.id); 
        //         $("#torneo").val(resp.nombre); 
        //       }              
        //     }            
        //   })   
    }

    function CargarSede(){
        // $.ajax({
        //     url: 'clases/Cl_Partido.php?op=UltimoSede',
        //     type: 'POST',
        //     success: function(data) {
        //       if(data == "error"){
        //         Swal.fire("Error..!", "Ha ocurrido un error al obtener el nombre de la sede actual", "error");      
        //       }
        //       else{
        //         var resp= $.parseJSON(data);
        //         $("#idSede").val(resp.id);
        //         $("#sede").val(resp.nombreSede);
        //       }              
        //     }            
        //   })   
    }
     
      </script>



<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" /> -->
    
<script>

$(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": lenguaje_español
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  })

  $(function () {

    $("#EquipoObservado").hide();
    $("#walkoverActivado").hide();
    $("#precio").hide();

    <?php 
    if(isset($_GET["idEquipo1"])) {?>
      
      $("#idEquipo1").val(<?php echo $_GET["idEquipo1"] ?>);
      $("#idEquipo1").prop("disabled",true);
      $("#idEquipo2").val(<?php echo $_GET["idEquipo2"] ?>);
      $("#idEquipo2").prop("disabled",true);
      <?php 
      if($_GET["modo"] == "Octavos"){ ?>
        $("#Modo").val("8vo de Final");      
        $("#Modo").prop("disabled",true);
      <?php 
      }
      else{
        if($_GET["modo"] == "Cuartos"){?>
          $("#Modo").val("4to de Final");      
          $("#Modo").prop("disabled",true);
        <?php 
        } 
        else{
          if($_GET["modo"] == "Semifinal"){?>
            $("#Modo").val("Semifinal");      
            $("#Modo").prop("disabled",true);
          <?php 
          } 
          else{
            if($_GET["modo"] == "Final"){?>
              $("#Modo").val("Final");      
              $("#Modo").prop("disabled",true);
          <?php 
            }
            else{
              if($_GET["modo"] == "Final2"){ ?>
              $("#Modo").val("3er y 4to Lugar");      
              $("#Modo").prop("disabled",true);
              <?php 
              } 
            }
          }
        }
      }
    }?>
   
      var fecha = '<?php echo $_GET['fecha'] ?>';
      if(fecha ==''){
        let hoy = new Date();
        document.getElementById("fecha").value = hoy.toJSON().slice(0,10);
      }
      else{
        $("#fecha").prop('disabled',true);
        $("#fecha").val(fecha);     
       
      }
         
    
})

  var lenguaje_español = 
    {
    "processing": "Procesando...",
    "lengthMenu": "Mostrar _MENU_ registros",
    "zeroRecords": "No se encontraron resultados",
    "emptyTable": "Ningún dato disponible en esta tabla", 
    "info":  "",
    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",   
    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
    "search": "Buscar:",
    "infoThousands": ",",
    "loadingRecords": "Cargando...",
    "paginate": {       
        "first": "Primero",
        "last": "Último",
        "next": "Siguiente",
        "previous": "Anterior"
    },
    "aria": {
        "sortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sortDescending": ": Activar para ordenar la columna de manera descendente"
    }
  }
  
</script>
</body>
</html>
