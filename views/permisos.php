<?php

include '../conexion/conexion.php';
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuarios</title>
  <?php
    require "../template/encabezado.php";
    ?>

<style>
  .card {
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
          <div class="col-sm-6">
            <h1 class="m-0">Usuarios</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

        <section class="col-lg-12 col-md-12 p-3">  
            <div class="card info-box shadow-lg">
              <div class="card-header">             
                <div class="row"> 
                    <div class="col-12 col-md-10">
                        <label for=""><h4>Listado de Usuarios</h4></label>
                    </div>       
                    <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-primary btn-block" onclick="modalNuevoUsuario();"><i class="fas fa-plus-circle"></i> Nuevo Usuario</button>                 
                    </div>   
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                          <tr>
                              <th>#</th>
                              <th>Rol</th>
                              <th>Equipo</th>
                              <th>Usuario</th> 
                              <th>Estado</th>                 
                              <th>Acción</th>          
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

   <!-- Modal nuevo/editar jugador --> 
    <div class="modal fade" id="modalNuevoUsuario">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"> Nuevo Usuario</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarLote" class="row g-3">                              
                    <div class="col-md-6 mt-3">                       
                        <label for="" class="form-label">Rol(*)</label> 
                        <select class="form-control input" id="idRol">                           
                            <?php 
                              $parametro->DropDownListarRoles();                                                                                                
                            ?>                              
                        </select>                                              
                    </div>
                    <div class="col-md-6 mt-3">                                       
                          <label for="" class="form-label">Equipo(*)</label> 
                          <select class="form-control input" id="idEquipo1"> 
                              <option value="0" selected disabled>Seleccionar Equipo...</option>
                              <?php 
                               $parametro->DropDownListarEquiposInscritos();                                                                                  
                              ?>                              
                          </select>                                             
                    </div>             
                    <div class="col-md-6 mt-3">                   
                        <label for="" class="form-label">Usuario(*)</label>
                        <input type="text" class="form-control input" id ="usuario" placeholder="Ingresar Nombre Usuario">                                                                  
                    </div>                      
                    <div class="col-md-6 mt-3" style='display:none;'>                    
                      <label for="" class="form-label">Contraseña</label>                                        
                      <input type="text" class="form-control input" id="contra" placeholder="Ingresar contraseña">                                                      
                    </div>             
                </form>
            </div>
            <div class="modal-footer col-md-12">
            <button type="button" class="btn btn-primary" onclick="Registrar()">Registrar</button>  
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->

  <script>
  
    function modalNuevoUsuario(){
        $("#Equipos").hide();
        $("#modalNuevoUsuario").modal("show");
    }

    function mostrarEquipos(){
        var nombreRol = $("#idRol").val();
      
        if(nombreRol == 3){
            $("#Equipos").show();
        }
        else{
            $("#Equipos").hide();
        }
    }

    function ConfirmarDeshabilitar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Al Deshabilitarlo ya no podra ingresar al sistema!",
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
        text: "Al Habilitarlo ya podra ingresar al sistema!",
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
         url: '../clases/Cl_Usuarios.php?op=EstadoUsario',
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
                      Swal.fire('Exito..!','Usuario habilitado correctamente.',  'success');
                      location.reload();
                     }
                     else{
                      Swal.fire('Exito..!','Usuario deshabilitado correctamente.',  'success');
                      location.reload();
                     }
                   }           
                 }                  
             }
         })         
     }


     function ListarUsuarios(){             

      $("#contenerdor_tabla").html('<div class="col-md-5 loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Acualizando Usuarios...</center></div>');


        $.ajax({
            url: '../clases/Cl_Usuarios.php?op=ListarUsuarios',
            type: 'POST', 
            success: function(data) {
            
                $("#contenerdor_tabla").html('');
              //  $('#example1').DataTable().destroy();
                $("#contenerdor_tabla").html(data);
                $("#example1").DataTable({
                    "responsive": true, 
                    "lengthChange": false, 
                    "autoWidth": false,
                    "language": lenguaje_español
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');            
            }          
        })         
    }

  

     function Registrar() {
         
        var idRol = $('#idRol').val();
        var usuario = $('#usuario').val();
       
        var idEquipo = $('#idEquipo1').val();
        
        if(usuario == ""){
          Swal.fire("Campos Vacios..!", "Debe ingresar un nombre de usuario", "warning");
          return false;
        }

        if(idEquipo == null){
          Swal.fire("Campos Vacios..!", "Debe seleccionar un equipo", "warning");
          return false;
        }

         $.ajax({
         url: '../clases/Cl_Usuarios.php?op=RegistrarUsuario',
         type: 'POST',
         data: {
            idRol: idRol,
            usuario: usuario,           
            idEquipo: idEquipo
             }, 
             success: function(vs) { 
              $("#modalNuevoUsuario").modal('hide');
              Swal.fire('Exito!', 'Usuario registrado correctamente',  'success');
                ListarUsuarios();
                //  if (vs == 'ok') {   
                //     Swal.fire('Exito!', 'Usuario registrado correctamente',  'success');
                    
                                                                  
                //  }else{
                //     Swal.fire("Error..!", "Ha ocurrido un error al registrar el usuario. "+vs, "error");
                //  }                  
             }
         })         
     }
     
     </script>

    <?php
      require "../template/piePagina.php";
      ?>

     <script>

  $(document).ready(function() {
    console.log(window.jQuery);
    ListarUsuarios();
    
  });

  $(function () {   

   
  })

  var lenguaje_español = {
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
      },
      "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      "buttons": {
          "copy": "Copiar",
          "colvis": "Visibilidad"
      }
  };
  
</script>
</body>
</html>
