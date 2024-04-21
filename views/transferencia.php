<?php


require_once '../conexion/parametros.php';
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
} else {
    $idRol = $_SESSION['idRol'];   
    $usuario = $_SESSION['usuario'];  
    $idEquipoDelegado = $_SESSION['idEquipo'];
    $nombreEquipoDelegado= $_SESSION['nombreEquipo'];
}

$parametro = new parametros();

$torneo = $parametro->TraerUltimoTorneo();
$cobros = $parametro->traerPrecio('Transferencias');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transferencias</title>
    
    <?php
    require "../template/encabezado.php";
    ?>
    <style>
        .card {
            border-top-color: cornflowerblue;
            border-top-width: 3px;
        }

        .card1 {
            border-top-color: cadetblue;
            border-top-width: 3px;
        }

        .card2 {
            border-top-color: green;
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
                        <div class="col-sm-12">
                            <h1 class="m-0">Transferencias de Jugadores</h1>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <!-- Transferencia -->
            <?php if($parametro->verificarPermisos($_SESSION['idUsuario'],12) > 0){ ?>
            <section class="col-lg-12 col-md-12 pr-4 pl-4"> <br>
                <div class="card info-box shadow-lg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 pr-2 d-flex justify-content-end">
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-4 col-4 col-form-label">
                                        <h5><b>Torneo</b></h5>
                                    </label>
                                    <div class="col-sm-8 col-8">
                                        <input type="text" class="form-control" id="torneo" value="<?php echo $torneo->nombre; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-2 col-4 col-form-label">
                                        <h5><b>Fecha</b></h5>
                                    </label>
                                    <div class="col-sm-4 col-8">
                                        <input type="date" class="form-control" id="fecha" value="<?php echo date('Y-m-d'); ?>" disabled>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <input type="hidden" class="form-control" id="idCampeonato" value="<?php echo $torneo->id; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <section class="col-md-5 card shadow-lg pl-4">
                                <br>
                                <h4 class="text-center">Datos Equipo Origen</h4><br>
                                <form id="datosJugadores" method="POST">
                                    <div class="mb-3 row m-2">
                                        <label for="inputPassword" class="col-sm-4 col-form-label">Nombre Equipo(*)</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="idEquipoOrigen" onchange="BuscarJugador($(this).val())">                                                                                          
                                                <?php 
                                                    $parametro->DropDownBuscarEquipos();
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row m-2">
                                        <label for="inputPassword" class="col-sm-4 col-form-label">Nombre Jugador(*)</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="idJugador" name="idJugador" >
                                            <!-- onchange="ObteneridJugador($(this).val())" -->
                                                <option value='0'>Seleccionar Jugador..</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="hidden" class="form-control" id="idJugador2">
                                    </div>
                                </form>
                                <br>
                            </section>
                            <section class="col-md-2"></section>
                            <section class="col-md-5 card shadow-lg pr-4">
                                <br>
                                <h4 class="text-center">Datos Equipo Destino</h4><br>
                                <div class="mb-3 row m-2">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Nombre Equipo(*)</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="idEquipoDestino">
                                        <option value='0'>Seleccionar Equipo..</option>
                                            <?php 
                                                    $parametro->DropDownListarEquiposInscritos();
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row m-2">                                      
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Precio(*)</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="precio" value="<?php echo $cobros->precio; ?>" placeholder="Precio de la transferencia">
                                    </div>
                                </div>
                                <br>
                            </section>
                        </div>
                        <br>
                        <div id="cargando_add"></div>
                        <br>
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-2 d-flex">
                                <button type="button" class="btn btn-primary flex-fill" id="btn_add" onclick="ConfirmarTransferencia()"><i class="fas fa-random"></i> Transferir</button>
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                        
                    </div>
                    <!-- /.card-body -->
                </div>
                <br>
            </section>
            <?php } ?>
            <!-- Filtros -->
            <section class="col-lg-12 col-md-12 pr-4 pl-4">
                <div id="accordion">
                    <div class="card info-box shadow-lg">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Filtros Historial Transferencias
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
                                                    <label for="inputPassword" class="form-label">Nombre Jugador</label>
                                                <input type="text" class="form-control" id="FilNombreJugador" placeholder="Ingresar nombre">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label"><b>Nombre Equipo Destino</b></label>
                                                    <select class="form-control" id="filIdEquipo">
                                                        <option value='0'>Seleccionar Equipo..</option>
                                                        <?php 
                                                            $parametro->DropDownListarEquiposInscritos();
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputCasa" class="form-label"><b>Nombre Torneo</b></label>
                                                        <select class="form-control" id="filIdCampeonato">
                                                            <?php 
                                                                $parametro->DropDownBuscarCampeonatos();
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div style="margin-top:32px;">
                                                        <button type="button" class="btn btn-primary" id="btn_add1" onclick="ListaTransferencias()"><i class="fas fa-filter"></i> Filtrar</button>
                                                        <!-- <button type="button" class="btn btn-secondary" id="botonDirectivaActual" onclick="ListaTransferencias()"> Mostrar Actual</button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                                <div id="cargando_add1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- lista -->
            <section class="col-lg-12 col-md-12 pr-4 pl-4"> <br>
                <div class="card info-box shadow-lg">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-9">
                                <label for="">
                                    <h4 id="lblTitulo">Historial Transferencias</h4>
                                </label>
                            </div>
                          
                        </div>
                    </div>
                    <!-- /.card-header -->
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

    <!-- Modal nuevo/editar inscripcion -->
    <div class="modal fade" id="ModalRegistrarInscripcion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloJugador"><i class="fas fa-file-signature"></i> Nueva Inscripción</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="formEditarLote" class="row g-3">
                        <div class="col-md-6">
                            <br>
                            <label for="inputName" class="form-label"><b>Nombre Torneo(*)</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-trophy"></i></span>
                                </div>
                                <input type="text" class="form-control" id="torneo" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label for="inputPassword" class="form-label"><b>Nombre Equipo(*)</b></label>
                            <select class="form-control" id="idEquipo">
                                    <?php 
                                        $parametro->DropDownBuscarEquipos();
                                    ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label for="inputName" class="form-label"><b>Inscripción(*)</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                <input type="text" class="form-control" id="monto" placeholder="Ingresar Monto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label for="inputName" class="form-label"><b>Fecha Pago(*)</b></label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="fecha">
                            </div>
                        </div>
                     
                        <div class="col-md-6">
                            <input type="hidden" class="form-control" id="idCampeonato" value="<?php echo $torneo->id; ?>">
                        </div>
                    </form>
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

        function ObteneridJugador(id){
       
          //  $("#idJugador").val(id);
        }

        function ConfirmarTransferencia() {
            Swal.fire({
                title: 'Esta Seguro?',
                text: "El jugador pasara a formar parte de otro equipo!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Transferir!'
            }).then((result) => {
                if (result.isConfirmed) {
                    TransferirJugador();
                }
            })
        }

        function TransferirJugador() {

            var idJugador = $("#idJugador").val();
            var idEquipoOrigen  = $("#idEquipoOrigen").val();
            var idEquipoDestino  = $("#idEquipoDestino").val();
            var fecha  = $("#fecha").val();
            var precio  = $("#precio").val();
            var idCampeonato = $("#idCampeonato").val();

            if(precio == ""){
                swal.fire('Campos Vacios..!','Debe ingresar el precio de la transferencia','warning');
                return false;
            }
            if(idJugador == "" || idJugador == 0 || idJugador == null){
                swal.fire('Campos Vacios..!','Debe seleccionar al jugador que sera transferido','warning');
                return false;
            }
            if(idEquipoDestino == "" || idEquipoDestino == 0){
                swal.fire('Campos Vacios..!','Debe seleccionar un equipo destino','warning');
                return false;
            }

            $("#btn_add").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

            $.ajax({
                url: '../clases/Cl_Transferencia.php?op=RegistrarTransferencia',
                type: 'POST',
                data: {
                    idJugador: idJugador,
                    idEquipoOrigen: idEquipoOrigen,
                    idEquipoDestino: idEquipoDestino,
                    fecha: fecha,
                    precio: precio,
                    idCampeonato: idCampeonato
                },
                success: function(vs) {
                    console.log(vs);
                    $("#btn_add").prop("disabled", false);
                    $("#cargando_add").html('');
                    
                       
                    if (vs == 3) {
                        Swal.fire('Error..!', 'Error al registrar la transferencia correctamente.', 'error');
                    }
                    else {
                        if (vs == 4) {
                            Swal.fire('Advertencia..!', 'Ya existe un jugador en el equipo destino con el mismo numero de camiseta, favor cambiar de nuemro y vuelva a intentar.', 'warning');
                        }
                        else{
                            Swal.fire('Exito..!', 'Jugador transferido correctamente.', 'success');
                            location.reload();
                        }
                    }
                                            
                },
                error: function(){
                    $("#btn_add").prop("disabled", false);
                    $("#cargando_add").html('<div class="alert alert-danger" role="alert">Algo salio mal!, por favor intente nuevamente</div>');
                }
            })
        }

        function BuscarJugador(id) {
            //var id = $("#idEquipoOrigen").val();
            
            $.ajax({
                url: '../clases/Cl_Transferencia.php?op=BuscarJugador',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Error..!", "Ha ocurrido un error al obtener la lista de jugadores", "error");
                    } else {
                        $("#idJugador").html('');
                        $("#idJugador").append(data);

                    }
                }
            })
        }
      

        function CargarDatos() {

            //ListaEquiposInscritos();
            //CargarCampeonato();
          
            ListaTransferencias();
        }

        function ListaTransferencias() {

            var idEquipo = $("#filIdEquipo").val();
            var idCampeonato = $("#filIdCampeonato").val();
            var nombreJugador = $("#FilNombreJugador").val();

            $("#btn_add1").prop("disabled", true);
            $("#cargando_add1").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
  
            $.ajax({
                url: '../clases/Cl_Transferencia.php?op=ListarTransferencias',
                type: 'POST',
                data: {
                    idEquipo: idEquipo,
                    idCampeonato: idCampeonato,
                    nombreJugador: nombreJugador
                },
                success: function(data) {

                    $("#cargando_add1").html('');
                    $("#btn_add1").prop("disabled", false); 

                    $("#contenerdor_tabla").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla").html(data);
                    // $("#botonDirectivaActual").hide();
                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                },
                error: function(data){
                    $("#cargando_add1").html('');
                    $("#btn_add1").prop("disabled", false); 
                }
            })
        }

      

    </script>

        <?php
      require "../template/piePagina.php";
      ?>

  
    <script>

        $(document).ready(function() {
          
            CargarDatos();
        
        });
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