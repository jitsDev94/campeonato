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
    $nombreEquipoDelegado = $_SESSION['nombreEquipo'];
}
if($parametro->verificarPermisos($_SESSION['idUsuario'],'11,43,42') == 0){
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
                        <div class="col-sm-10 pb-3">
                            <h1 class="m-0">Multas</h1>
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
                                    <form role="form" method="post" id="formFiltroVenta">
                                        <div class="box-body">
                                            <div class="form-horizontal">
                                               
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputCasa" class="form-label"><b>Torneo</b></label>
                                                            <select class="form-control" id="filCampeonato">                                                              
                                                                <?php 
                                                                    $parametro->DropDownListarTorneos();
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php if($idRol != 3){ ?>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputCasa" class="form-label"><b>Nombre Equipo</b></label>
                                                            <select class="form-control" id="filEquipo">                                                                
                                                                <?php 
                                                                    $parametro->DropDownBuscarEquipos();
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <div class="col-md-3">
                                                        <div style="margin-top:32px;">
                                                            <button type="button" class="btn btn-primary" id="btn_add1" onclick="FiltrarMultas()"><i class="fas fa-filter"></i> Filtrar</button>
                                                            <!-- <button type="button" class="btn btn-secondary" id="botonDirectivaActual" onclick="ListarMultas()"> Mostrar Actual</button> -->
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
         


            <section class="col-lg-12 col-md-12">
                <div class="card info-box shadow-lg">
                <div class="card-header">    
                <div class="row"> 
                            <div class="col-12 col-md-10">
                                <label for=""><h4>Listado de Multas</h4></label>
                            </div>       
                            <div class="col-12 col-md-2">
                            <?php  if($parametro->verificarPermisos($_SESSION['idUsuario'],'11') > 0){ ?>
                                <button type="button" class="btn btn-primary btn-block" onclick="modalRegistrarMultas()"><i class="fas fa-plus-circle"></i> Registrar Multa</button>
                                <?php } ?>
                            </div>   
                        </div>
                           
                </div>
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

    <!-- Modal nuevo/editar multas -->
    <div class="modal fade" id="ModalRegistrarMultas">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloJugador">Nueva Multa</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="formEditarLote" class="row g-3">
                        <div class="col-md-6">                            
                            <label for="inputCasa" class="form-label"><b>Motivo Multa(*)</b></label>
                            <select class="form-control" id="motivoMulta">
                                <option selected>No asistio a la reunión</option>
                                <option>No presento veedor</option>
                            </select>                            
                        </div>
                        <div class="col-md-6">                           
                            <label for="inputCasa" class="form-label"><b>Nombre Equipo(*)</b></label>
                            <select class="form-control" id="idEquipo">
                                <?php 
                                    $parametro->DropDownListarEquiposInscritos();
                                ?>
                            </select>                            
                        </div>
                        <div class="col-md-6">                           
                            <label for="inputName" class="form-label"><b>Total(*)</b></label>                           
                            <input type="text" class="form-control" id="total" placeholder="Ingresar total multa">                           
                        </div>
                        <div class="col-md-6">                           
                            <label for="inputName" class="form-label"><b>Fecha Multa(*)</b></label>                           
                            <input type="date" class="form-control" id="fecha" value='<?php echo date('Y-m-d'); ?>'>                           
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="form-control" id="id">
                        </div>
                    </form>
                    <br><div id="cargando_add"></div>
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


        //funcion que pide confirmacion al usuario para desabilitar un producto
        function ConfirmarCobrarMulta(id) {

            Swal.fire({
                title: 'Esta Seguro?',
                text: "La multa quedara archivada y sumara los ingresos!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Cobrar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    CobrarMulta(id);
                }
            })
        }

        function CobrarMulta(id) {

            $.ajax({
                url: '../clases/Cl_Multa.php?op=CobrarMulta',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(vs) {
                    if (vs == 2) {
                        Swal.fire("Error..!", "ha ocurrido un error al cobrar la multa", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito..!', 'Multa cobrada correctamente.', 'success');
                            location.reload();
                        }
                    }
                }
            })
        }


        function modalRegistrarMultas() {
            obtenerprecio();
            $("#tituloJugador").html("<i class='nav-icon fas fa-list'></i> Nueva Multa");
            $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarMulta()'>Registrar</button>");
            //$("#motivoMulta").val("No asistio a la reunion");
           
            //$("#fecha").val("");
            $('#ModalRegistrarMultas').modal('show');
        }

        function obtenerprecio(){
            $.ajax({
                url: '../clases/Cl_Multa.php?op=precioMulta',
                type: 'POST',
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Error..!", "No se ha podido obtener el precio de la inscricion, favor ingresarlo manualmente", "error");
                    } else {                     
                        $("#total").val(data);
                    }
                }
            })
        }


        //funcion que pide confirmacion al usuario para desabilitar un producto
        function ConfirmarEliminar(id) {

            Swal.fire({
                title: 'Esta Seguro?',
                text: "Al Eliminar la multa ya no podra realizar el cobro!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    EliminarMulta(id);
                }
            })
        }

        function EliminarMulta(id) {

            $.ajax({
                url: '../clases/Cl_Multa.php?op=EliminarMulta',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(vs) {
                    if (vs == 2) {
                        Swal.fire("Error..!", "ha ocurrido un error al eliminar la multa", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito..!', 'Multa eliminada correctamente.', 'success');
                            location.reload();
                        }
                    }
                }
            })
        }

        function FiltrarMultas() {

            var idEquipo = $("#filEquipo").val();
            var idCampeonato = $("#filCampeonato").val();           

            $("#btn_add1").prop("disabled", true);
            $("#cargando_add1").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

            $.ajax({
                url: '../clases/Cl_Multa.php?op=FiltrarMultas',
                type: 'POST',
                data:{
                    idEquipo:idEquipo,
                    idCampeonato:idCampeonato
                },
                success: function(data) {
                    $("#btn_add1").prop("disabled", false); 
                    $("#cargando_add1").html('');
                   
                    $("#botonDirectivaActual").show();
                    $("#contenerdor_tabla").html('');
                    $('#example1').DataTable().destroy();
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


        function ListarMultas() {

            $("#botonDirectivaActual").hide();

            $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Multas ...</center></div>');

            $.ajax({
                url: '../clases/Cl_Multa.php?op=ListarMultas',
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
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function RegistrarMulta() {

            var motivoMulta = $('#motivoMulta').val();
            var fecha = $('#fecha').val();
            var total = $('#total').val();
            var idEquipo = $('#idEquipo').val();

            if (motivoMulta == "") {
                Swal.fire("Campos Vacios..!", "Debe seleccionar el motivo de la multa", "warning");
                return false;
            }
            if (idEquipo == "" || idEquipo == "0") {
                Swal.fire("Campos Vacios..!", "Debe seleccionar un equipo", "warning");
                return false;
            }
            if (total == "" || total == "0") {
                Swal.fire("Campos Vacios..!", "Debe ingresar el monto de la multa", "warning");
                return false;
            }
            if ( fecha == "") {
                Swal.fire("Campos Vacios..!", "Debe indicar la fecha", "warning");
                return false;
            }

            $("#botonRegistro").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
 

            $.ajax({
                url: '../clases/Cl_Multa.php?op=RegistrarMulta',
                type: 'POST',
                data: {
                    motivoMulta: motivoMulta,
                    fecha: fecha,
                    total: total,
                    idEquipo: idEquipo
                },
                success: function(vs) {
                    $("#cargando_add").html('');
                    $("#botonRegistro").prop("disabled", false); 
                    if (vs == 2) {
                        Swal.fire("Error..!", "Ha ocurrido un error al registrar la multa", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito!', 'Multa registrada correctamente', 'success');
                            location.reload();
                        }
                    }
                },
                error: function(vs){
                    $("#cargando_add").html('');
                    $("#botonRegistro").prop("disabled", false); 
                }
            })
        }
    </script>

<?php
      require "../template/piePagina.php";
      ?>


    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "language": lenguaje_español
            });

            FiltrarMultas();
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