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

if($parametro->verificarPermisos($_SESSION['idUsuario'],'7,20,21') == 0){
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
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse">
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
          <div class="col-sm-10">
            <h1 class="m-0">Equipos</h1>
          </div>
          <div class="col-12 col-md-2">
              <?php if($parametro->verificarPermisos($_SESSION['idUsuario'],7) > 0){ ?>
                <button type="button" class="btn btn-primary btn-block" onclick="modalRegistrarEquipo()"><i class="fas fa-plus-circle"></i> Registrar Equipo</button>                 
              <?php }?>
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

        <section class="col-lg-12 col-md-12 pl-3 pr-3">
            <div class="card info-box shadow-lg">
            
              <div class="card-body">
                <div id="contenerdor_tabla" class="table-responsive">
                
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


      <?php
      require "../template/footer.php";
      ?>
    
    </div>
    <!-- ./wrapper -->




<!--        MODAL         -->

   <!-- Modal nuevo/editar equipo --> 
   <div class="modal fade" id="ModalRegistrarEquipo">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloJugador">Nuevo Equipo</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">     
                  <input type="hidden" class="form-control" id ="id">                            
                    <div class="col-md-12">                      
                        <label for="inputName" class="form-label"><b>Nombre Equipo(*)</b></label>                     
                        <input type="text" class="form-control" id ="nombre" placeholder="Ingresar Nombre">                                                                 
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
          $("#tituloJugador").html("Nuevo");  
          $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarEquipo()'>Registrar</button>");
          $("#id").val("");           
          $("#nombre").val("");
          
          $("#Equipos").show();
          $('#ModalRegistrarEquipo').modal('show');
        }


        function modalEditarEquipo(id,nombreEquipo) {       
            
          $("#tituloJugador").html("Editar");  
          $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarEquipo()'>Editar</button>");
          $("#nombre").val(nombreEquipo);
          $("#id").val(id);
          $('#ModalRegistrarEquipo').modal('show');
       
        }

        function EditarEquipo(){

          var id = $('#id').val();
          var nombre = $('#nombre').val();
        
          if(nombre == ""){
            Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
            return false;
          }

          $("#botonRegistro").prop("disabled", true);
          $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

          $.ajax({
          url: '../clases/Cl_Equipo.php?op=EditarEquipo',
          type: 'POST',
          data: {
              id: id,
              nombre: nombre
            
              }, 
              success: function(vs) {  
                setTimeout(() => {
                  $("#botonRegistro").prop("disabled", false);
                  $("#cargando_add").html('');
                }, 2000);
              
                  if (vs == 'nombre') {                   
                      Swal.fire("Oops..!", "El nombre que esta ingresando ya esta registrado en otro equipo", "warning");                     
                  }else{
                    
                    $('#ModalRegistrarEquipo').modal('hide');
                    Swal.fire('Exito!', 'Equipo editado correctamente',  'success');
                    ListaEquipos();
                      
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
         url: '../clases/Cl_Equipo.php?op=EstadoEquipo',
         type: 'POST',
         data: {
             id: id,
             estado: estado        
             }, 
             success: function(vs) {
                
                if(estado == 'Habilitado'){
                Swal.fire('Exito..!','Equipo habilitado correctamente.',  'success');
                ListaEquipos();
                }
                else{
                Swal.fire('Exito..!','Equipo deshabilitado correctamente.',  'success');
                ListaEquipos();
                }
                            
             }
         })         
     }
     function ListaEquipos(){
        
      $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Equipos ...</center></div>');

        $.ajax({
            url: '../clases/Cl_Equipo.php?op=ListaEquipos',
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

     function RegistrarEquipo() {
         
        var nombre = $('#nombre').val();
     
        if(nombre == ""){
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

        $("#botonRegistro").prop("disabled", true);
        $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

         $.ajax({
         url: '../clases/Cl_Equipo.php?op=RegistrarEquipo',
         type: 'POST',
         data: {
            nombre: nombre
           
             }, 
             success: function(vs) {   
              $("#botonRegistro").prop("disabled", false);
                $("#cargando_add").html('');           
                 if (vs == 'nombre') {                   
                    Swal.fire("Oops..!", "Ya existe un equipo registrado con ese nombre", "warning");                     
                 }else{
                  $('#ModalRegistrarEquipo').modal('hide');
                    Swal.fire('Exito!', 'Equipo registrado correctamente',  'success');
                    ListaEquipos();                   
                 }                  
             },
             error: function(vs){
                $("#botonRegistro").prop("disabled", false);
                $("#cargando_add").html('');
                Swal.fire("Error..!", "Error inesperado", "error");     
             }
         })         
     }
      </script>

<?php
      require "../template/piePagina.php";
      ?>
       <script>

  $(function () {
    ListaEquipos();
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
