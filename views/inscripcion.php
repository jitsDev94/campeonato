<?php

include 'clases/conexion.php';
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
    <title>Inscripción</title>
    <link rel="icon" type="image/jpg" href="img/image.png">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

    <style>
        .card {
            border-top-color: cornflowerblue;
            border-top-width: 3px;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="CargarDatos();">
    <div class="wrapper">

        <?php
        require "Navegador.php";
        ?>

        <?php
        require "Menus.php";
        ?>

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Inscripción de Equipos</h1>
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
                                                    <option value="todos" selected >Seleccionar Equipo...</option>
                                                    <?php
                                                    $consultar = "SELECT * FROM Equipo  order by nombreEquipo asc";
                                                    $resultado1 = mysqli_query($conectar, $consultar);
                                                    while ($listado = mysqli_fetch_array($resultado1)) {
                                                    ?>
                                                        <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombreEquipo']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputCasa" class="form-label"><b>Nombre Torneo</b></label>
                                                        <select class="form-control" id="filCampeonato">
                                                            <option value="todos" selected >Seleccionar Torneo...</option>
                                                            <?php
                                                            $consultar = "SELECT * FROM Campeonato where estado = 'Concluido'";
                                                            $resultado1 = mysqli_query($conectar, $consultar);
                                                            while ($listado = mysqli_fetch_array($resultado1)) {
                                                            ?>
                                                                <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombre']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-md-3">
                                                    <div style="margin-top:32px;">
                                                        <button type="button" class="btn btn-primary" id="btn_add1" onclick="FiltrarInscripciones()"><i class="fas fa-filter"></i> Filtrar</button>
                                                        <button type="button" class="btn btn-secondary" id="botonDirectivaActual" onclick="ListaEquiposInscritos()"> Mostrar Actual</button>
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


            <section class="col-lg-12 col-md-12"> <br>
                <div class="card info-box shadow-lg">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-9">
                                <label for="">
                                    <h4 id="lblTitulo">Equipos Inscritos</h4>
                                </label>
                            </div>
                            <div class="col-12 col-md-3">
                                <button type="button" class="btn btn-primary btn-block" onclick="ModalRegistrarInscripcion()"><i class="fas fa-plus-circle"></i> Nueva Inscripción</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="contenerdor_tabla" class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Equipo</th>
                                        <th>Inscripción</th>
                                        <th>Fecha Pago</th>
                                        <th>Torneo</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>

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

        <?php $año = date('Y'); ?>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0
            </div>
            <strong>Copyright &copy; Software Bolivia <?php echo $año ?></strong> Todos los derechos reservados.
        </footer>

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
                            <input type="text" class="form-control shadow-lg" id="torneo" disabled>
                        </div>
                        <div class="col-md-6">
                            <br>                           
                            <label for="inputName" class="form-label"><b>Inscripción(*)</b></label>
                            <input type="text" class="form-control shadow-lg" id="monto" placeholder="Ingresar Monto">
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label for="inputPassword" class="form-label"><b>Nombre Equipo(*)</b></label>
                            <select class="form-control shadow-lg" id="idEquipo" multiple>
                                <?php
                                $count = 0;
                                $consultar = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";
                                $resultado1 = mysqli_query($conectar, $consultar);
                                while ($listado = mysqli_fetch_array($resultado1)) {
                                    $count++;
                                ?>
                                    <option value="<?php echo $listado['id']; ?>"><?php echo $count; ?> - <?php echo $listado['nombreEquipo']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                      
                        <div class="col-md-6">
                            <br>
                            <label for="inputName" class="form-label"><b>Fecha Pago(*)</b></label>
                            <input type="date" class="form-control shadow-lg" id="fecha">
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="form-control shadow-lg" id="idInscripcion">
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="form-control shadow-lg" id="idCampeonato">
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
         function CargarCampeonato(){
        $.ajax({
            url: 'clases/Cl_Inscripcion.php?op=UltimoTorneo',
            type: 'POST',
            success: function(data) {
              if(data == "error"){
                Swal.fire("Error..!", "Ha ocurrido un error al obtener el nombre del torneo actual", "error");      
              }
              else{
                var resp= $.parseJSON(data);
                $("#idCampeonato").val(resp.id); 
                $("#torneo").val(resp.nombre); 
              }              
            }            
          })   
    }

        function CargarDatos() {

            ListaEquiposInscritos();
            
        }


        function ModalRegistrarInscripcion(id) {
            $("#idEquipo").prop("multiple",true);
            let hoy = new Date();
            document.getElementById("fecha").value = hoy.toJSON().slice(0,10);
            CargarCampeonato();
            obtenerprecioInscripcion();
            $("#tituloJugador").html("<i class='fas fa-briefcase'></i> Nueva Inscripción");
            $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarInscripcion()'>Registrar</button>");
            $("#idInscripcion").val("");
            $("#idCampeonato").val("");
            
            $('#ModalRegistrarInscripcion').modal('show');
        }

        function obtenerprecioInscripcion(){
            $.ajax({
                url: 'clases/Cl_Inscripcion.php?op=precioInscripcion',
                type: 'POST',
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Error..!", "No se ha podido obtener el precio de la inscricion, favor ingresarlo manualmente", "error");
                    } else {                     
                        $("#monto").val(data);
                    }
                }
            })
        }

        function ModalEditarInscripcion(id) {

            $("#idEquipo").prop("multiple",false);
            $.ajax({
                url: 'clases/Cl_Inscripcion.php?op=DatoEquipoInscrito',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    if (data == "") {
                        Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del pago de la inscripción", "error");
                    } else {
                        var resp = $.parseJSON(data);
                        $("#tituloJugador").html("<i class='nav-icon fas fa-edit'></i> Editar Inscripción");
                        $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarMiembroDirectiva()'>Editar</button>");
                        CargarCampeonato();
                        $("#idInscripcion").val(resp.id);
                        $("#idEquipo").val(resp.idEquipo);
                        $("#monto").val(resp.inscripcion);
                        $("#fecha").val(resp.fecha);
                        $('#ModalRegistrarInscripcion').modal('show');
                    }
                }
            })
        }

        function EditarMiembroDirectiva() {

            var id = $('#idInscripcion').val();
            var idEquipo = $('#idEquipo').val();
            var monto = $('#monto').val();
            var fecha = $('#fecha').val();


            if (monto == "") {
                    Swal.fire("Campos Vacios..!", "Debe ingresar el monto", "warning");
                    return false;
                } else {
                    if (fecha == "") {
                        Swal.fire("Campos Vacios..!", "Debe ingresar la fecha del pago", "warning");
                        return false;
                    } else {
                        if (idCampeonato == "") {
                            Swal.fire("Campos Vacios..!", "Debe ingresar el nombre del campeonato", "warning");
                            return false;
                        }
                    }
                }

            $("#botonRegistro").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
           

            $.ajax({
                url: 'clases/Cl_Inscripcion.php?op=EditarEquipoInscrito',
                type: 'POST',
                data: {
                    idInscripcion: id,
                    idEquipo: idEquipo,
                    monto: monto,
                    fecha: fecha,
                },
                success: function(vs) {
                    $("#cargando_add").html('');
                    $("#botonRegistro").prop("disabled", false); 
                    if (vs == 2) {
                        Swal.fire("Error..!", "Ha ocurrido un error al editar la inscripción", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito!', 'Inscripción editada correctamente', 'success');
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

        function ListaEquiposInscritos() {

        
            $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Equipos ...</center></div>');

            $.ajax({
                url: 'clases/Cl_Inscripcion.php?op=ListaEquiposInscritos',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla").html(data);
                    $("#botonDirectivaActual").hide();
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function FiltrarInscripciones() {

            var idEquipo = $("#filIdEquipo").val();
            var idCampeonato = $("#filCampeonato").val();

            if (idEquipo == "Todos" && idCampeonato == "Todos") {
                Swal.fire('Aviso..!', 'Debe seleccionar una opcion de filtro', 'warning');
                return false;
            }

            $("#btn_add1").prop("disabled", true);
            $("#cargando_add1").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
           
            $.ajax({
                url: 'clases/Cl_Inscripcion.php?op=FiltrarInscripciones',
                type: 'POST',
                data: {
                    idEquipo: idEquipo,
                    idCampeonato: idCampeonato
                },
                success: function(data) {
                    $("#cargando_add1").html('');
                    $("#btn_add1").prop("disabled", false); 

                    $("#contenerdor_tabla").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla").html(data);
                    $("#botonDirectivaActual").show();
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                },
                error: function(data){
                    $("#cargando_add1").html('');
                    $("#btn_add1").prop("disabled", false); 

                }
            })
        }


        function RegistrarInscripcion() {

            var idEquipo = $('#idEquipo').val();
            var monto = $('#monto').val();
            var fecha = $('#fecha').val();
            var idCampeonato = $('#idCampeonato').val();

           
           
                if (monto == "") {
                    Swal.fire("Campos Vacios..!", "Debe ingresar el monto", "warning");
                    return false;
                } else {
                    if (fecha == "") {
                        Swal.fire("Campos Vacios..!", "Debe ingresar la fecha del pago", "warning");
                        return false;
                    } else {
                        if (idCampeonato == "") {
                            Swal.fire("Campos Vacios..!", "Debe ingresar el nombre del campeonato", "warning");
                            return false;
                        }
                    }
                }
        
            $("#botonRegistro").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

            for (let index = 0; index < idEquipo.length; index++) {
              
                $.ajax({
                    url: 'clases/Cl_Inscripcion.php?op=RegistrarInscripcion',
                    type: 'POST',
                    data: {
                        idEquipo: idEquipo[index],
                        monto: monto,
                        fecha: fecha,
                        idCampeonato: idCampeonato
                    },
                    success: function(vs) {
                        $("#cargando_add").html('');
                        $("#botonRegistro").prop("disabled", false); 
                        if (vs == 2) {
                            Swal.fire("Error..!", "Ha ocurrido un error al registrar la inscripción", "error");
                            return false;
                        } else {
                            if (vs == 1) {
                               
                            }
                            else {
                                if (vs == 3) {
                                    Swal.fire('Advertencia!', 'El equipo ya esta registrado en este torneo', 'warning');
                                    return false;
                                }
                            }
                        }
                    },
                    error: function(vs){
                        $("#cargando_add").html('');
                        $("#botonRegistro").prop("disabled", false); 
                    }
                }) 
            }

            Swal.fire('Exito!', 'Equipo inscrito correctamente', 'success');
            location.reload();
            
        }
    </script>



    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
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