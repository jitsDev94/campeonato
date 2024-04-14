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
  <title>Equipos</title>
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
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="ListaEquipos();" >
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
            <h1 class="m-0">Equipos</h1>
          </div>
          <div class="col-12 col-md-2">
              <button type="button" class="btn btn-primary btn-block" onclick="modalRegistrarEquipo()"><i class="fas fa-plus-circle"></i> Registrar Equipo</button>                 
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

        <section class="col-lg-12 col-md-12">
            <div class="card info-box shadow-lg">
            
              <div class="card-body">
                <div id="contenerdor_tabla" class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Equipo</th>
                      <th>Fecha Registro</th>
                      <th>Estado</th>
                      <th>Accion</th>
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
   <div class="modal fade" id="ModalRegistrarEquipo">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloJugador"><i class="nav-icon fas fa-users"></i> Nuevo Equipo</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">                              
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Nombre Equipo(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                            </div>
                            <input type="text" class="form-control" id ="nombre" placeholder="Ingresar Nombre">                          
                        </div>                 
                    </div>             
                    <div class="col-md-6">
                    <br>
                        <label for="inputName" class="form-label"><b>Fecha Nacimiento(*)</b></label>
                        <div class="input-group">                 
                            <input type="date" class="form-control" id ="fecha">                          
                        </div>                 
                    </div>
                                                    
                <div class="col-md-6">               
                    <input type="hidden" class="form-control" id ="id">                                          
                </div> 
              </form>
              <br>
                    <div id="cargando_add"></div>
                    <br> 
            </div>
            <div class="modal-footer col-md-12">
              <div id="botonRegistro"></div>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->


  <script>

    
        function modalRegistrarEquipo() {    
          $("#tituloJugador").html("<i class='nav-icon fas fa-users'></i> Nuevo Equipo");  
          $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarEquipo()'>Registrar</button>");
          $("#id").val("");           
          $("#nombre").val("");
          $("#fecha").val("");
          $("#Equipos").show();
          $('#ModalRegistrarEquipo').modal('show');
        }


        function modalEditarEquipo(id) {       
            
          $.ajax({
            url: 'clases/Cl_Equipo.php?op=DatosEquipo',
            type: 'POST',
            data: {
                id: id
            }, 
            success: function(data) {
              if(data == "error"){
                Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del Equipo", "error");      
              }
              else{
                var resp= $.parseJSON(data);
                $("#tituloJugador").html("<i class='nav-icon fas fa-edit'></i> Editar Equipo");  
                $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarEquipo()'>Editar</button>");
                $("#id").val(resp.id);           
                $("#nombre").val(resp.nombreEquipo);
                $("#fecha").val(resp.fechaRegistro);
                $('#ModalRegistrarEquipo').modal('show');
              }              
            }            
          })               
        }

        function EditarEquipo(){

        var id = $('#id').val();
        var nombre = $('#nombre').val();
        var fecha = $('#fecha').val();

        if(nombre == "" || fecha == ""){
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

         $.ajax({
         url: 'clases/Cl_Equipo.php?op=EditarEquipo',
         type: 'POST',
         data: {
            id: id,
            nombre: nombre,
            fecha: fecha
             }, 
             success: function(vs) {  
                
                 if (vs == 2) {                   
                    Swal.fire("Error..!", "Ha ocurrido un error al editar los datos del equipo", "error");                     
                 }else{
                    if (vs == 1) {
                    Swal.fire('Exito!', 'Equipo editado correctamente',  'success');
                    location.reload();
                    }
                 }                  
             }
         })         
        }
  
         //funcion que pide confirmacion al usuario para desabilitar un producto
         function ConfirmarDeshabilitar(id) {
        
          Swal.fire({
          title: 'Esta Seguro?',
          text: "Al deshabilitarlo ya no podra jugar en ningun campeonato!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Deshabilitar!'
          }).then((result) => {
                  if (result.isConfirmed) {
                    EstadoEquipo(id,'Deshabilitado');                      
                  }
          })
      }

      function ConfirmarHabilitar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Al Habilitarlo ya podra jugar en cualquier campeonato!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Habilitar!'
        }).then((result) => {
                if (result.isConfirmed) {
                    EstadoEquipo(id,'Habilitado');                      
                }
        })
      }

      function EstadoEquipo(id,estado) {
         
         $.ajax({
         url: 'clases/Cl_Equipo.php?op=EstadoEquipo',
         type: 'POST',
         data: {
             id: id,
             estado: estado        
             }, 
             success: function(vs) {
                 if (vs == 2) {
                  Swal.fire("Error..!", "ha ocurrido un error al deshabilitar al equipo", "error");                     
                 }else{
                   if(vs== 1){
                     if(estado == 'Habilitado'){
                      Swal.fire('Exito..!','Equipo habilitado correctamente.',  'success');
                      location.reload();
                     }
                     else{
                      Swal.fire('Exito..!','Equipo deshabilitado correctamente.',  'success');
                      location.reload();
                     }
                   }           
                 }                  
             }
         })         
     }
     function ListaEquipos(){
        
      $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Equipos ...</center></div>');

        $.ajax({
            url: 'clases/Cl_Equipo.php?op=ListaEquipos',
            type: 'POST', 
            success: function(data) {
                $("#contenerdor_tabla").html('');
                $('#example1').DataTable().destroy();
                $("#contenerdor_tabla").html(data);
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');            
            }          
        })         
    }

     function RegistrarEquipo() {
         
        var nombre = $('#nombre').val();
        var fecha = $('#fecha').val();

        if(nombre == "" || fecha == ""){
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

        $("#botonRegistro").prop("disabled", true);
        $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

         $.ajax({
         url: 'clases/Cl_Equipo.php?op=RegistrarEquipo',
         type: 'POST',
         data: {
            nombre: nombre,
            fecha: fecha
             }, 
             success: function(vs) {   
              $("#botonRegistro").prop("disabled", false);
                    $("#cargando_add").html('');           
                 if (vs == 2) {                   
                    Swal.fire("Error..!", "Ha ocurrido un error al registrar al equipo", "error");                     
                 }else{
                    if (vs == 1) {
                    Swal.fire('Exito!', 'Equipo registrado correctamente',  'success');
                    location.reload();
                    }
                 }                  
             },
             error: function(vs){
                $("#botonRegistro").prop("disabled", false);
                $("#cargando_add").html('');
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
