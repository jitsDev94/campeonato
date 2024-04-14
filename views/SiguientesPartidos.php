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
  <title>Próximos de Partidos</title>
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
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="ListarPartidos();" >
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
          <div class="col-sm-10">
            <h1 class="m-0">Partidos de la Siguiente Fecha</h1>
          </div>             
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="col-lg-12 col-md-12">
        <div class="row"> 
        <section class="col-lg-6 col-md-6">
            <div class="card info-box shadow-lg">            
              <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-md-6 col-sm-6">
                        <label for="">Fecha Partidos</label>
                        <input type="date" class="form-control shadow-lg" id="fecha" readonly>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label for="">Cancha</label>
                        <input type="text" class="form-control shadow-lg" value="Cancha 1" readonly>
                    </div>
                </div>
                <div id="contenerdor_tabla" class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>                     
                      <th>Equipo Local</th>
                      <th>Equipo Visitante</th>
                      <th>Hora</th>                     
                    </tr>
                    </thead>
                    <tbody>
                           
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <br>
        </section>


        <section class="col-lg-6 col-md-6">
            <div class="card info-box shadow-lg">            
              <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-md-6 col-sm-6">
                        <label for="">Fecha Partidos</label>
                        <input type="date" class="form-control shadow-lg" id="fecha2" readonly>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label for="">Cancha</label>
                        <input type="text" class="form-control shadow-lg" value="Cancha 2" readonly>
                    </div>
                </div>
                <div id="contenerdor_tabla2" class="table-responsive">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>                     
                      <th>Equipo Local</th>
                      <th>Equipo Visitante</th>
                      <th>Hora</th>                     
                    </tr>
                    </thead>
                    <tbody>
                           
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <br>
        </section>

        </div>
        
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

   <!-- Modal nuevo/editar equipo --> 
    <div class="modal fade" id="ModalRegistrarProgramacionPartido">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloJugador">Programación de Partidos</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">                              
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Equipo Local(*)</b></label>
                        <select class="form-control shadow-lg" id="idEquipoLocal">
                            <option selected disabled>Seleccionar..</option>
                            <?php
                            $consultar = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";
                               $resultado1 = mysqli_query($conectar, $consultar);
                            while ($listado = mysqli_fetch_array($resultado1)) {
                            ?>
                                <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombreEquipo']; ?></option>
                            <?php } ?>
                        </select>           
                    </div>      
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Equipo Visitante(*)</b></label>
                        <select class="form-control shadow-lg" id="idEquipoVisitante">
                            <option selected disabled>Seleccionar..</option>
                            <?php
                            $consultar = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";
                               $resultado1 = mysqli_query($conectar, $consultar);
                            while ($listado = mysqli_fetch_array($resultado1)) {
                            ?>
                                <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombreEquipo']; ?></option>
                            <?php } ?>
                        </select>                
                    </div>  
                    <div class="col-md-6">
                    <br>
                        <label for="inputName" class="form-label"><b>Fecha Partido(*)</b></label>
                        <div class="input-group">                 
                            <input type="datetime-local" class="form-control shadow-lg" id ="fecha">                          
                        </div>                 
                    </div>        
                    <div class="col-md-6">
                    <br>
                        <label for="inputName" class="form-label"><b>Cancha(*)</b></label>                                                
                        <select class="form-control shadow-lg" id="txtCancha">
                            <option value="Cancha 1">Cancha 1</option>
                            <option value="Cancha 2">Cancha 2</option>
                            <option value="Cancha 3">Cancha 3</option>
                        </select>
                    </div>                                                              
              </form>
              <br>
                    <div id="cargando_add"></div>
                    <br> 
            </div>
            <div class="modal-footer col-md-12">
                <button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarProgramacionPartido()'>Registrar</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->


  <script>


     function ListarPartidos(){
        
      $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Partidos ...</center></div>');
      $("#contenerdor_tabla2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Partidos ...</center></div>');

      //partidos cancha 1
        $.ajax({
            url: 'clases/Cl_Programa_Partidos.php?op=ListarPartidos',
            type: 'POST', 
            data: {
                cancha : "Cancha 1"
            },
            success: function(data) {
                $("#contenerdor_tabla").html('');
                $('#example1').DataTable().destroy();
                $("#contenerdor_tabla").html(data);
                // $("#example1").DataTable({
                //     "responsive": true, "lengthChange": false, "autoWidth": false, "search": false,
                //     "language": lenguaje_español
                // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');            
            }          
        })  
        
        //partidos cancha 2
          $.ajax({
            url: 'clases/Cl_Programa_Partidos.php?op=ListarPartidos',
            type: 'POST',
            data: {
                cancha : "Cancha 2"
            },
            success: function(data) {
                $("#contenerdor_tabla2").html('');
                $('#example2').DataTable().destroy();
                $("#contenerdor_tabla2").html(data);
                // $("#example2").DataTable({
                //     "responsive": true, "lengthChange": false, "autoWidth": false, "search": false,
                //     "language": lenguaje_español
                // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');            
            }          
        })  
        

        $.ajax({
            url: 'clases/Cl_Programa_Partidos.php?op=datosProgramacionPartidos',
            type: 'POST',
            success: function(data) {
              if(data != ""){
                var resp= $.parseJSON(data);
                             
                    $("#fecha").val(resp.fecha); 
                    $("#fecha2").val(resp.fecha);   
                // Swal.fire("Error..!", "Ha ocurrido un error al obtener numero del ultimo partido", "error");      
              }
              else{                
                         
              }              
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
    $("#example1").DataTable({
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
