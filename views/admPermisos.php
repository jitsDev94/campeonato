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

if($parametro->verificarPermisos($_SESSION['idUsuario'],'32,33,34') == 0){
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
            <h1 class="m-0">Administración de Permisos</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

        <section class="col-lg-12 col-md-12 p-3">  
            <div class="card info-box shadow-lg">
              <div class="card-header">             
                <div class="row"> 
                    <div class="col-12 col-md-10">
                        <label for=""><h4>Listado de Permisos</h4></label>
                    </div>       
                    <div class="col-12 col-md-2">
                      <?php  if($parametro->verificarPermisos($_SESSION['idUsuario'],32) > 0){ ?>
                        <button type="button" class="btn btn-primary btn-block" onclick="modalNuevoPermiso();"><i class="fas fa-plus-circle"></i> Nuevo Permiso</button>                 
                        <?php } ?>
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
                              <th>Nombre Permiso</th>
                              <th>Menu</th>
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
    <div class="modal fade" id="modalNuevoPermiso">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"> Nuevo Permiso</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarLote" class="row g-3">                                                           
                    <div class="col-md-6">                   
                        <label for="" class="form-label">Nombre Permiso(*)</label>
                        <input type="text" class="form-control input" id ="nombrePermiso" placeholder="Ingresar Nombre Permiso">                                                                  
                    </div>  
                    <div class="col-md-6">                   
                        <label for="" class="form-label">Menus(*)</label>
                        <select id="txtMenu"  class="form-control input" onchange="cambiarMenu()">
                            <?php 
                            echo $parametro->listadoMenus();
                             ?>
                        </select>                                                                                         
                    </div>  
                    <div class="col-md-6" style='display:none' id='divNuevoNombreMenu'>                   
                        <label for="" class="form-label">Nuevo Menu(*)</label>
                        <input type="text" class="form-control input" id ="nuevoNombreMenu" placeholder="Ingresar Nombre Menu">                                                                  
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


       <!-- Modal nuevo/editar jugador --> 
    <div class="modal fade" id="modalEditarRol">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"> Editar Permiso</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarLote" class="row g-3">    
                  <input type="hidden" id='idRolEdit'>                                                       
                    <div class="col-md-12">                   
                        <label for="" class="form-label">Nombre Permiso(*)</label>
                        <input type="text" class="form-control input" id ="nombrePermisoEdit" placeholder="Ingresar Nombre Rol">                                                                  
                    </div>    
                    <div class="col-md-6" style='display:none'>                   
                        <label for="" class="form-label">Menus(*)</label>
                        <select id="txtMenuEdit"  class="form-control input" onchange="cambiarMenuEdit()">
                            <?php 
                            echo $parametro->listadoMenus();
                             ?>
                        </select>                                                                                         
                    </div>  
                    <div class="col-md-6" style='display:none' id='divNuevoNombreMenuEdit'>                   
                        <label for="" class="form-label">Nuevo Menu(*)</label>
                        <input type="text" class="form-control" id ="nuevoNombreMenuEdit" placeholder="Ingresar Nombre Menu">                                                                  
                    </div>                                                    
                </form>
            </div>
            <div class="modal-footer col-md-12">
                <button type="button" class="btn btn-primary" onclick="Editar()">Editar</button>  
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->

  <script>
  
    function cambiarMenuEdit(){
        var menu = $("#txtMenuEdit").val();   
        if(menu == 'otro'){
            $("#divNuevoNombreMenuEdit").show("slow");
        }
        else{
            $("#divNuevoNombreMenuEdit").hide("slow");
            $("#nuevoNombreMenuEdit").val('');  
        }
    }

    function cambiarMenu(){
        var menu = $("#txtMenu").val();   
        if(menu == 'otro'){
            $("#divNuevoNombreMenu").show("slow");
        }
        else{
            $("#divNuevoNombreMenu").hide("slow");
            $("#nuevoNombreMenu").val('');  
        }
    }
    function modalNuevoPermiso(){            
        $("#modalNuevoPermiso").modal("show");
    }

    function abrirModalEditarRol(idRol,nombrePermiso,menu){    
        $("#nombrePermisoEdit").val(nombrePermiso);   
        $("#idRolEdit").val(idRol);  
        $("#txtMenuEdit").val(menu); 
        $("#modalEditarRol").modal("show");
    }

    function ConfirmarDeshabilitar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Al Deshabilitarlo ya no sera visible!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Deshabilitar!'
        }).then((result) => {
                if (result.isConfirmed) {
                    EstadoPermiso(id,'1');                      
                }
        })
    }

    function ConfirmarHabilitar(id) {
        Swal.fire({
        title: 'Esta Seguro?',
        text: "Al Habilitarlo estara visible nuevamente!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Habilitar!'
        }).then((result) => {
                if (result.isConfirmed) {
                    EstadoPermiso(id,'0');                      
                }
        })
    }

    function EstadoPermiso(id,estado) {
         
         $.ajax({
         url: '../clases/Cl_Permisos.php?op=EstadoPermiso',
         type: 'POST',
         data: {
             id: id,
             estado: estado        
             }, 
             success: function(vs) {
                 if (vs == 'error') {
                    Swal.fire("Error..!", "ha ocurrido un error al deshabilitar", "error"); 
                 }else{
                    
                    if(estado == 'Habilitado'){
                      Swal.fire('Exito..!','Usuario habilitado correctamente.',  'success');                    
                     }
                     else{
                      Swal.fire('Exito..!','Usuario deshabilitado correctamente.',  'success');                     
                     }                                  
                     ListarPermisos();                      
                 }
             }
         })         
    }

    function guardarPermiso(idPermiso,idRol){

      $.ajax({
          url: '../clases/Cl_Permisos.php?op=guardarPermisoRol',
          type: 'POST',
          data: {
            idPermiso: idPermiso,
            idRol: idRol        
          }, 
          success: function(vs) {
              if (vs == 'error') {
                $("#chekPermisos"+idPermiso).prop("checked",false);
                Swal.fire("Error..!", "ha ocurrido un error al asignar el permiso", "error"); 
              }else{
                
                //Swal.fire('Exito..!','Usuario habilitado correctamente.',  'success');                    
                                  
              }
          }
      })         
    }
     function ListarPermisos(){             

      $("#contenerdor_tabla").html('<div class="col-md-5 loading"> <center><i class="fa fa-spinner fa-pulse fa-5x text-center"  style="color:#3c8dbc"></i><br/>Actualizando Roles...</center></div>');


        $.ajax({
            url: '../clases/Cl_Permisos.php?op=ListarPermisos',
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

     function Registrar() {
         
        var nombre = $('#nombrePermiso').val();
        var menu = $('#txtMenu').val();
        var nombreMenu = $('#nuevoNombreMenu').val();

        if(nombre == ""){
          Swal.fire("Campos Vacios..!", "Debe ingresar un nombre para el permiso", "warning");
          return false;
        }
       
        if(menu == ""){
          Swal.fire("Campos Vacios..!", "Debe seleccionar un menu", "warning");
          return false;
        }

        if(menu == "otro" && nombreMenu == ''){
          Swal.fire("Campos Vacios..!", "Debe ingresar un nombre para el nuevo Menu", "warning");
          return false;
        }

         $.ajax({
         url: '../clases/Cl_Permisos.php?op=RegistrarPermiso',
         type: 'POST',
         data: {
            nombre: nombre,
            menu: menu,
            nombreMenu: nombreMenu
             }, 
             success: function(vs) { 
              
                 if (vs == 'existe') {   
                   
                    Swal.fire('Error..!', 'Ya existe un permiso con ese nombre en el menu indicado, intente con otro nombre',  'error');
                                                              
                 }else{
                    //Swal.fire("Error..!", vs, "error");
                    if(menu == 'otro'){
                        location.reload();
                    }
                    else{
                        $('#nombrePermiso').val('');
                        $('#txtMenu').val('');
                        $('#nuevoNombreMenu').val('');
                        $("#modalNuevoPermiso").modal('hide');
                        Swal.fire('Exito!', 'Menu registrado correctamente',  'success');
                        ListarPermisos();
                    }
                  
                 }                  
             }
         })         
     }
     
     function Editar() {
         
         var nombre = $('#nombrePermisoEdit').val();
         var idPermiso = $('#idRolEdit').val();
         var menu = $('#txtMenuEdit').val();
         var nombreMenu = $('#nuevoNombreMenuEdit').val();

         if(nombre == ""){
           Swal.fire("Campos Vacios..!", "Debe ingresar un nombre para el rol", "warning");
           return false;
         }
        
         if(menu == ""){
          Swal.fire("Campos Vacios..!", "Debe seleccionar un menu", "warning");
          return false;
        }

        if(menu == "otro" && nombreMenu == ''){
          Swal.fire("Campos Vacios..!", "Debe ingresar un nombre para el nuevo Menu", "warning");
          return false;
        }

          $.ajax({
          url: '../clases/Cl_Permisos.php?op=EditarPermiso',
          type: 'POST',
          data: {
             nombre: nombre,
             id: idPermiso,
             menu: menu,
             nombreMenu: nombreMenu
              }, 
              success: function(vs) { 

                if (vs == 'existe') {   
                   
                   Swal.fire('Error..!', 'Ya existe un permiso con ese nombre en el menu indicado, intente con otro nombre',  'error');
                                                             
                }else{
                    if(menu == 'otro'){
                        location.reload();
                    }
                    else{
                        $('#nombrePermisoEdit').val('');
                        $('#txtMenuEdit').val('');
                        $('#nuevoNombreMenuEdit').val('');
                        $("#modalEditarRol").modal('hide');
                        Swal.fire('Exito!', 'Permiso modificado correctamente',  'success');
                        ListarPermisos();
                    }
                }               
              }
          })         
      }
     </script>

    <?php
      require "../template/piePagina.php";
      ?>

     <script>

  $(document).ready(function() {
   
    ListarPermisos();
    
  });



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
