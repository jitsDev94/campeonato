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
  <title>Tarjetas</title>
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
    .amarillo{
      border-top-color: yellow;
      border-top-width: 3px;
    }
    .rojo{
      border-top-color: red;
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
            <h1 class="m-0">Tarjetas Pendientes</h1>
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
 

        <section class="col-lg-12 col-md-12">
            <div id="accordion">
                <div class="card info-box shadow-lg">
                    <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Filtros Tarjetas
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
                                                <label for="inputCasa" class="form-label"><b>Tipo de Tarjeta</b></label> 
                                                <select class="form-control" id="filTarjetas"> 
                                                    <option selected>Tarjeta Amarilla</option>
                                                    <option>Tarjeta Roja</option>
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

        <section class="col-lg-12 col-md-12">
            <div class="row">        
                <section class="col-lg-6 col-md-6"> <br>  
                    <div class="card amarillo info-box shadow-lg">
                    <div class="card-header">             
                        <div class="row"> 
                            <div class="col-12 col-md-12">
                                <label for=""><h4>Tarjetas Amarillas</h4></label>
                            </div>        
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="contenerdor_tabla2" class="table-responsive">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Equipo</th>
                                        <th>Fecha</th>
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

        <section class="col-lg-6 col-md-6"> <br>  
                    <div class="card rojo info-box shadow-lg">
                    <div class="card-header">             
                        <div class="row"> 
                            <div class="col-12 col-md-8">
                                <label for=""><h4>Tarjetas Rojas</h4></label>
                            </div>        
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="contenerdor_tabla3" class="table-responsive">
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Equipo</th>
                                        <th>Fecha</th>
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

  
      <!-- Modal cobrar Tarjetas --> 
   <div class="modal fade" id="ModalCobroTarjeta">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i class="nav-icon fas fa-dollar-sign"></i> Registrar Pago Tarjeta</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">                              
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-sm-3 col-form-label">Jugador</label>
                    <div class="col-sm-9 text-center">
                    <input type="text" readonly class="form-control-plaintext" id="nombre" disabled>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Precio</label>
                    <div class="col-sm-9">                    
                        <input type="text" class="form-control" id ="precio" placeholder="Ingresar Precio">                              
                    </div>
                </div>                 
                <div class="col-md-6">               
                    <input type="hidden" class="form-control" id ="id">                                          
                </div> 
              </form>
            </div>
            <div class="modal-footer col-md-12">
              <button type="button" class="btn btn-primary" onclick="RegistrarPagoTajeta()">Registrar</button>    
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
      
  <script>


        function CargarDatos(){
            $("#botonDirectivaActual").hide();
            ListaTarjetasRojas();
            ListaTarjetasAmarillas();
            
        }

        function modalCobrarTarjetaAmarilla(id) {       

          $.ajax({
            url: 'clases/Cl_Tarjetas.php?op=DatosTarjeta',
            type: 'POST',
            data: {
                id: id
            }, 
            success: function(data) {
              if(data == ""){
                Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del miembro de la directiva", "error");      
              }
              else{
                var resp= $.parseJSON(data); 
                var nombre = resp.nombre + ' ' +resp.apellidos;
                $("#id").val(id);           
                $("#nombre").val(nombre);
                ObtenerPrecioTarjetaAmarilla();
                $('#ModalCobroTarjeta').modal('show');
              }              
            }            
          })               
        }

        function ObtenerPrecioTarjetaAmarilla() {       

          $.ajax({
            url: 'clases/Cl_Tarjetas.php?op=precioTarjetaAmarilla',
            type: 'POST',
            success: function(data) {
              if(data == ""){
                Swal.fire("Error..!", "No se ha podido obtener el precio de la tarjeta amarilla, favor de colocarlo manualmente", "error");      
              }
              else{     
                $("#precio").val(data);
               
              }              
            }            
          })               
        }

        function modalCobrarTarjetaRoja(id) {       

          $.ajax({
            url: 'clases/Cl_Tarjetas.php?op=DatosTarjeta',
            type: 'POST',
            data: {
                id: id
            }, 
            success: function(data) {
              if(data == ""){
                Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del miembro de la directiva", "error");      
              }
              else{
                var resp= $.parseJSON(data); 
                var nombre = resp.nombre + ' ' +resp.apellidos;
                $("#id").val(id);           
                $("#nombre").val(nombre);
                ObtenerPrecioTarjetaRoja();
                $('#ModalCobroTarjeta').modal('show');
              }              
            }            
          })               
        }
        function ObtenerPrecioTarjetaRoja() {       

          $.ajax({
            url: 'clases/Cl_Tarjetas.php?op=precioTarjetaRoja',
            type: 'POST',
            success: function(data) {
              if(data == ""){
                Swal.fire("Error..!", "No se ha podido obtener el precio de la tarjeta roja, favor de colocarlo manualmente", "error");      
              }
              else{     
                $("#precio").val(data);          
              }              
            }            
          })               
        }

       

      function ConfirmarEliminar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Ya no podra cobrar esta tarjeta!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
        }).then((result) => {
                if (result.isConfirmed) {
                    EliminarTarjeta(id);                      
                }
        })
      }

      function EliminarTarjeta(id) {
         
         $.ajax({
         url: 'clases/Cl_Tarjetas.php?op=EliminarTarjetas',
         type: 'POST',
         data: {
             id: id    
             }, 
             success: function(vs) {
                 if (vs == 2) {
                  Swal.fire("Error..!", "ha ocurrido un error al eliminar la tarjeta", "error");                     
                 }else{
                   if(vs== 1){
                        Swal.fire('Exito..!','Tarjeta eliminada correctamente.', 'success');
                        location.reload();
                   }           
                 }                  
             }
         })         
     }

     function ListaTarjetasRojas(){
        
        $.ajax({
            url: 'clases/Cl_Tarjetas.php?op=ListaTarjetasRojas',
            type: 'POST', 
            success: function(data) {
                $("#contenerdor_tabla3").html('');
                $('#example3').DataTable().destroy();
                $("#contenerdor_tabla3").html(data);   
                $("#botonDirectivaActual").hide(); 
                $("#example3").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }          
        })         
    }


     function ListaTarjetasAmarillas(){
        
        $.ajax({
            url: 'clases/Cl_Tarjetas.php?op=ListaTarjetasAmarillas',
            type: 'POST', 
            success: function(data) {
                $("#contenerdor_tabla2").html('');
                $('#example2').DataTable().destroy();
                $("#contenerdor_tabla2").html(data);    
                    $("#example2").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }          
        })         
    }

    function FiltrarTarjetas(){
        
      var tarjetas = $("#filTarjetas").val();    
      var idCampeonato = $("#filIdTorneo").val();

        $.ajax({
            url: 'clases/Cl_Tarjetas.php?op=FiltrarTarjetas',
            type: 'POST', 
            data:{
              tarjetas: tarjetas,
              idCampeonato: idCampeonato
            },
            success: function(data) {
              if(tarjetas == "Tarjeta Amarilla"){
                $("#contenerdor_tabla2").html('');
                $('#example2').DataTable().destroy();
                $("#contenerdor_tabla2").html(data);  
                $("#example2").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $("#botonDirectivaActual").show();
              }
              else{
                $("#contenerdor_tabla3").html('');
                $('#example3').DataTable().destroy();
                $("#contenerdor_tabla3").html(data); 
                $("#example3").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)'); 
                $("#botonDirectivaActual").show();
              }
            }          
        })         
    }


    function RegistrarPagoTajeta() {
         
         var id = $('#id').val();
         var precio = $('#precio').val();

         if(precio == ""){
           Swal.fire("Campos Vacios..!", "Debe ingresar el precio de la tajeta", "warning");
           return false;
         }
 
          $.ajax({
          url: 'clases/Cl_Tarjetas.php?op=RegistrarPagoTarjeta',
          type: 'POST',
          data: {
             id: id,
             precio: precio
              }, 
              success: function(vs) { 
                  if (vs == 2) {                   
                     Swal.fire("Error..!", "Ha ocurrido un error al registrar el pago de la tarjeta", "error");                     
                  }else{
                     if (vs == 1) {
                     Swal.fire('Exito!', 'Pago registrado correctamente',  'success');
                     location.reload();
                     }
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
