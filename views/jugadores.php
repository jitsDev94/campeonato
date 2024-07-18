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

// if($parametro->verificarPermisos($_SESSION['idUsuario'],'6,30,31') == 0){
//   echo "Su usuario no tiene permisos para entrar a esta pagina";
//   exit();
// }

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
 
      <?php if($idRol == 1 || $idRol == 2){ ?>
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
                                                    <?php 
                                                        $parametro->DropDownBuscarEquipos();
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-grid gap-2 d-md-flex" style="margin-top:32px;">
                                            <button type="button" class="btn btn-primary" id="btnFiltrar" onclick="ListaJugadores()"><i class="fas fa-filter"></i>  Filtrar</button>
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
            <?php if($parametro->verificarPermisos($_SESSION['idUsuario'],6) > 0){ ?>
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

    
   <!-- Modal nuevo/editar jugador --> 
      <div class="modal fade" id="ModalRegistrarJugador">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloJugador"> Nuevo Jugador</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">                              
                    <div class="col-md-6 mt-3">                    
                        <label for="inputName" class="form-label"><b>Nombre(*)</b></label>                                                 
                        <input type="text" class="form-control input" id ="nombre" placeholder="Ingresar Nombre">                                                               
                    </div> 
                    <div class="col-md-6 mt-3">                      
                        <label for="inputName" class="form-label"><b>Apellidos(*)</b></label>                       
                        <input type="text" class="form-control input" id ="apellidos" placeholder="Ingresar Apellidos">                                                                   
                    </div>                  
                    <div class="col-md-6 mt-3">                  
                        <label for="inputName" class="form-label"><b>Carnet(*)</b></label>                       
                        <input type="text" class="form-control input" id ="carnet" placeholder="Ingresar Carnet">                                                                 
                    </div>            
                    <div class="col-md-6 mt-3">
                   
                        <label for="inputName" class="form-label"><b>Fecha Nacimiento(*)</b></label>                                      
                        <input type="date" class="form-control input" id ="fecha">                                                                
                    </div>             
                <div class="col-md-6 mt-3">               
                  <label for="inputName" class="form-label"><b>Numero Camiseta</b></label>                
                  <input type="text" class="form-control input" id ="nroCamiseta" placeholder="Nro de Camiseta">                                                   
                </div>             
                <div class="col-md-6 mt-3" id="Equipos">                    
                  <label for="inputCasa" class="form-label"><b>Equipo(*)</b></label> 
                  <select class="form-control input" id="idEquipo"> 
                      <?php 
                          $parametro->DropDownBuscarEquipos();
                      ?>
                  </select>                                          
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
          $("#tituloJugador").html("Nuevo Jugador");  
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


        function modalEditarJugador(id,nombre,apellidos,ci,fechaNacimiento,nroCamiseta,idEquipo) {       

          $("#tituloJugador").html("Editar Jugador");  
          $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarJugador()'>Editar</button>");
          $("#id").val(id);           
          $("#nombre").val(nombre);
          $("#apellidos").val(apellidos);
          $("#carnet").val(ci);
          $("#fecha").val(fechaNacimiento);
          $("#nroCamiseta").val(nroCamiseta);
          $('#idEquipo').val(idEquipo);
          $("#Equipos").hide();
          
          $('#ModalRegistrarJugador').modal('show');          
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


        if(nombre == "" || apellidos == "" || carnet == "" || fecha == "" || anoMision == "" || mision == ""){
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

         $.ajax({
         url: '../clases/Cl_Jugador.php?op=EditarJugador',
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
              if (vs == 'nrocamiseta') {                   
                  Swal.fire("Alerta Nro Camiseta..!", "Ya existe un jugador con ese numero de camiseta, intentar con otro numero", "warning");
              }else{
                  if (vs == 'carnet') {
                  Swal.fire("Alerta Nro Carnet..!", "Ya existe un jugador con ese numero de carnet, intentar con otro numero", "warning");
                  }
                  else{
                  $('#ModalRegistrarJugador').modal('hide');  
                     Swal.fire('Exito!', 'Jugador editado correctamente',  'success');
                     ListaJugadores();
                //     location.reload();
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
            url: '../clases/Cl_Jugador.php?op=EstadoJugador',
            type: 'POST',
            data: {
                id: id,
                estado: estado        
            }, 
            success: function(vs) {
            
                    if(estado == 'Habilitado'){
                      Swal.fire('Exito..!','Jugador habilitado correctamente.',  'success');                       
                    }
                    else{
                      Swal.fire('Exito..!','Jugador deshabilitado correctamente.',  'success');                     
                    }
                    ListaJugadores();
                               
            }
         })         
     }


    function ListaJugadores(){
        
      var nombre = $("#filNombre").val();    
      var idEquipo = $("#filEquipo").val();
      var  idRol = <?php  echo $idRol; ?> ;
      var  idEquipoDelegado = <?php  echo $idEquipoDelegado; ?> ;
     
      $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Jugadores ...</center></div>');

        $.ajax({
            url: '../clases/Cl_Jugador.php?op=ListaJugadores',
            type: 'POST', 
            data:{
              nombre: nombre,
              idEquipo: idEquipo,
              idEquipoDelegado: idEquipoDelegado,
              idRol: idRol
            },
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

   

      function RegistrarJugador() {
                        
        var nombre = $('#nombre').val();
        var apellidos = $('#apellidos').val();
        var carnet = $('#carnet').val();
        var fecha = $('#fecha').val();
        var nroCamiseta = $('#nroCamiseta').val();
        var idEquipo = $('#idEquipo').val();
        var anoMision = $('#anoMision').val();
        var mision = $('#mision').val();


        if(nombre == "" || apellidos == "" || carnet == "" || fecha == "" || idEquipo == ""){
         
          Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
          return false;
        }

        $("#btnRegistro").prop("disabled", true);
        $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

         $.ajax({
         url: '../clases/Cl_Jugador.php?op=RegistrarJugador',
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
              $("#cargando_add").html('');
              $("#btnRegistro").prop("disabled", false);
                 if (vs == 'nrocamiseta') {                   
                    Swal.fire("Alerta Nro Camiseta..!", "Ya existe un jugador con ese numero de camiseta, intentar con otro numero", "warning");
                 }else{
                     if (vs == 'carnet') {
                      Swal.fire("Alerta Nro Carnet..!", "Ya existe un jugador con ese numero de carnet, intentar con otro numero", "warning");
                     }else{
                      $('#ModalRegistrarJugador').modal('hide');  
                        Swal.fire('Exito!', 'Jugador registrado correctamente',  'success');
                        ListaJugadores();
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

      <?php
      require "../template/piePagina.php";
      ?>
<script>
  $(function () {

    ListaJugadores();

    
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
