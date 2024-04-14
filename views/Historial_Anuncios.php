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
  <title>Historial Anuncios</title>
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
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="ListarAnuncios();" >
<div class="wrapper">

    <!-- Preloader
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="img/logo.png" alt="Software Bolivia" height="60" width="60">
    </div> -->
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
            <h1 class="m-0">Historial de Anuncios</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

        <section class="col-lg-12 col-md-12"> <br>  
            <div class="card info-box shadow-lg">
            
              <div class="card-body">
                <div id="contenerdor_tabla" class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Titulo</th>
                      <th>Detalle</th>  
                      <th>Publicado</th>
                      <th>Limite</th>
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


  <!-- Modal nueva anuncio -->
  <div class="modal fade" id="ModalRegistrarAnuncio">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> Editar Anuncio</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" id="formEditarLote" class="row g-3">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Titulo</label>
                <input type="email" class="form-control" id="titulo" placeholder="Ingresar el titulo">
              </div>
            </div><br>           
            <div class="col-md-6">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Limite Fecha</label>
                <input type="date" class="form-control" id="fechaLimite">
              </div>
            </div><br>
            <div class="col-md-12">
              <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Anuncio</label>
                <textarea class="form-control" id="detalle" rows="4" placeholder="Ingresar el detalle del anuncio"></textarea>
              </div>
            </div> <br> 
            <input type="hidden" class="form-control" id="id" disabled>
          </form>
        </div>
        <div class="modal-footer col-md-12">
          <button type="button" class="btn btn-primary" onclick="EditarAnuncio()">Actualizar</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

    <!-- Modal nueva anuncio -->
  <div class="modal fade" id="ModalHabilitarAnuncio">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> Habilitar Anuncio</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" id="formEditarLote" class="row g-3">
            <div class="col-md-12">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Titulo</label>
                <input type="text" class="form-control" id="titulo1" disabled>
              </div>
            </div><br>           
            <div class="col-md-12">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Fecha Limite</label>
                <input type="date" class="form-control" id="fechaLimite1">
              </div>
            </div>
            <input type="hidden" class="form-control" id="id1" disabled>
          </form>
        </div>
        <div class="modal-footer col-md-12">
          <button type="button" class="btn btn-primary" onclick="HabilitarAnuncio()">Habilitar</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <script>
  
  function ModalHabilitarAnuncio(id){
    $.ajax({
      url: 'clases/Cl_Historial_Anuncios.php?op=DatosAnuncio',
      type: 'POST',
      data: {
      id:id
      },
      success: function(vs) {
        if (vs == 'error') {
          Swal.fire("Error..!", "ha ocurrido un error al crear el anuncio", "error");
        } else {
        
            var resp= $.parseJSON(vs);
            $("#id1").val(id); 
            $("#titulo1").val(resp.titulo); 
            $("#ModalHabilitarAnuncio").modal("show");
          
        }
      }
    })
  }

  function ModalEditarAnuncio(id) {

          
    $.ajax({
      url: 'clases/Cl_Historial_Anuncios.php?op=DatosAnuncio',
      type: 'POST',
      data: {
      id:id
      },
      success: function(vs) {
        if (vs == 'error') {
          Swal.fire("Error..!", "ha ocurrido un error al crear el anuncio", "error");
        } else {
        
            var resp= $.parseJSON(vs);
            $("#id").val(resp.id); 
            $("#titulo").val(resp.titulo); 
            $("#detalle").val(resp.detalle); 
            $("#fechaLimite").val(resp.fechaLimite); 
            $("#ModalRegistrarAnuncio").modal("show");
          
        }
      }
    })

  }


  function EditarAnuncio(){

    var id = $("#id").val();
    var titulo = $("#titulo").val();
    var detalle = $("#detalle").val();
    var fechaLimite = $("#fechaLimite").val();
    let hoy = new Date();
    var fechaActual = hoy.toJSON().slice(0,10);

    if(titulo == "" || detalle == "" || fechaLimite == ""){
      Swal.fire("Campos Vacios..!", "Debe ingresar todos los campos requeridos", "warning");
      return false;
    }
    
    if(fechaLimite <= fechaActual){
      Swal.fire("Oops..!", "La fecha limite no puede ser menor o igual a la fecha actual", "warning");
      return false;
    }

    $.ajax({
      url: 'clases/Cl_Historial_Anuncios.php?op=EditarAnuncio',
      type: 'POST',
      data: {
      id:id,
      titulo: titulo,
      detalle: detalle,
      fechaLimite: fechaLimite,
      },
      success: function(vs) {
        if (vs == 2) {
          Swal.fire("Error..!", "ha ocurrido un error al actualizar el anuncio, intentar mas tarde", "error");
        } else {
          if (vs == 1) {
            Swal.fire('Exito..!', 'Anuncio actualizado correctamente.', 'success');
            location.reload();
          }
        }
      }
    })
  }

  function QuitarAnuncio(id){
      $.ajax({
        url: 'clases/Cl_Historial_Anuncios.php?op=QuitarAnuncio',
        type: 'POST',
        data: {
          id: id
        },
        success: function(vs) {
          if (vs == 2) {
            Swal.fire("Error..!", "ha ocurrido un error al crear el anuncio", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito..!', 'Anuncio quitado correctamente.', 'success');
              location.reload();
            }
          }
        }
      })
  }

  function HabilitarAnuncio(){

    var id = $("#id1").val();
    var fechaLimite = $("#fechaLimite1").val();

      $.ajax({
        url: 'clases/Cl_Historial_Anuncios.php?op=HabilitarAnuncio',
        type: 'POST',
        data: {
          id: id,
          fechaLimite
        },
        success: function(vs) {
          if (vs == 2) {
            Swal.fire("Error..!", "ha ocurrido un error al habiltiar el anuncio", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito..!', 'Anuncio habilitado correctamente.', 'success');
              location.reload();
            }
          }
        }
      })
    }

  function ConfirmarQuitarAnuncio(id) {
      Swal.fire({
        title: 'Esta Seguro?',
        text: "Al quitarlo ya no sera visible para los delegados!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Quitar!'
      }).then((result) => {
        if (result.isConfirmed) {
          QuitarAnuncio(id);
        }
      })
    }

   
  



     function ListarAnuncios(){
        
        $.ajax({
            url: 'clases/Cl_Historial_Anuncios.php?op=ListarAnuncios',
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
