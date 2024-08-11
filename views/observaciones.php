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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
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
          <div class="col-sm-6">
            <h1 class="m-0">Observaciones Pendientes</h1>
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
 
    <?php //if($idRol != 3) { ?>
        <section class="col-lg-12 col-md-12">
            <div id="accordion">
                <div class="card info-box shadow-lg">
                    <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Filtros Observaciones
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
                                                <label for="inputCasa" class="form-label"><b>Equipo Observado </b></label> 
                                                <select class="form-control" id="filIdEquipos"> 
                                                    <?php 
                                                        $parametro->DropDownBuscarEquipos($idRol,$idEquipoDelegado);
                                                    ?>
                                                    <?php 
                                                        $condicion ="";
                                                        if($idRol != 3){ ?>
                                                            <option value="0" selected>Todos</option>
                                                    <?php   }   ?>
                                                 
                                                    <?php 
                                                        $condicion ="";
                                                        if($idRol == 3){
                                                            $condicion =" and id = $idEquipoDelegado";
                                                        }
                                                        // $selected = "selected";
                                                        // $consultar = "SELECT * FROM Equipo where estado = 'Habilitado'  $condicion order by nombreEquipo asc";
                                                        // $resultado1 = mysqli_query($conectar, $consultar);
                                                        // while ($listado = mysqli_fetch_array($resultado1)){
                                                            ?>
                                                             <!-- <option value="<?php echo $listado['id']; ?>" <?php  if($idRol == 3 && $idEquipoDelegado == $listado['id'] ){ echo $selected;} ?>><?php echo $listado['nombreEquipo']; ?></option> -->
                                                            <?php
                                                        //}
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="inputCasa" class="form-label"><b>Torneo</b></label> 
                                                <select class="form-control" id="filIdTorneo"> 
                                                <?php 
                                                                    $parametro->DropDownListarTorneos();
                                                                ?>
                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-top:32px;">
                                              <button type="button" class="btn btn-primary" onclick="FiltrarObservaciones()"><i class="fas fa-filter"></i>  Filtrar</button>                                              
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
        <?php //} ?>
                <section class="col-lg-12 col-md-12"> <br>  
                    <div class="card info-box shadow-lg">
                    <div class="card-body">
                        <div id="contenerdor_tabla" class="table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Partido</th>
                                        <th>Sede</th>
                                        <th>Torneo</th>
                                        <th>Fecha</th>
                                        <th>Modo</th>
                                        <th>Observación</th>
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
          
            <br>
       
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

  
    <div class="modal" tabindex="-1" id='modalVerObservacion'>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id='lblObservacion'>Motivo del Rechazo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class='row'>               
                <div class='col-md-6 mt-2'>
                    <label for="">Fecha Resolución</label>
                    <input  type='text' id='txtFechaRespuesta' class='form-control shadow-lg' readonly>                          
                </div>
                <div class='col-md-6 mt-2'>
                    <label for="">Usuario</label>
                    <input  type='text' id='txtUsuarioRespuesta' class='form-control shadow-lg' readonly>                   
                </div>
                <div class='col-md-12 mt-2'>
                    <label for="">Detalle</label>
                    <textarea id="txtMotivoRechazo_ver" rows="5" readonly class='form-control shadow-lg'></textarea>
                </div>

              </div>
            </div>
            <div class="modal-footer">               
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>           
            </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id='RechazarObservacion'>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Motivo del rechazo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class='row'>
                <input type="hidden" id='txtcodPartido'>
                <div class='col-md-12'>
                    <label for="">Motivo</label>
                    <textarea id="txtMotivoRechazo" rows="3" placeholder='Ingresar motivo del rechazo de la observación' class='form-control shadow-lg'></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick='AceptarRechazarObservacion("","Rechazado")'>Rechazar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>           
            </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id='AceptarObservacion'>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Catigo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class='row'>
                    <input type="hidden" id='txtcodObservacion'>
                    <input type="hidden" id='txtcodPartido2'>
                    <input type="hidden" id='txtcodEquipoObservado'>
                    <input type="hidden" id='txtEquipoLocal'>
                    <input type="hidden" id='txtEsquipoVisitante'>
                    <input type="hidden" id='txtEquipoObservado'>
                    <div class='col-md-12 mb-3'>
                        <label for="">Tipo de Castigos</label>
                        <select class='form-control shadow-lg' id='txtCastigos' onclick='mostrarcastigos()'>
                            <option value="" selected disabled>Seleccionar..</option>
                            <option value="puntos">Quita de puntos en la tabla de posición</option>
                            <!-- <option value="goles">Goles en contra para el proximo partido</option> -->
                        </select>
                    </div>               
                    <div class='col-md-12' id='divQuitarPuntos' style='display:none;'>
                        <div class="alert alert-warning" role='alert'>Se procedera a quitar los 3 puntos y se dara 2 goles en contra al equipo observado. ¿Desea Continuar?</div>
                        <!-- <label for="">Cantidad de puntos a quitar</label>
                        <input placeholder='Ingresar los puntos que se quitaran' type='number' id='txtCantidadPuntos' class='form-control shadow-lg'> -->
                    </div>
                    <div class='col-md-12' id='divGolesContra' style='display:none;'>
                        <label for="">Cantidad de goles en contra</label>
                        <input placeholder='Ingresar los goles en contrar para el proximo partido' id='txtCantidadGoles' type='number' class='form-control shadow-lg'>                   
                    </div>
                    
                </div>  <br>
                <div class="row text-center">
                    <div class="col-md-12">                  
                        <div id="divAlertaCastigos"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id='btnAceptarObs' onclick='AceptarRechazarObservacion("","Aceptado")'>
                   
                    <span >Aceptar Observación</span>
                </button>
                <!-- <button type="button" class="btn btn-primary" id='btnAceptarObs'onclick='AceptarRechazarObservacion("","Aceptado")'>Aceptar Observación</button> -->
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>           
            </div>
            </div>
        </div>
    </div>


  <script>

        function RechazarObservacion(cod){
            $("#txtcodPartido").val(cod);
            $("#RechazarObservacion").modal('show');
          
        }
        
        function mostrarcastigos(){
            var castigo = $("#txtCastigos").val();

            if(castigo == 'puntos'){
                $("#txtCantidadGoles").val('');
                $("#divGolesContra").hide(500);
                $("#divQuitarPuntos").show(500);
            }
            if(castigo == 'goles'){
                $("#txtCantidadPuntos").val('');
                $("#divGolesContra").show(500);
                $("#divQuitarPuntos").hide(500);
            }

            $("#divAlertaCastigos").html('');
        }

        function ConfirmarRechazarObservacion(id) {

           
            Swal.fire({
            title: 'Rechazar Observación?',
            text: "No se procedera al respectivo castigo!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Rechazar!'
            }).then((result) => {
                    if (result.isConfirmed) {
                        AceptarRechazarObservacion(id,'Rechazado');                      
                    }
            })
        }


        function verMotivoRechazo(motivo,fecha,usuario) {
     
            $("#txtMotivoRechazo_ver").html(motivo);
            $("#txtFechaRespuesta").val(fecha);
            $("#txtUsuarioRespuesta").val(usuario);
            $("#lblObservacion").html('Motivo del Rechazo');
            $("#modalVerObservacion").modal('show');
        }
        function verAprobacion(motivo,fecha,usuario) {
     
            $("#txtMotivoRechazo_ver").html(motivo);
            $("#txtFechaRespuesta").val(fecha);
            $("#txtUsuarioRespuesta").val(usuario);
            $("#lblObservacion").html('Detalle de la Aprobación');
            $("#modalVerObservacion").modal('show');
        }

      function ConfirmarAceptarObservacion(idObservacion,idPartido,codEquipoObservado,equipoLocal,esquipoVisitante,equipoObservado) {
     
            $("#txtcodObservacion").val(idObservacion);
            $("#txtcodPartido2").val(idPartido);
            $("#txtcodEquipoObservado").val(codEquipoObservado);
            $("#txtEquipoLocal").val(equipoLocal);
            $("#txtEsquipoVisitante").val(esquipoVisitante);
            $("#txtEquipoObservado").val(equipoObservado);

            $("#AceptarObservacion").modal('show');
      }

      function AceptarRechazarObservacion(id,estado) {

        if(id == ''){
          var id = $("#txtcodPartido2").val();
        }

        $("#divAlertaCastigos").html('');
        var codObservacion = $("#txtcodObservacion").val();        
        var codEquipoObservado = $("#txtcodEquipoObservado").val();
        var castigos = $("#txtCastigos").val();
        var castigosTexto = $("#txtCastigos option:selected").text();
        //var puntos = $("#txtCantidadPuntos").val();
        var puntos = 3;
        //var goles = $("#txtCantidadGoles").val();
        var goles = 2;
        var equipoLocal = $("#txtEquipoLocal").val();
        var esquipoVisitante = $("#txtEsquipoVisitante").val();
        var equipoObservado = $("#txtEquipoObservado").val();

        if(estado == 'Aceptado'){
           // $("#divAlertaCastigos").html('<div class="col-md-5 loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Guardando Partido ...</center></div>');         
            if(castigos == "" || castigos == null){
                $("#divAlertaCastigos").html("<div class='alert alert-warning' role='alert'>Debe seleccionar un tipo de castigo</div>");                
                return false;
            }

            // if(castigos =='puntos'){
            //     if(puntos == ''){
            //         $("#divAlertaCastigos").html("<div class='alert alert-warning' role='alert'>Debe indicar las cantidad de puntos a descontar</div>");
            //         return false;
            //     }
            // }else{
            //     if(castigos =='goles'){
            //         if(goles == ''){
            //             $("#divAlertaCastigos").html("<div class='alert alert-warning' role='alert'>Debe indicar las cantidad de goles a descontar</div>");
            //             return false;
            //         }
            //     }
            // }
        }

        $("#btnAceptarObs").prop("disabled",true);
        $("#divAlertaCastigos").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span >Aplicando Castigo</span>');
        var motivo = $("#txtMotivoRechazo").val();
         
         $.ajax({
         url: '../clases/Cl_Observaciones.php?op=AceptarObservacion',
            type: 'POST',
            data: {
                codObservacion: codObservacion,
                idPartido: id,
                estado: estado,
                motivo: motivo,
                castigos:castigos,                
                puntos:puntos,
                goles:goles,
                codEquipoObservado: codEquipoObservado,               
                equipoLocal:equipoLocal,
                esquipoVisitante: esquipoVisitante,
                equipoObservado: equipoObservado
             }, 
             success: function(vs) {
                    $("#divAlertaCastigos").html('');
                    $("#btnAceptarObs").prop("disabled",false);
                  
                    if(vs== 'ok'){
                        if(estado == "Aceptado"){                              
                            Swal.fire('Exito..!','Observación aceptada correctamente.', 'success');                                                                             
                        }
                        else{
                            Swal.fire('Exito..!','Observación rechazada correctamente.', 'success');                           
                        }

                        location.reload();
                    } 
                    else{
                        Swal.fire("Error..!", vs, "error");     
                    }          
                                      
             }
         })         
     }

   
    function FiltrarObservaciones(){
        
      var idEquipos = $("#filIdEquipos").val();    
      var idCampeonato = $("#filIdTorneo").val();

        $.ajax({
            url: '../clases/Cl_Observaciones.php?op=FiltrarObservaciones',
            type: 'POST', 
            data:{
                idEquipos: idEquipos,
              idCampeonato: idCampeonato
            },
            success: function(data) {
                $("#contenerdor_tabla").html('');
                $('#example').DataTable().destroy();
                $("#contenerdor_tabla").html(data);  
                $("#example").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "language": lenguaje_español
                });
               // $("#botonDirectivaActual").show();
             
            }          
        })         
    }
      </script>

    <?php
      require "../template/piePagina.php";
    ?>

<script>

$(function () {

    FiltrarObservaciones();     

    $("#example2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": lenguaje_español
    });
 
    $("#example3").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": lenguaje_español
    });

    
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
