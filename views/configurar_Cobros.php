<?php

require_once '../conexion/parametros.php';
$parametro = new parametros();

session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
} else {
    $idRol = $_SESSION['idRol'];   
    $usuario = $_SESSION['usuario'];  
    $idEquipoDelegado = $_SESSION['idEquipo'];
    $nombreEquipoDelegado= $_SESSION['nombreEquipo'];
}

if($parametro->verificarPermisos($_SESSION['idUsuario'],'3,28,29') == 0){
  echo "Su usuario no tiene permisos para entrar a esta pagina";
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
    require "../template/encabezado.php";
    ?>


   <style>
    .card{
      border-top-color: cornflowerblue;
      border-top-width: 3px;
    }
  </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" >
<div class="wrapper">


    <?php  
        require "../template/Navegador.php";
    ?>

    <?php  
        require "../template/Menus.php";
    ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Configuracion de Cobros o Ingresos</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

        <section class="col-lg-12 col-md-12"> <br>  
            <div class="card info-box shadow-lg">

                <div class="row"> 
                    <div class="col-12 col-md-10">
                        <label for=""><h4>Listado de Precios</h4></label>
                    </div>       
                    <div class="col-12 col-md-2">
                      <?php  if($parametro->verificarPermisos($_SESSION['idUsuario'],3) > 0){ ?>
                        <button type='button' class='btn btn-primary btn-block' onclick='AgregarPrecio()'><i class="fas fa-plus-circle"></i> &nbsp; Nuevo Precio</button>               
                        <?php } ?>
                    </div>   
                </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Motivo</th>
                      <th>Precio</th>            
                      <th>Accion</th>
                    </tr>
                    </thead>
                    <tbody id="contenerdor_tabla" >
                          
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

      <?php
    require "../template/footer.php";
    ?>
    </div>
    <!-- ./wrapper -->




<!--        MODAL         -->

   <!-- Modal para actualizar el precio --> 
    <div class="modal fade" id="modalActualizarPrecio">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"> Actualizar Precio</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">                             
                    <div class="col-md-12">
                        <label  class="form-label">Motivo Cobro</label>                      
                        <input type="text" readonly class="form-control" id="motivo">                       
                    </div>
                    <div class="col-md-12 mt-2">
                        <label class="form-label">Precio(*)</label>                        
                        <input type="number" class="form-control" id="precio" placeholder="Ingresar Precio">                        
                    </div>
                    <input type="hidden" class="form-control" id="id">
                </div>
            </div>
            <div class="modal-footer col-md-12">
            <button type="button" class="btn btn-primary" onclick="ActualizarPrecio()">Actualizar</button>  
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->

    <!-- Modal para crear nuevo precio --> 
    <div class="modal fade" id="modalCrearPrecio">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"> Nuevo Precio</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">                             
                    <div class="col-md-12">
                        <label  class="form-label">Motivo Cobro (*)</label>                      
                        <input type="text" class="form-control" id="txtmotivo" placeholder="Ingresar Motivo">                       
                    </div>
                    <div class="col-md-12 mt-2">
                        <label class="form-label">Precio (*)</label>                        
                        <input type="number" class="form-control" id="txtprecio" placeholder="Ingresar Precio">                        
                    </div>                   
                </div>
            </div>
            <div class="modal-footer col-md-12">
            <button type="button" class="btn btn-primary" onclick="CrearPrecio()">Registrar</button>  
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
  <script>
  
  function AgregarPrecio(id,motivo){     
      $("#modalCrearPrecio").modal("show");
    }


    function CrearPrecio() {
         
         var precio = $("#txtprecio").val();
         var motivo = $("#txtmotivo").val();
 
         if(motivo == ""){
             Swal.fire("Campos Vacios..!", "Debe ingresar el motivo del cobro", "warning");                     
             return false;
         }
 
         if(precio == ""){
             Swal.fire("Campos Vacios..!", "Debe ingresar el precio", "warning");                     
             return false;
         }

         
          $.ajax({
            url: '../clases/Cl_Configurar_Cobros.php?op=CrearPrecio',
            type: 'POST',
            data: {
              motivo: motivo,
              precio: precio        
            }, 
            success: function(vs) {
                $("#modalCrearPrecio").modal("hide");
                 if (vs == 'existe') {
                  Swal.fire("Alerta duplicidad..!", "Ya existe un cobro con ese nombre, favor intentar con otro nombre", "warning");                     
                 }else{     
                      $("#txtprecio").val('');
                      $("#txtmotivo").val('');            
                      Swal.fire('Exito..!','Precio creado correctamente.',  'success');
                      ListarCobros()                     
                 }                  
            }
          })         
      }

    function AbrirModalActualizarPrecio(id,motivo){
     
      $("#id").val(id); 
      $("#motivo").val(motivo); 
      $("#precio").val("");
      $("#modalActualizarPrecio").modal("show");
    }
  

      function ActualizarPrecio() {
         
        var precio = $("#precio").val();
        var id = $("#id").val();

        if(precio == ""){
            Swal.fire("Campos Vacios..!", "Debe ingresar el precio", "warning");                     
            return false;
        }


         $.ajax({
         url: '../clases/Cl_Configurar_Cobros.php?op=ActualizarPrecio',
         type: 'POST',
         data: {
             id: id,
             precio: precio        
             }, 
             success: function(vs) {
                $("#modalActualizarPrecio").modal("hide");
                //  if (vs == 2) {
                //   Swal.fire("Error..!", "ha ocurrido al actualizar el precio, favor intentar de nuevo mas tarde", "error");                     
                //  }else{                 
                      Swal.fire('Exito..!','Precio actualizado correctamente.',  'success');
                      ListarCobros()                     
                //  }                  
             }
         })         
     }


     function ConfirmarDeshabilitar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Al Deshabilitarlo ya no sera visible para realizar Cobros!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Deshabilitar!'
        }).then((result) => {
                if (result.isConfirmed) {
                  EstadoPrecio(id,'Deshabilitado');                      
                }
        })
    }

    function ConfirmarHabilitar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Al Habilitarlo estara visible nuevamente para realizar cobros!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Habilitar!'
        }).then((result) => {
                if (result.isConfirmed) {
                  EstadoPrecio(id,'Habilitado');                      
                }
        })
    }

    function EstadoPrecio(id,estado) {
         
         $.ajax({
         url: '../clases/Cl_Configurar_Cobros.php?op=EstadoPrecio',
         type: 'POST',
         data: {
             id: id,
             estado: estado        
             }, 
             success: function(vs) {
                //  if (vs == 'error') {
                //     Swal.fire("Error..!", "ha ocurrido un error al deshabilitar", "error"); 
                //  }else{
                    
                    if(estado == 'Habilitado'){
                      Swal.fire('Exito..!','Cobro habilitado correctamente.',  'success');                    
                     }
                     else{
                      Swal.fire('Exito..!','Cobro deshabilitado correctamente.',  'success');                     
                     }                                  
                     ListarCobros();                      
                // }
             }
         })         
    }

     function ListarCobros(){
        
        $.ajax({
            url: '../clases/Cl_Configurar_Cobros.php?op=ListarCobros',
            type: 'POST', 
            success: function(data) {
            
                $("#contenerdor_tabla").html('');
                $('#example1').DataTable().destroy();
                $("#contenerdor_tabla").html(data);
                $("#example1").DataTable({
                    "responsive": true, 
                    "lengthChange": false, 
                    "autoWidth": false,
                    "language": lenguaje_español
                });            
            }          
        })         
    }

  

     
      </script>

  <?php
    require "../template/piePagina.php";
    ?>


<script>
  $(function () {
    ListarCobros();
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
