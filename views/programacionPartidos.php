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

// if($parametro->verificarPermisos($_SESSION['idUsuario'],'14') == 0){
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
          <div class="col-sm-9">
            <h3 class="m-0">Partidos Programados</h3>
          </div>
          <div class="col-12 col-md-3">
              <button type="button" class="btn btn-primary btn-block" onclick="modalRegistrarProgramacionPartido()"><i class="fas fa-plus-circle"></i> Programar Partidos</button>                 
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
                <div id="contenerdor_tabla">
                 
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
    <div class="modal fade" id="ModalRegistrarProgramacionPartido">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloJugador">Programación de Partidos</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="formEditarLote" class="row g-3">                              
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Equipo Local(*)</b></label>
                        <select class="form-control shadow-lg" id="idEquipoLocal">
                          <?php 
                                $parametro->DropDownListarEquiposInscritos();
                            ?>
                        </select>           
                    </div>      
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Equipo Visitante(*)</b></label>
                        <select class="form-control shadow-lg" id="idEquipoVisitante">
                            <?php 
                                $parametro->DropDownListarEquiposInscritos();
                            ?>
                        </select>                
                    </div>  
                    <div class="col-md-6">
                    <br>
                        <label for="inputName" class="form-label"><b>Fecha Partido(*)</b></label>
                        <div class="input-group">                 
                            <input type="datetime-local" class="form-control shadow-lg" id ="fecha">                          
                        </div>                 
                    </div>        
                    <div class="col-md-6">
                    <br>
                        <label for="inputName" class="form-label"><b>Cancha(*)</b></label>                                                
                        <select class="form-control shadow-lg" id="txtCancha">
                            <option value="Cancha 1">Cancha 1</option>
                            <option value="Cancha 2">Cancha 2</option>                           
                        </select>
                    </div>                                                              
              </form>
              <br>
                    <div id="cargando_add"></div>
                    <br> 
            </div>
            <div class="modal-footer col-md-12">
                <button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarProgramacionPartido()'>Registrar</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->



   

  <script>

    
        function modalRegistrarProgramacionPartido() {    
          //$("#tituloJugador").html(" Programacipon de Partidos");  
          //$("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarProgramacionPartido()'>Registrar</button>");
          $("#id").val("");           
          $("#nombre").val("");
          $("#fecha").val("");
          $("#Equipos").show();
          $('#ModalRegistrarProgramacionPartido').modal('show');
        }

   


        function modalEditarEquipo(id) {       
            
          $.ajax({
            url: '../clases/Cl_Equipo.php?op=DatosEquipo',
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
                $('#ModalRegistrarProgramacionPartido').modal('show');
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
          url: '../clases/Cl_Equipo.php?op=EditarEquipo',
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
         function ModalRegistrarPartido(codProgramacion,codEquipo1,codEquipo2,fecha) {
        
          Swal.fire({
          title: '¿Esta Seguro?',
          text: "¿Seguro que desea registrar el partido?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Registrar!'
          }).then((result) => {
                  if (result.isConfirmed) {
                    registrarPartido(codProgramacion,codEquipo1,codEquipo2,fecha);                      
                  }
          })
      }

      function registrarPartido(codProgramacion,codEquipo1,codEquipo2,fecha) {
        var codEquipolocal = codEquipo1;
        var codEquipoVisitante = codEquipo2;
        var fechaPartido = fecha;
        location.href="partido.php?idEquipo1="+codEquipolocal+"&idEquipo2="+codEquipoVisitante+"&codProgramacion="+codProgramacion+"&fecha="+fechaPartido;
        //window.open("partido.php?idEquipo1="+codEquipolocal+"&idEquipo2="+codEquipoVisitante+"&codProgramacion="+codProgramacion);
     }


     function ModalEliminarPartido(codProgramacion,Equipo1,Equipo2) {
        
        Swal.fire({
        title: '¿Esta Seguro?',
        text: "¿Seguro que desea eliminar el partido entre "+Equipo1+" y "+Equipo2+"?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
        }).then((result) => {
                if (result.isConfirmed) {
                  EliminarPartido(codProgramacion);                      
                }
        })
    }

    function EliminarPartido(codProgramacion) {
      var codProgramacion = codProgramacion;
    
      $.ajax({
            url: '../clases/Cl_Programa_Partidos.php?op=eliminarPartidoProgramado',
            type: 'POST', 
            data: {
              codProgramacion: codProgramacion
            },
            success: function(data) {
                  if (data == "ok") {                               
                      Swal.fire('Exito!', 'Partido eliminado correctamente',  'success');
                      location.reload();                     
                  }else{
                    Swal.fire("Oops..!", data, "error");         
                     
                  }      
            }          
        })        
      
    }


     function ListaEquipos(){
        
      $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Equipos ...</center></div>');

        $.ajax({
            url: '../clases/Cl_Programa_Partidos.php?op=ListaEquipos2',
            type: 'POST', 
            success: function(data) {
                $("#contenerdor_tabla").html('');
             //   $('#example1').DataTable().destroy();
                $("#contenerdor_tabla").html(data);
                // $("#example1").DataTable({
                //     "responsive": true, 
                //     "lengthChange": false, 
                //     "autoWidth": false,
                //     "language": lenguaje_español
                // });            
            }          
        })         
    }

     function RegistrarProgramacionPartido(confirmacion = 0) {
         
        var idEquipoLocal = $('#idEquipoLocal').val();
        var idEquipoVisitante = $('#idEquipoVisitante').val();
        var fecha = $('#fecha').val();
        var Cancha = $('#txtCancha').val();

        if(idEquipoLocal == "0" || idEquipoLocal == ""){
          Swal.fire("Campos Vacios..!", "Debe seleccionar un equipo local", "warning");
          return false;
        }

        if(idEquipoVisitante == "0" || idEquipoVisitante == ""){
          Swal.fire("Campos Vacios..!", "Debe seleccionar un equipo visitante", "warning");
          return false;
        }

        if(idEquipoVisitante == "0" || idEquipoLocal == ""){
          Swal.fire("Campos Vacios..!", "Debe seleccionar equipos diferentes", "warning");
          return false;
        }

        if(fecha == "" ){
          Swal.fire("Campos Vacios..!", "Debe ingresar la fecha del partido", "warning");
          return false;
        }

        if(txtCancha == "" ){
          Swal.fire("Campos Vacios..!", "Debe seleccioanr la cancha del partido", "warning");
          return false;
        }

        $("#botonRegistro").prop("disabled", true);
                  
          $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
        
         $.ajax({
            url: '../clases/Cl_Programa_Partidos.php?op=RegistrarProgramacionPartido',
            type: 'POST',
            dataType: 'JSON',
            data: {
                idEquipoLocal: idEquipoLocal,
                idEquipoVisitante: idEquipoVisitante,
                fecha: fecha,
                Cancha: Cancha,
                confirmacion: confirmacion
             }, 
             success: function(vs) {   
              $("#botonRegistro").prop("disabled", false);
              setTimeout(() => {       
                $("#cargando_add").html('');
              }, 2000);


              if(vs['request'] == 'error'){
                Swal.fire('Advertencia!', vs['message'], 'warning');                
              }
              else{
                Swal.fire('Exito!','Partido programado correctamente',  'success');
                  location.reload();
              }
              // if(vs == 'fecha'){
              //   Swal.fire('Advertencia!', 'Hay partidos', 'warning');                
              // }
              // else
              // if(vs == 'local'){
              //   Swal.fire('Advertencia!', 'El equipo local ya tiene un partido programado', 'warning');                
              // }
              // else
              // if(vs == 'visitante'){
              //   Swal.fire('Advertencia!', 'El equipo visitante ya tiene un partido programado', 'warning');
              // }
              // else{
              //   Swal.fire('Exito!','Partido programado correctamente',  'success');
              //     location.reload();
              // }
                 
                //  if (vs == 1) {    
                //    Swal.fire('Exito!', 'Equipo registrado correctamente',  'success');
                //     location.reload();
                               
                //  }else{
                //     $("#botonRegistro").prop("disabled", false);
                //     $("#cargando_add").html('');                       
                //    // Swal.fire("Aviso..!", vs, "error");        
                //    Swal.fire({
                //     title: '¿Esta Seguro?',
                //     text: vs + ",¿Desea Continuar?",
                //     icon: 'question',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Si, Continuar!'
                //     }).then((result) => {
                //             if (result.isConfirmed) {
                //               RegistrarProgramacionPartido(1);                      
                //             }
                //     })
                //  }                  
             },
             error: function(vs){
                $("#botonRegistro").prop("disabled", false);
                $("#cargando_add").html('');
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
