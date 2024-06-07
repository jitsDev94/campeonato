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

$precio = $parametro->ObtenerPecioCobros('Inscripcion');
$torneo = $parametro->TraerUltimoTorneo();
if($parametro->verificarPermisos($_SESSION['idUsuario'],'14') == 0){
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
                            <h1 class="m-0">Inscripción de Equipos</h1>
                        </div>                   
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <section class="col-lg-12 col-md-12 pl-3 pr-3">
                <div id="accordion">
                    <div class="card info-box shadow-lg">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Filtros Historial Inscripciones
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form role="form" method="post" id="formFiltroVenta">
                                    <div class="box-body">
                                        <div class="form-horizontal">
                                            <div class="row">
                                            <div class="col-md-3">                                        
                                                <label for="inputPassword" class="form-label"><b>Nombre Equipo(*)</b></label>                                            
                                                <select class="form-control" id="filIdEquipo">
                                                    <?php 
                                                        $parametro->DropDownBuscarEquipos();
                                                    ?>
                                                </select>
                                            </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputCasa" class="form-label"><b>Nombre Torneo</b></label>
                                                        <select class="form-control" id="filCampeonato">
                                                            <?php 
                                                                $parametro->DropDownListarTorneos();
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-md-3">
                                                    <div style="margin-top:32px;">
                                                        <button type="button" class="btn btn-primary" id="btn_add1" onclick="ListaEquiposInscritos()"><i class="fas fa-filter"></i> Filtrar</button>                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                                <br><div id="cargando_add1"></div><br>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <section class="col-lg-12 col-md-12  pl-3 pr-3"> <br>
                <div class="card info-box shadow-lg">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-9 col-12 ">
                                <label for="">
                                    <h4 id="lblTitulo">Equipos Inscritos</h4>
                                </label>
                            </div>
                            <div class="col-lg-2 col-md-3 col-12 ">
                                <?php if($parametro->verificarPermisos($_SESSION['idUsuario'],8) > 0){ ?>
                                    <button type="button" class="btn btn-primary btn-block" onclick="ModalRegistrarInscripcion()"><i class="fas fa-plus-circle"></i> Nueva Inscripción</button>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Equipo</th>                                       
                                        <th>Fecha Inscripción</th>
                                        <th>Campeonato</th>
                                        <th>Pago Inscripción</th>
                                        <?php if($parametro->verificarPermisos($_SESSION['idUsuario'],'22,41') > 0){ ?>
                                        <th>Accion</th>
                                        <?php }?>
                                    </tr>
                                </thead>
                                <tbody id="contenerdor_tabla" >

                                </tbody>
                            </table>
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

 
    <!-- Modal nuevo/editar inscripcion -->
    <div class="modal fade" id="ModalRegistrarInscripcion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloJugador"> Nueva Inscripción</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="formEditarLote" class="row g-3">
                        <div class="col-md-6">
                            
                            <label for="inputName" class="form-label"><b>Nombre Torneo(*)</b></label>
                            <input type="text" class="form-control shadow-lg" id="torneo" disabled value="<?php echo $torneo->nombre; ?>">
                        </div>
                        <div class="col-md-6" id='divMonto'>                                                    
                            <label for="inputName" class="form-label"><b>Inscripción(*)</b></label>
                            <input type="text" class="form-control shadow-lg" id="monto" placeholder="Ingresar Monto" value='<?php echo $precio; ?>'>
                        </div>
                        <div class="col-md-6">
                          
                            <label for="inputPassword" class="form-label"><b>Nombre Equipo(*)</b></label>
                            <select class="form-control shadow-lg" id="idEquipo" multiple>
                                <?php 
                                    $parametro->DropDownBuscarEquipos();
                                ?>
                            </select>
                        </div>                                            
                        <div class="col-md-6">
                            <input type="hidden" class="form-control shadow-lg" id="idInscripcion">
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="form-control shadow-lg" id="idCampeonato" value="<?php echo $torneo->id; ?>">
                        </div>
                    </form>
                    <br><div id="cargando_add"></div>
                </div>
                <br>
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


        function ModalRegistrarInscripcion(id) {
            $("#idEquipo").prop("multiple",true);
         
           
            $("#tituloJugador").html("Nueva Inscripción");
            $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarInscripcion()'>Registrar</button>");
            $("#idInscripcion").val("");
            $("#divMonto").show();
            $('#ModalRegistrarInscripcion').modal('show');
        }

       

        function ModalEditarInscripcion(id,idEquipo) {

            $("#idEquipo").prop("multiple",false);
            $("#tituloJugador").html("Editar Inscripción");
            $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarInscripcion()'>Editar</button>");                      
            $("#idInscripcion").val(id);
            $("#idEquipo").val(idEquipo);
            $("#divMonto").hide();
            
            $('#ModalRegistrarInscripcion').modal('show');
        }

        function EditarInscripcion() {

            var id = $('#idInscripcion').val();
            var idEquipo = $('#idEquipo').val();
            var idCampeonato = $('#idCampeonato').val();

            if (idEquipo == "" || idEquipo == 0) {
                Swal.fire("Campos Vacios..!", "Debe seleccionar un equipo", "warning");
                return false;
            }

            $("#botonRegistro").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
           

            $.ajax({
                url: '../clases/Cl_Inscripcion.php?op=EditarEquipoInscrito',
                type: 'POST',
                data: {
                    idInscripcion: id,
                    idEquipo: idEquipo,
                    idCampeonato: idCampeonato
                },
                success: function(vs) {
                    setTimeout(() => {
                        $("#cargando_add").html('');
                        $("#botonRegistro").prop("disabled", false); 
                    }, 2000);
                    
                    if (vs == 'inscrito') {
                        Swal.fire('Advertencia!', 'El equipo ya esta registrado en este torneo', 'warning');
                                                    
                    } else {
                        Swal.fire('Exito!', 'Equipo modificado correctamente', 'success');
                        ListaEquiposInscritos();
                    }
                },
                error: function(vs){
                    Swal.fire('Ops!', 'Error inesperado', 'error');
                    $("#cargando_add").html('');
                    $("#botonRegistro").prop("disabled", false); 
                }
            })
        }

        function ListaEquiposInscritos() {

            var idEquipo = $("#filIdEquipo").val();
            var idCampeonato = $("#filCampeonato").val();

            $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Equipos ...</center></div>');

            $.ajax({
                url: '../clases/Cl_Inscripcion.php?op=ListaEquiposInscritos',
                type: 'POST',
                data: {
                    idEquipo: idEquipo,
                    idCampeonato: idCampeonato
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


        function RegistrarInscripcion() {

            var idEquipo = $('#idEquipo').val();
            var monto = $('#monto').val();        
            var idCampeonato = $('#idCampeonato').val();

            if (monto == "") {
                Swal.fire("Campos Vacios..!", "Debe ingresar el monto", "warning");
                return false;
            }   
                            
            if (idEquipo == "" || idEquipo == 0) {
                Swal.fire("Campos Vacios..!", "Debe seleccionar un equipo", "warning");
                return false;
            }                    
                        
            $("#botonRegistro").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

            for (let index = 0; index < idEquipo.length; index++) {
              
                $.ajax({
                    url: '../clases/Cl_Inscripcion.php?op=RegistrarInscripcion',
                    type: 'POST',
                    data: {
                        idEquipo: idEquipo[index],
                        monto: monto,
                       
                        idCampeonato: idCampeonato
                    },
                    success: function(vs) {
                        setTimeout(() => {
                            $("#cargando_add").html('');
                            $("#botonRegistro").prop("disabled", false); 
                        }, 2000);
                       
                        if (vs == 'inscrito') {
                            Swal.fire('Advertencia!', 'El equipo ya esta registrado en este torneo', 'warning');
                                                      
                        } else {
                            Swal.fire('Exito!', 'Equipo inscrito correctamente', 'success');
                            ListaEquiposInscritos();
                        }
                    },
                    error: function(vs){
                        Swal.fire('Ops!', 'Error inesperado', 'error');
                        $("#cargando_add").html('');
                        $("#botonRegistro").prop("disabled", false); 
                    }
                }) 
            }                       
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
                    EstadoInscripcion(id,'Deshabilitado');                      
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
                EstadoInscripcion(id,'Habilitado');                      
              }
      })
    }

    function EstadoInscripcion(id,estado) {
       
       $.ajax({
          url: '../clases/Cl_Inscripcion.php?op=EstadoInscripcion',
          type: 'POST',
          data: {
              id: id,
              estado: estado        
          }, 
          success: function(vs) {
          
            if(estado == 'Habilitado'){
                Swal.fire('Exito..!','Equipo habilitado correctamente.',  'success');                       
            }
            else{
                Swal.fire('Exito..!','Equipo deshabilitado correctamente.',  'success');                     
            }
            ListaEquiposInscritos();                             
          }
       })         
   }

    </script>

    <?php
      require "../template/piePagina.php";
      ?>
    <script>

        $(function () {

            ListaEquiposInscritos();


        })


        var lenguaje_español = {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
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