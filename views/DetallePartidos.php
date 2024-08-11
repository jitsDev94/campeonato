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
                        <div class="col-sm-10">
                            <h1 class="m-0">Detalle de Partidos</h1>
                        </div>                     
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <section class="col-lg-12 col-md-12 pr-3 pl-3 ">
                <div class="card info-box shadow-lg">
                               
                          
                                <div class="row text-center">
                                    <div class="col-md-2">
                                        <label for="inputfecha" class="form-label"><h5><b>Fecha Partidos</b></h5></label>
                                        <div class="input-group">
                                        <input type="date" class="form-control" id="fecha">
                                        </div> <br>
                                    </div>
                                    <?php if($_SESSION["tipoCampeonato"] == "Fase de Grupo") {?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputCasa" class="form-label"><h5><b>Grupos</b></h5></label>
                                            <select class="form-control" id="grupo">
                                                <option value="0" selected>Seleccionar..</option>
                                                <option value="1">Grupo 1</option>
                                                <option value="2">Grupo 2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                        <div class="d-grid gap-2 d-md-flex" style="margin-top:39px;">
                                            <button type="button" class="btn btn-primary" onclick="listarPatidos()"><i class="fas fa-filter"></i> Buscar Partidos</button>
                                        </div>
                                    </div>
                                </div>

                </div>
                <br>
            </section>

            <div id="contenerdor_tabla" class="pl-3 pr-3">
                          
                              
                               
                          </div>
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

    <!-- Modal para ver partidos -->
    <div class="modal fade" id="ModalVerPartidos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloJugador"><i class="nav-icon fas fa-futbol"></i> Detalle del Patido</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <section class="col-md-12 card shadow-lg">
                            <br>
                            <div id="tituloEquipo1">
                                <h4 class="text-center">Equipo Local</h4>
                            </div><br>
                            <div id="contenerdor_tabla2" class="table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Jugador</th>
                                            <th>Hecho</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <br>
                        </section>

                        <section class="col-md-12 card shadow-lg">
                            <br>
                            <div id="tituloEquipo2">
                                <h4 class="text-center">Equipo Visitante</h4>
                            </div><br>
                            <div id="contenerdor_tabla3" class="table-responsive">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Jugador</th>
                                            <th>Hecho</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <br>
                        </section>
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
        function ModalVerPartidos(idPartido) {


            $.ajax({
                url: '../clases/Cl_DetallePartido.php?op=DatosEquipo',
                type: 'POST',
                data: {
                    id: idPartido
                },
                success: function(data) {

                    var resp = $.parseJSON(data);

                    var nombreEquipo1 = resp.EquipoLocal;
                    var nombreEquipo2 = resp.EquipoVisitante;
                    detalleEquipo1(idPartido, nombreEquipo1);
                    detalleEquipo2(idPartido, nombreEquipo2);
                    $('#ModalVerPartidos').modal('show');
                }
            })



        }

        function detalleEquipo1(id, nombreEquipo) {

            $.ajax({
                url: '../clases/Cl_DetallePartido.php?op=detalleEquipo1',
                type: 'POST',
                data: {
                    id: id,
                    nombreEquipo: nombreEquipo
                },
                success: function(data) {
                    $("#tituloEquipo1").html('<h4 class="text-center">' + nombreEquipo + '</h4>')
                    $("#contenerdor_tabla2").html('');
                    $('#example2').DataTable().destroy();
                    $("#contenerdor_tabla2").html(data);
                }
            })

        }

        function detalleEquipo2(id, nombreEquipo) {

            $.ajax({
                url: '../clases/Cl_DetallePartido.php?op=detalleEquipo2',
                type: 'POST',
                data: {
                    id: id,
                    nombreEquipo: nombreEquipo
                },
                success: function(data) {
                    $("#tituloEquipo2").html('<h4 class="text-center">' + nombreEquipo + '</h4>')
                    $("#contenerdor_tabla3").html('');
                    $('#example3').DataTable().destroy();
                    $("#contenerdor_tabla3").html(data);
                }
            })

        }



        function modalEditarEquipo(id) {

            $.ajax({
                url: '../clases/Cl_Equipo.php?op=DatosEquipo',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del Equipo", "error");
                    } else {
                        var resp = $.parseJSON(data);
                        $("#tituloJugador").html("<i class='nav-icon fas fa-edit'></i> Editar Equipo");
                        $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarEquipo()'>Editar</button>");
                        $("#id").val(resp.id);
                        $("#nombre").val(resp.nombreEquipo);
                        $("#fecha").val(resp.fechaRegistro);
                        $('#ModalRegistrarEquipo').modal('show');
                    }
                }
            })
        }

        function EditarEquipo() {

            var id = $('#id').val();
            var nombre = $('#nombre').val();
            var fecha = $('#fecha').val();

            if (nombre == "" || fecha == "") {
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
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito!', 'Equipo editado correctamente', 'success');
                            location.reload();
                        }
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
                    EstadoEquipo(id, 'Deshabilitado');
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
                    EstadoEquipo(id, 'Habilitado');
                }
            })
        }

        function EstadoEquipo(id, estado) {

            $.ajax({
                url: '../clases/Cl_Equipo.php?op=EstadoEquipo',
                type: 'POST',
                data: {
                    id: id,
                    estado: estado
                },
                success: function(vs) {
                    if (vs == 2) {
                        Swal.fire("Error..!", "ha ocurrido un error al deshabilitar al equipo", "error");
                    } else {
                        if (vs == 1) {
                            if (estado == 'Habilitado') {
                                Swal.fire('Exito..!', 'Equipo habilitado correctamente.', 'success');
                                location.reload();
                            } else {
                                Swal.fire('Exito..!', 'Equipo deshabilitado correctamente.', 'success');
                                location.reload();
                            }
                        }
                    }
                }
            })
        }

        function listarPatidos() {

            var fecha = $("#fecha").val();
            var grupo = $("#grupo").val();

            // if (fecha == "") {
            //     swal.fire("Oopss..!", "Debe ingresar un fecha", "warning");
            //     return false;
            // }

            $("#contenerdor_tabla").html('');
            $.ajax({
                url: '../clases/Cl_DetallePartido.php?op=ListaPartidos2',
                type: 'POST',
                data: {
                    fecha: fecha,
                    grupo: grupo
                },
                success: function(data) {
                    $("#contenerdor_tabla").html('');
                    //$('#example1').DataTable().destroy();
                    $("#contenerdor_tabla").html(data);
                }
            })
        }

        function RegistrarEquipo() {

            var nombre = $('#nombre').val();
            var fecha = $('#fecha').val();

            if (nombre == "" || fecha == "") {
                Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
                return false;
            }

            $.ajax({
                url: '../clases/Cl_Equipo.php?op=RegistrarEquipo',
                type: 'POST',
                data: {
                    nombre: nombre,
                    fecha: fecha
                },
                success: function(vs) {
                    if (vs == 2) {
                        Swal.fire("Error..!", "Ha ocurrido un error al registrar al equipo", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito!', 'Equipo registrado correctamente', 'success');
                            location.reload();
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
        $(function() {

            listarPatidos();

            $("#example7").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "language": lenguaje_español
            });
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