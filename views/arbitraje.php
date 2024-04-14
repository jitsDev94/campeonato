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

$consultar = "SELECT precio FROM configuracionCobros where motivo = 'Arbitraje'";
$resultado2 = mysqli_query($conectar, $consultar);
$row = $resultado2->fetch_assoc();
$precio = $row['precio'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Arbitraje</title>
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
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="CargarDatos();" >
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
                    <h1 class="m-0 pb-3">Arbitraje</h1>
                </div>
                <div class="col-12 col-sm-2">
                    <button type="button" class="btn btn-primary btn-block" onclick="ModalRegistrarPago()"><i class="fas fa-plus-circle"></i> Registrar Pago</button>                 
                </div> 
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
                      <th>Fecha</th>
                      <th>Total</th>
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

   <!-- Modal nuevo/editar pago arbitraje --> 
   <div class="modal fade" id="ModalRegistrarPago">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloJugador"><i class="nav-icon fas fa-dollar-sign"></i> Pago Arbitraje</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">                                                                        
                <div class="col-md-6">
                      <div class="form-group">
                   
                          <label for="inputCasa" class="form-label"><b>Equipo(*)</b></label> 
                          <select class="form-control shadow-lg" multiple id="idEquipo">                           
                              <?php 
                                $consultar = "SELECT i.idEquipo,e.nombreEquipo FROM Inscripcion as i 
                                left join Equipo as e on e.id = i.idEquipo 
                                left join Campeonato as c on c.id = i.idCampeonato 
                                where c.estado = 'En Curso' order by nombreEquipo asc";
                                $resultado1 = mysqli_query($conectar, $consultar);
                                $cont=0;
                                while ($listado = mysqli_fetch_array($resultado1)) {   
                                  $count++;
                                  $equipos = utf8_encode($listado['nombreEquipo']);                                                 
                              ?>
                              <option value="<?php echo $listado['idEquipo']; ?>"><?php echo $count; ?> - <?php echo $listado['nombreEquipo']; ?></option>
                              <?php }?>
                          </select>                       
                      </div>
                </div>         
                <div class="col-md-6">          
                  <label for="inputName" class="form-label"><b>Precio(*)</b></label>
                  <?php 
                          ?>
                    <input type="text" class="form-control shadow-lg" id ="precio" value="<?php echo $precio; ?>" placeholder="Precio albitraje">                      
                </div> 
                <div class="col-md-6">
                <br>
                    <label for="inputName" class="form-label"><b>Fecha(*)</b></label>
                    <div class="input-group">                 
                        <input type="date" class="form-control shadow-lg" id ="fecha">                          
                    </div>                 
                </div> 
                <div class="col-md-6">  
                <br>        
                  <label for="inputName" class="form-label"><b>Torneo(*)</b></label>
                  <input type="text" class="form-control shadow-lg" id ="torneo" disabled>                             
                </div>    
                <div class="col-md-6">               
                    <input type="hidden" class="form-control" id ="idCampeonato">                                          
                </div> 
              </form>
              <br>
              <div id="cargando_add"></div>
            </div>
            <div class="modal-footer col-md-12">
                <button type="button" class="btn btn-primary" id="btn_nuevo_add" onclick="RegistrarPago()">Registrar</button> 
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
        ListaPagosArbitraje();
      
    }

    function CargarCampeonato(){
        $.ajax({
            url: 'clases/Cl_Arbitraje.php?op=UltimoTorneo',
            type: 'POST',
            success: function(data) {
              if(data == "error"){
                Swal.fire("Error..!", "Ha ocurrido un error al obtener el nombre del torneo actual", "error");      
              }
              else{
                var resp= $.parseJSON(data);
                $("#idCampeonato").val(resp.id); 
                $("#torneo").val(resp.nombre); 
              }              
            }            
          })   
    }
       
        function ModalRegistrarPago(id) {    
            CargarCampeonato();
            let hoy = new Date();
            document.getElementById("fecha").value = hoy.toJSON().slice(0,10);
            $('#ModalRegistrarPago').modal('show');
        }

  
         //funcion que pide confirmacion al usuario para desabilitar un producto
         function ConfirmarDeshabilitar(id) {
        
          Swal.fire({
          title: 'Esta Seguro?',
          text: "Ya no podra recuperar la información del pago!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Eliminar!'
          }).then((result) => {
                  if (result.isConfirmed) {
                    EliminarPago(id);                      
                  }
          })
      }

      function EliminarPago(id) {
         
         $.ajax({
         url: 'clases/Cl_Arbitraje.php?op=EliminarPago',
         type: 'POST',
         data: {
             id: id     
             }, 
             success: function(vs) {
                 if (vs == 2) {
                  Swal.fire("Error..!", "ha ocurrido un error al eliminar el pago", "error");                     
                 }else{
                   if(vs== 1){
                        Swal.fire('Exito..!','Pago eliminado correctamente.',  'success');
                        location.reload();
                   }           
                 }                  
             }
         })         
     }

     function ListaPagosArbitraje(){
        
        $.ajax({
            url: 'clases/Cl_Arbitraje.php?op=ListaPagos',
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

  
     function RegistrarPago() {
         
        var idEquipo = $('#idEquipo').val();
        var precio = $('#precio').val();
        var fecha = $('#fecha').val();
        var idCampeonato = $('#idCampeonato').val();
        var cont = 0;

        if(precio == "" || fecha == ""){
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

        $("#btn_nuevo_add").prop("disabled", true);
        $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

        for (let index = 0; index < idEquipo.length; index++) {
        
          $.ajax({
              url: 'clases/Cl_Arbitraje.php?op=RegistrarPago',
              type: 'POST',
              data: {
                  idEquipo: idEquipo[index],
                  precio: precio,
                  fecha: fecha,
                  idCampeonato: idCampeonato
              }, 
              success: function(vs) {  
                $("#cargando_add").html('');
                $("#btn_nuevo_add").prop("disabled", false); 
                  if (vs == 2) {                   
                      Swal.fire("Error..!", "Ha ocurrido un error al registrar el pago de arbitraje", "error");                     
                  }else{
                      if (vs == 1) {
                        if(cont == idEquipo.length){
                          Swal.fire('Exito..!', 'Pago registrado correctamente','success');
                          location.reload();
                        }
                       
                      }
                      else{
                          if (vs == 3) {
                          Swal.fire('Advertencia..!', 'El equipo indicado ya pago arbitraje de esta fecha','warning');                     
                          }
                      }   
                  }                  
              }
          })   
          cont++;      
        }
        
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
