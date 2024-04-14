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
  <title>Jugadores</title>
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
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="ListaJugadores();" >
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
            <h1 class="m-0">Jugadores</h1>
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
 
      <?php if($idRol != 3){ ?>
        <section class="col-lg-12 col-md-12"><br>          
            <div id="accordion">
                <div class="card info-box shadow-lg">
                    <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Filtros
                        </button>
                    </h5>
                    </div>
                    <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form role="form" method="post"  id="formFiltroVenta">
                                <div class="box-body">
                                    <div class="form-horizontal">  
                                        <div class="row">
                                        <div class="col-md-4">
                                        <label for="inputfecha" class="form-label">Nombre Jugador</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id ="filNombre" placeholder="Ingresar Nombre del Jugador">
                                            
                                        </div> 
                                        <br>                
                                        </div>   
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="inputCasa" class="form-label"><b>Nombre Equipo</b></label> 
                                                <select class="form-control" id="filEquipo"> 
                                                    <option value="0" selected>Seleccionar Equipo...</option>
                                                    <?php 
                                                      $consultar = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";
                                                      $resultado1 = mysqli_query($conectar, $consultar);
                                                      while ($listado = mysqli_fetch_array($resultado1)) {  
                                                        $equipos = utf8_encode($listado['nombreEquipo']);                                                  
                                                    ?>
                                                    <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombreEquipo']; ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-grid gap-2 d-md-flex" style="margin-top:32px;">
                                            <button type="button" class="btn btn-primary" id="btnFiltrar" onclick="FiltrarJugadores()"><i class="fas fa-filter"></i>  Filtrar</button>
                                            </div>
                                        </div>                                   
                                    </div>  
                                </div>                     
                            </form>
                            <br>
                            <div class="col-md-12" id="cargando_add1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
      <?php  } ?>

        <section class="col-lg-12 col-md-12"> <br>  
            <div class="card info-box shadow-lg">
            <?php if($idRol != 3){ ?>
              <div class="card-header">             
                <div class="row"> 
                    <div class="col-12 col-md-10">
                        <label for=""><h4>Listado de Jugadores</h4></label>
                    </div>       
                    <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-primary btn-block" onclick="modalRegistrarJugador()"><i class="fas fa-plus-circle"></i> Registrar Jugador</button>                 
                    </div>   
                </div>
              </div>
            <?php }?>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="contenerdor_tabla" class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Apellidos</th>
                      <th>Carnet</th>
                      <th>Fecha Nac.</th>                   
                      <th>Nro Camiseta</th>
                      <th>Equipo</th>
                      <th>Misión</th>
                      <th>Año Misión</th>
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

   <!-- Modal nuevo/editar jugador --> 
   <div class="modal fade" id="ModalRegistrarJugador">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloJugador"><i class="nav-icon fas fa-running"></i> Nuevo Jugador</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">                              
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Nombre(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            </div>
                            <input type="text" class="form-control" id ="nombre" placeholder="Ingresar Nombre">                          
                        </div>                 
                    </div> 
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Apellidos(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            </div>
                            <input type="text" class="form-control" id ="apellidos" placeholder="Ingresar Apellidos">                          
                        </div>                 
                    </div>                  
                    <div class="col-md-6">
                    <br>
                        <label for="inputName" class="form-label"><b>Carnet(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            </div>
                            <input type="text" class="form-control" id ="carnet" placeholder="Ingresar Carnet">                           
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
                <br>
                  <label for="inputName" class="form-label"><b>Numero Camiseta</b></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                    </div>
                    <input type="text" class="form-control" id ="nroCamiseta" placeholder="Nro de Camiseta">                  
                  </div>                 
                </div>             
                <div class="col-md-6" id="Equipos">
                      <div class="form-group">
                      <br>
                          <label for="inputCasa" class="form-label"><b>Equipo(*)</b></label> 
                          <select class="form-control" id="idEquipo"> 
                              <option value="0" selected disabled>Seleccionar Equipo...</option>
                              <?php 
                                $consultar = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";
                                $resultado1 = mysqli_query($conectar, $consultar);
                                while ($listado = mysqli_fetch_array($resultado1)) {   
                                  $equipos = utf8_encode($listado['nombreEquipo']);                                                 
                              ?>
                              <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombreEquipo']; ?></option>
                              <?php }?>
                          </select>                       
                      </div>
                </div>
             
                <div class="col-md-6">
                <br>
                  <label for="inputName" class="form-label"><b>Año Misión(*)</b></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                    </div>
                    <input type="text" class="form-control" id ="anoMision" placeholder="Año de Misión">                
                  </div>                 
                </div> 
                <div class="col-md-6">
                <br>
                  <label for="inputName" class="form-label"><b>Misión(*)</b></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                    </div>
                    <input type="text" class="form-control" id ="mision" placeholder="Nombre de Misión">                 
                  </div>                 
                </div> 
               
                <div class="col-md-6">               
                    <input type="hidden" class="form-control" id ="id">                                          
                </div> 
              </form>
              <br>
                <div class="col-md-12" id="cargando_add">                           
                </div>
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
       
        function modalRegistrarJugador(id) {    
          $("#tituloJugador").html("<i class='nav-icon fas fa-running'></i> Nuevo Jugador");  
          $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='btnRegistro' onclick='RegistrarJugador()'>Registrar</button>");
          $("#Equipos").show();
          $('#id').val("");
          $('#nombre').val("");
          $('#apellidos').val("");
          $('#carnet').val("");
          $('#fecha').val("");
          $('#nroCamiseta').val("");
          $('#anoMision').val("");
          $('#mision').val("");
          $('#ModalRegistrarJugador').modal('show');
        }


        function modalEditarJugador(id) {       

          $.ajax({
            url: 'clases/Cl_Jugador.php?op=DatosJugador',
            type: 'POST',
            data: {
                id: id
            }, 
            success: function(data) {
              if(data == ""){
                Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del jugador", "error");      
              }
              else{
                var resp= $.parseJSON(data);
                $("#tituloJugador").html("<i class='nav-icon fas fa-edit'></i> Editar Jugador");  
                $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarJugador()'>Editar</button>");
                $("#id").val(resp.id);           
                $("#nombre").val(resp.nombre);
                $("#apellidos").val(resp.apellidos);
                $("#carnet").val(resp.ci);
                $("#fecha").val(resp.fechaNacimiento);
                $("#nroCamiseta").val(resp.nroCamiseta);
                $("#Equipos").hide();
                $("#anoMision").val(resp.anoMision);
                $("#mision").val(resp.nombreMision);
                $('#ModalRegistrarJugador').modal('show');
              }              
            }            
          })               
        }

        function EditarJugador(){

        var id = $('#id').val();
        var nombre = $('#nombre').val();
        var apellidos = $('#apellidos').val();
        var carnet = $('#carnet').val();
        var fecha = $('#fecha').val();
        var nroCamiseta = $('#nroCamiseta').val();
        var idEquipo = $('#idEquipo').val();
        var anoMision = $('#anoMision').val();
        var mision = $('#mision').val();


        if(nombre == "" || apellidos == "" || carnet == "" || fecha == "" || idEquipo == "" || anoMision == "" || mision == ""){
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

         $.ajax({
         url: 'clases/Cl_Jugador.php?op=EditarJugador',
         type: 'POST',
         data: {
            id: id,
            nombre: nombre,
            apellidos: apellidos,
            carnet: carnet,
            fecha: fecha,
            nroCamiseta: nroCamiseta,
            idEquipo: idEquipo,
            anoMision: anoMision,
            mision  : mision
             }, 
             success: function(vs) {              
                 if (vs == 2) {                   
                    Swal.fire("Error..!", "Ha ocurrido un error al editar al jugador", "error");                     
                 }else{
                    if (vs == 1) {
                    Swal.fire('Exito!', 'Jugador editado correctamente',  'success');
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
          text: "Al deshabilitarlo ya no podra jugar en ningun equipo!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Deshabilitar!'
          }).then((result) => {
                  if (result.isConfirmed) {
                    EstadoJugador(id,'Deshabilitado');                      
                  }
          })
      }

      function ConfirmarHabilitar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Al Habilitarlo ya podra jugar en cualquier equipo!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Habilitar!'
        }).then((result) => {
                if (result.isConfirmed) {
                  EstadoJugador(id,'Habilitado');                      
                }
        })
      }

      function EstadoJugador(id,estado) {
         
         $.ajax({
         url: 'clases/Cl_Jugador.php?op=EstadoJugador',
         type: 'POST',
         data: {
             id: id,
             estado: estado        
             }, 
             success: function(vs) {
                 if (vs == 2) {
                  Swal.fire("Error..!", "ha ocurrido un error al deshabilitar", "error");                     
                 }else{
                   if(vs== 1){
                     if(estado == 'Habilitado'){
                      Swal.fire('Exito..!','Jugador habilitado correctamente.',  'success');
                      location.reload();
                     }
                     else{
                      Swal.fire('Exito..!','Jugador deshabilitado correctamente.',  'success');
                      location.reload();
                     }
                   }           
                 }                  
             }
         })         
     }
     function ListaJugadores(){
        
        $.ajax({
            url: 'clases/Cl_Jugador.php?op=ListaJugadores',
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

    function FiltrarJugadores(){
        
     

      var nombre = $("#filNombre").val();    
      var idEquipo = $("#filEquipo").val();

      if(nombre== "" && idEquipo == 0){
        
        Swal.fire('Aviso..!','Debe seleccionar una opcion de filtro','warning');
        return false;
      }

      $("#btnFiltrar").prop("disabled", true);
      $("#cargando_add1").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

        $.ajax({
            url: 'clases/Cl_Jugador.php?op=FiltrarJugadores',
            type: 'POST', 
            data:{
              nombre: nombre,
              idEquipo: idEquipo
            },
            success: function(data) {
              $("#btnFiltrar").prop("disabled", false);
              $("#cargando_add1").html('');
                $("#contenerdor_tabla").html('');
                $('#example1').DataTable().destroy();
                $("#contenerdor_tabla").html(data);
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');            
            },
            error: function(){
              $("#btnFiltrar").prop("disabled", false);
              $("#cargando_add1").html('<div class="alert alert-danger" role="alert">Algo salio mal!, por favor intente nuevamente</div>');
            }      
        })         
    }

     function RegistrarJugador() {
         
      
     
    
        var nombre = $('#nombre').val();
        var apellidos = $('#apellidos').val();
        var carnet = $('#carnet').val();
        var fecha = $('#fecha').val();
        var nroCamiseta = $('#nroCamiseta').val();
        var idEquipo = $('#idEquipo').val();
        var anoMision = $('#anoMision').val();
        var mision = $('#mision').val();


        if(nombre == "" || apellidos == "" || carnet == "" || fecha == "" || idEquipo == "" || anoMision == "" || mision == ""){
         
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

        $("#btnRegistro").prop("disabled", true);
        $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

         $.ajax({
         url: 'clases/Cl_Jugador.php?op=RegistrarJugador',
         type: 'POST',
         data: {
            nombre: nombre,
            apellidos: apellidos,
            carnet: carnet,
            fecha: fecha,
            nroCamiseta: nroCamiseta,
            idEquipo: idEquipo,
            anoMision: anoMision,
            mision  : mision
             }, 
             success: function(vs) {  
                 if (vs == 2) {                   
                    Swal.fire("Error..!", "Ha ocurrido un error al registrar al jugador", "error");                     
                 }else{
                    if (vs == 1) {
                      $("#cargando_add").html('');
                    Swal.fire('Exito!', 'Jugador registrado correctamente',  'success');
                    location.reload();
                    }
                 }                  
             },
             error: function(){
              $("#btnRegistro").prop("disabled", false);
              $("#cargando_add").html('<div class="alert alert-danger" role="alert">Algo salio mal!, por favor intente nuevamente</div>');
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
