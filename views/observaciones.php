<?php

include 'clases/conexion.php';
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
} else {
    $idRol = $_SESSION['idRol'];   
    $usuario = $_SESSION['usuario'];  
    $idEquipoDelegado = $_SESSION['idEquipo'];
    $nombreEquipoDelegado= $_SESSION['nombreEquipo'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Observaciones</title>
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
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="CargarDatos();">
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
          <div class="col-sm-6">
            <h1 class="m-0">Observaciones Pendientes</h1>
          </div><!-- /.col
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div> /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
 
    <?php //if($idRol != 3) { ?>
        <section class="col-lg-12 col-md-12">
            <div id="accordion">
                <div class="card info-box shadow-lg">
                    <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Filtros Observaciones
                        </button>
                    </h5>
                    </div>
                    <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form role="form" method="post"  id="formFiltroVenta">
                                <div class="box-body">
                                    <div class="form-horizontal">  
                                        <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="inputCasa" class="form-label"><b>Equipo  </b></label> 
                                                <select class="form-control" id="filIdEquipos"> 
                                                    <?php 
                                                        $condicion ="";
                                                        if($idRol != 3){ ?>
                                                            <option value="0" selected>Todos</option>
                                                    <?php   }   ?>
                                                 
                                                    <?php 
                                                        $condicion ="";
                                                        if($idRol == 3){
                                                            $condicion =" and id = $idEquipoDelegado";
                                                        }
                                                        $selected = "selected";
                                                        $consultar = "SELECT * FROM Equipo where estado = 'Habilitado'  $condicion order by nombreEquipo asc";
                                                        $resultado1 = mysqli_query($conectar, $consultar);
                                                        while ($listado = mysqli_fetch_array($resultado1)){
                                                            ?>
                                                             <option value="<?php echo $listado['id']; ?>" <?php  if($idRol == 3 && $idEquipoDelegado == $listado['id'] ){ echo $selected;} ?>><?php echo $listado['nombreEquipo']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="inputCasa" class="form-label"><b>Torneo</b></label> 
                                                <select class="form-control" id="filIdTorneo"> 
                                                    <?php 
                                                        $consultar = "SELECT * FROM Campeonato";
                                                        $resultado1 = mysqli_query($conectar, $consultar);
                                                        while ($listado = mysqli_fetch_array($resultado1)){
                                                            ?>
                                                            <option value="<?php echo $listado['id'];?>"><?php echo $listado['nombre'];?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-top:32px;">
                                              <button type="button" class="btn btn-primary" onclick="FiltrarTarjetas()"><i class="fas fa-filter"></i>  Filtrar</button>
                                              <button type="button" class="btn btn-secondary" id="botonDirectivaActual" onclick="CargarDatos()"> Mostrar Actual</button>
                                            </div>
                                        </div>                                    
                                    </div>  
                                </div>                     
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php //} ?>
                <section class="col-lg-12 col-md-12"> <br>  
                    <div class="card info-box shadow-lg">
                    <div class="card-body">
                        <div id="contenerdor_tabla" class="table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Partido</th>
                                        <th>Sede</th>
                                        <th>Torneo</th>
                                        <th>Fecha</th>
                                        <th>Modo</th>
                                        <th>Observación</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    </div>
                </section>
          
            <br>
       
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

  

    <div class="modal" tabindex="-1" id='RechazarObservacion'>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Motivo del rechazo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class='row'>
                <input type="hidden" id='txtcodPartido'>
                <div class='col-md-12'>
                    <label for="">Motivo</label>
                    <textarea id="txtMotivoRechazo" rows="3" placeholder='Ingresar motivo del rechazo de la observación' class='form-control shadow-lg'></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick='AceptarRechazarObservacion("","Rechazado")'>Rechazar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>           
            </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id='AceptarObservacion'>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Catigo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class='row'>
                    <input type="hidden" id='txtcodPartido2'>
                    <input type="hidden" id='txtcodEquipoObservado'>
                    <div class='col-md-12 mb-3'>
                        <label for="">Tipo de Castigos</label>
                        <select class='form-control shadow-lg' id='txtCastigos' onclick='mostrarcastigos()'>
                            <option value="" selected disabled>Seleccionar..</option>
                            <option value="puntos">Quita de puntos en la tabla de posición</option>
                            <option value="goles">Goles en contra para el proximo partido</option>
                        </select>
                    </div>               
                    <div class='col-md-12' id='divQuitarPuntos' style='display:none;'>
                        <label for="">Cantidad de puntos a quitar</label>
                        <input placeholder='Ingresar los puntos que se quitaran' type='number' id='txtCantidadPuntos' class='form-control shadow-lg'>                   
                    </div>
                    <div class='col-md-12' id='divGolesContra' style='display:none;'>
                        <label for="">Cantidad de goles en contra</label>
                        <input placeholder='Ingresar los goles en contrar para el proximo partido' id='txtCantidadGoles' type='number' class='form-control shadow-lg'>                   
                    </div>
                    
                </div>  <br>
                <div class="row text-center">
                    <div class="col-md-12">                  
                        <div id="divAlertaCastigos"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id='btnAceptarObs' onclick='AceptarRechazarObservacion("","Aceptado")'>
                   
                    <span >Aceptar Observación</span>
                </button>
                <!-- <button type="button" class="btn btn-primary" id='btnAceptarObs'onclick='AceptarRechazarObservacion("","Aceptado")'>Aceptar Observación</button> -->
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>           
            </div>
            </div>
        </div>
    </div>


  <script>


        function CargarDatos(){
            $("#botonDirectivaActual").hide();
            ListaObservaciones();       
        }

        function RechazarObservacion(cod){
            $("#txtcodPartido").val(cod);
            $("#RechazarObservacion").modal('show');
          
        }
        
        function mostrarcastigos(){
            var castigo = $("#txtCastigos").val();

            if(castigo == 'puntos'){
                $("#txtCantidadGoles").val('');
                $("#divGolesContra").hide(500);
                $("#divQuitarPuntos").show(500);
            }
            if(castigo == 'goles'){
                $("#txtCantidadPuntos").val('');
                $("#divGolesContra").show(500);
                $("#divQuitarPuntos").hide(500);
            }

            $("#divAlertaCastigos").html('');
        }

        function ConfirmarRechazarObservacion(id) {

           
            Swal.fire({
            title: 'Rechazar Observación?',
            text: "No se procedera al respectivo castigo!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Rechazar!'
            }).then((result) => {
                    if (result.isConfirmed) {
                        AceptarRechazarObservacion(id,'Rechazado');                      
                    }
            })
        }

      function ConfirmarAceptarObservacion(id,equipoObservado) {
     
            $("#txtcodPartido2").val(id);
            $("#txtcodEquipoObservado").val(equipoObservado);

            $("#AceptarObservacion").modal('show');
      }

      function AceptarRechazarObservacion(id,estado) {

        if(id == ''){
          var id = $("#txtcodPartido2").val();
        }

        $("#divAlertaCastigos").html('');
        var codEquipoObservado = $("#txtcodEquipoObservado").val();
        var castigos = $("#txtCastigos").val();
        var puntos = $("#txtCantidadPuntos").val();
        var goles = $("#txtCantidadGoles").val();

        if(estado == 'Aceptado'){
           // $("#divAlertaCastigos").html('<div class="col-md-5 loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Guardando Partido ...</center></div>');
         

            if(castigos == "" || castigos == null){
                $("#divAlertaCastigos").html("<div class='alert alert-warning' role='alert'>Debe seleccionar un tipo de castigo</div>");                
                return false;
            }

            if(castigos =='puntos'){
                if(puntos == ''){
                    $("#divAlertaCastigos").html("<div class='alert alert-warning' role='alert'>Debe indicar las cantidad de puntos a descontar</div>");
                    return false;
                }
            }else{
                if(castigos =='goles'){
                    if(goles == ''){
                        $("#divAlertaCastigos").html("<div class='alert alert-warning' role='alert'>Debe indicar las cantidad de goles a descontar</div>");
                        return false;
                    }
                }
            }
        }

        $("#btnAceptarObs").prop("disabled",true);
        $("#btnAceptarObs").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span > Aceptar Observación</span>');
        var motivo = $("#txtMotivoRechazo").val();
         
         $.ajax({
         url: 'clases/Cl_Observaciones.php?op=AceptarObservacion',
            type: 'POST',
            data: {
                id: id,
                estado: estado,
                motivo: motivo,
                castigos:castigos,
                puntos:puntos,
                goles:goles,
                codEquipoObservado: codEquipoObservado
             }, 
             success: function(vs) {
                    $("#divAlertaCastigos").html('');
                    $("#btnAceptarObs").prop("disabled",false);
                    if (vs == 2) {
                    Swal.fire("Error..!", "ha ocurrido un error al aceptar la observación", "error");                     
                    }else{
                        if(vs== 1){
                            if(estado == "Aceptado"){
                                    if (vs == 3) {
                                    Swal.fire("Error..!", "ha ocurrido un error al configurar el castigo, intenta nuevamente", "error");                     
                                    }
                                    else{
                                        Swal.fire('Exito..!','Observación aceptada correctamente.', 'success');
                                        location.reload();
                                    }
                               
                            }
                            else{
                                Swal.fire('Exito..!','Observación rechazada correctamente.', 'success');
                                location.reload();
                            }
                            
                        }           
                    }                  
             }
         })         
     }

     function ListaObservaciones(){
        
        $.ajax({
            url: 'clases/Cl_Observaciones.php?op=ListaObservaciones',
            type: 'POST', 
            success: function(data) {
                $("#contenerdor_tabla").html('');
                $('#example').DataTable().destroy();
                $("#contenerdor_tabla").html(data);   
                $("#botonDirectivaActual").hide(); 
                $("#example").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }          
        })         
    }

    function FiltrarTarjetas(){
        
      var idEquipos = $("#filIdEquipos").val();    
      var idCampeonato = $("#filIdTorneo").val();

        $.ajax({
            url: 'clases/Cl_Observaciones.php?op=FiltrarObservaciones',
            type: 'POST', 
            data:{
                idEquipos: idEquipos,
              idCampeonato: idCampeonato
            },
            success: function(data) {
                $("#contenerdor_tabla").html('');
                $('#example').DataTable().destroy();
                $("#contenerdor_tabla").html(data);  
                $("#example").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $("#botonDirectivaActual").show();
             
            }          
        })         
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
<script>

$(function () {
    $("#example2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": lenguaje_español
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  })

  $(function () {
    $("#example3").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": lenguaje_español
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  })

  var lenguaje_español = 
    {
    "processing": "Procesando...",
    "lengthMenu": "Mostrar _MENU_ registros",
    "zeroRecords": "No se encontraron resultados",
    "emptyTable": "Ningún dato disponible en esta tabla", 
    "info":  "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
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
