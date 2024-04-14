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
    <title>Estadisticas</title>
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
                        <div class="col-sm-12">
                            <h1 class="m-0">Estadistica Deportivas</h1>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>


            <!-- goleadores -->
            <section class="col-lg-12 col-md-12">
                <div id="accordion">
                    <div class="card info-box shadow-lg">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                   <h5> Goleadores</h5>
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
                                                <input type="text" class="form-control" id="FilNombreJugador" placeholder="Ingresar nombre o Apellido">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label">Nombre Equipo</label>
                                                    <select class="form-control" id="filIdEquipo">
                                                        <option value="" selected>Seleccionar Equipo...</option>
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
                                                        <label for="inputCasa" class="form-label">Nombre Torneo</label>
                                                        <select class="form-control" id="filIdCampeonato">
                                                            <option value="" selected>Seleccionar Torneo...</option>
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
                                                        <button type="button" class="btn btn-primary" onclick="FiltrarGoleadores()"><i class="fas fa-filter"></i> Filtrar</button>
                                                        <button type="button" class="btn btn-secondary" id="botonDirectivaActual" onclick="ListaGoleadores()"> Mostrar Actual</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 col-12">
                                    <label for="inputPassword" class="form-label"><h5><b>Listado de Goleadores</b></h5></label>                                     
                                </div>
                                <div id="contenerdor_tabla" class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jugador</th>
                                                <th>Equipo Actual</th>
                                                <th>Total Goles</th>
                                                <th>Campeonato</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- tarjetas Amarillas -->
            <section class="col-lg-12 col-md-12"><br>
                <div id="accordion">
                    <div class="card info-box shadow-lg">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true" aria-controls="collapsetwo">
                                   <h5>Tarjeta Amarillas</h5>
                                </button>
                            </h5>
                        </div>
                        <div id="collapsetwo" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form role="form" method="post" id="formFiltroVenta">
                                    <div class="box-body">
                                        <div class="form-horizontal">
                                           
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label">Nombre Jugador</label>
                                                <input type="text" class="form-control" id="FilNombreJugador2" placeholder="Ingresar nombre o Apellido">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label"><b>Nombre Equipo</b></label>
                                                    <select class="form-control" id="filIdEquipo2">
                                                        <option value="" selected>Seleccionar Equipo...</option>
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
                                                        <select class="form-control" id="filIdCampeonato2">
                                                            <option value="" selected>Seleccionar Torneo...</option>
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
                                                        <button type="button" class="btn btn-primary" onclick="FiltrarTarjetasAmarillas()"><i class="fas fa-filter"></i> Filtrar</button>
                                                        <button type="button" class="btn btn-secondary" id="botonDirectivaActual2" onclick="ListaTarjetasAmarillas()"> Mostrar Actual</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 col-12">
                                    <label for="inputPassword" class="form-label"><h5><b>Listado de Tarjetas</b></h5></label>                                     
                                </div>
                                <div id="contenerdor_tabla2" class="table-responsive">
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jugador</th>
                                                <th>Equipo</th>
                                                <th>Total Amarillas</th>
                                                <th>Campeonato</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


                  <!-- tarjetas Rojas -->
            <section class="col-lg-12 col-md-12"><br>
                <div id="accordion">
                    <div class="card info-box shadow-lg">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapsethree">
                                   <h5>Tarjetas Rojas</h5>
                                </button>
                            </h5>
                        </div>
                        <div id="collapsethree" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form role="form" method="post" id="formFiltroVenta">
                                    <div class="box-body">
                                        <div class="form-horizontal">
                                           
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label">Nombre Jugador</label>
                                                <input type="text" class="form-control" id="FilNombreJugador3" placeholder="Ingresar nombre o Apellido">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label"><b>Nombre Equipo</b></label>
                                                    <select class="form-control" id="filIdEquipo3">
                                                        <option value="" selected>Seleccionar Equipo...</option>
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
                                                        <select class="form-control" id="filIdCampeonato3">
                                                            <option value="" selected>Seleccionar Torneo...</option>
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
                                                        <button type="button" class="btn btn-primary" onclick="FiltrarTarjetasRojas()"><i class="fas fa-filter"></i> Filtrar</button>
                                                        <button type="button" class="btn btn-secondary" id="botonDirectivaActual3" onclick="ListaTarjetasRojas()"> Mostrar Actual</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 col-12">
                                    <label for="inputPassword" class="form-label"><h5><b>Listado de Tarjetas</b></h5></label>                                     
                                </div>
                                <div id="contenerdor_tabla3" class="table-responsive">
                                    <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jugador</th>
                                                <th>Equipo</th>
                                                <th>Total Rojas</th>
                                                <th>Campeonato</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

              <!-- Equipos Campeones -->
              <section class="col-lg-12 col-md-12"><br>
                <div id="accordion">
                    <div class="card info-box shadow-lg">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive">
                                   <h5>Equipos Campeones</h5>
                                </button>
                            </h5>
                        </div>
                        <div id="collapsefive" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form role="form" method="post" id="formFiltroVenta">
                                    <div class="box-body">
                                        <div class="form-horizontal">
                                           
                                            <div class="row">                                             
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label"><b>Nombre Equipo</b></label>
                                                    <select class="form-control" id="filIdEquipo5">
                                                        <option value="" selected>Seleccionar Equipo...</option>
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
                                                        <select class="form-control" id="filIdCampeonato5">
                                                            <option value="" selected>Seleccionar Torneo...</option>
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
                                                        <button type="button" class="btn btn-primary" onclick="FiltrarCampeones()"><i class="fas fa-filter"></i> Filtrar</button>
                                                        <button type="button" class="btn btn-secondary" id="botonDirectivaActual5" onclick="ListaEquiposCampeones()"> Mostrar Actual</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 col-12">
                                    <label for="inputPassword" class="form-label"><h5><b>Listado de Equipos Campeones</b></h5></label>                                     
                                </div>
                                <div id="contenerdor_tabla5" class="table-responsive">
                                    <table id="example5" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Equipo</th>                                               
                                                <th>Campeonato</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- suspenciones -->
            <section class="col-lg-12 col-md-12"><br>
                <div id="accordion">
                    <div class="card info-box shadow-lg">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapsefour" aria-expanded="true" aria-controls="collapsefour">
                                   <h5> Suspenciones</h5>
                                </button>
                            </h5>
                        </div>
                        <div id="collapsefour" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form role="form" method="post" id="formFiltroVenta">
                                    <div class="box-body">
                                        <div class="form-horizontal">
                                           
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label">Nombre Jugador</label>
                                                <input type="text" class="form-control" id="FilNombreJugador4" placeholder="Ingresar nombre o Apellido">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputPassword" class="form-label"><b>Nombre Equipo</b></label>
                                                    <select class="form-control" id="filIdEquipo4">
                                                        <option value="" selected>Seleccionar Equipo...</option>
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
                                                        <select class="form-control" id="filIdCampeonato4">
                                                            <option value="" selected>Seleccionar Torneo...</option>
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
                                                        <button type="button" class="btn btn-primary" onclick="FiltrarSuspenciones()"><i class="fas fa-filter"></i> Filtrar</button>
                                                        <button type="button" class="btn btn-secondary" id="botonDirectivaActual4" onclick="ListaSuspenciones()"> Mostrar Actual</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 col-12">
                                    <label for="inputPassword" class="form-label"><h5><b>Listado de Suspenciones</b></h5></label>                                     
                                </div>
                                <div id="contenerdor_tabla4" class="table-responsive">
                                    <table id="example4" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jugador</th>
                                                <th>Equipo</th>
                                                <th>Total Suspeciones</th>
                                                <th>Campeonato</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                $consultar = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";
                                $resultado1 = mysqli_query($conectar, $consultar);
                                while ($listado = mysqli_fetch_array($resultado1)) {
                                ?>
                                    <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombreEquipo']; ?></option>
                                <?php } ?>
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
                            <input type="hidden" class="form-control" id="idInscripcion">
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="form-control" id="idCampeonato">
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
       
            $("#idJugador").val(id);
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
            if(idJugador == ""){
                swal.fire('Campos Vacios..!','Debe seleccionar al jugador que sera transferido','warning');
                return false;
            }

            $.ajax({
                url: 'clases/Cl_Transferencia.php?op=RegistrarTransferencia',
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
                    if (vs == 2) {                     
                        Swal.fire("Error..!", "Error al actualizar el nuevo equipo del jugador", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito..!', 'Jugador transferido correctamente.', 'success');
                            location.reload();
                        }
                        else {
                            if (vs == 3) {
                                Swal.fire('Error..!', 'Error al registrar la transferencia correctamente.', 'error');
                            }
                        }
                    }
                }
            })
        }

        function BuscarJugador(id) {
            //var id= ("#idEquipoOrigen").val();
            var html = '<option value ="0" selected disabled>seleccionar...</option>';
            $("#NombresJugadores").html(html);
            $.ajax({
                url: 'clases/Cl_Transferencia.php?op=BuscarJugador',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Error..!", "Ha ocurrido un error al obtener la lista de jugadores", "error");
                    } else {
                        $("#NombresJugadores").append(data);

                    }
                }
            })
        }


        function CargarCampeonato() {
            $.ajax({
                url: 'clases/Cl_Transferencia.php?op=UltimoTorneo',
                type: 'POST',
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Error..!", "Ha ocurrido un error al obtener el nombre del torneo actual", "error");
                    } else {
                        var resp = $.parseJSON(data);
                        $("#idCampeonato").val(resp.id);
                        $("#torneo").val(resp.nombre);
                    }
                }
            })
        }

        function CargarDatos() {
            ListaGoleadores();
            ListaTarjetasAmarillas();
            ListaTarjetasRojas();
            ListaEquiposCampeones();
            /*CargarCampeonato();
            let hoy = new Date();
            document.getElementById("fecha").value = hoy.toJSON().slice(0, 10);
            ListaTransferencias();*/
        }


        function ModalRegistrarInscripcion(id) {
            let hoy = new Date();
            document.getElementById("fecha").value = hoy.toJSON().slice(0, 10);
            CargarCampeonato();
            $("#tituloJugador").html("<i class='fas fa-briefcase'></i> Nueva Inscripción");
            $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarInscripcion()'>Registrar</button>");
            $("#idInscripcion").val("");
            $("#idCampeonato").val("");
            $("#monto").val("");
            $('#ModalRegistrarInscripcion').modal('show');
        }

        function ModalEditarInscripcion(id) {

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
                    if (vs == 2) {
                        Swal.fire("Error..!", "Ha ocurrido un error al editar la inscripción", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito!', 'Inscripción editada correctamente', 'success');
                            location.reload();
                        }
                    }
                }
            })
        }

        function ListaGoleadores() {

            $.ajax({
                url: 'clases/Cl_Estadistica.php?op=ListaGoleadores',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla").html(data);
                    $("#botonDirectivaActual").hide();
                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function ListaTarjetasAmarillas() {

            $.ajax({
                url: 'clases/Cl_Estadistica.php?op=ListaTarjetasAmarillas',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla2").html('');
                    $('#example2').DataTable().destroy();
                    $("#contenerdor_tabla2").html(data);
                    $("#botonDirectivaActual2").hide();
                    $("#example2").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function ListaTarjetasRojas() {

            $.ajax({
                url: 'clases/Cl_Estadistica.php?op=ListaTarjetasRoja',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla3").html('');
                    $('#example3').DataTable().destroy();
                    $("#contenerdor_tabla3").html(data);
                    $("#botonDirectivaActual3").hide();
                    $("#example3").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function ListaEquiposCampeones() {

            $.ajax({
                url: 'clases/Cl_Estadistica.php?op=FiltrarCampeones',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla5").html('');
                    $('#example5').DataTable().destroy();
                    $("#contenerdor_tabla5").html(data);
                    $("#botonDirectivaActual5").hide();
                    $("#example5").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }


        function FiltrarGoleadores() {
           
            var idCampeonato = $("#filIdCampeonato").val();
            var nombreJugador = $("#FilNombreJugador").val();
            var idEquipo = $("#filIdEquipo").val();
            var combo = document.getElementById("filIdEquipo");
            var nombreEquipo = combo.options[combo.selectedIndex].text;

            if (idEquipo == "" && idCampeonato == "" && nombreJugador == "") {
                Swal.fire('Aviso..!', 'Debe seleccionar una opcion de filtro', 'warning');
                return false;
            }

            $.ajax({
                url: 'clases/Cl_Estadistica.php?op=FiltrarGoleadores',
                type: 'POST',
                data: {
                    nombreEquipo: nombreEquipo,
                    idCampeonato: idCampeonato,
                    nombreJugador: nombreJugador,
                    idEquipo: idEquipo
                },
                success: function(data) {
                
                    $("#contenerdor_tabla").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla").html(data);
                    $("#botonDirectivaActual").show();
                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function FiltrarTarjetasAmarillas() {
           
           var idCampeonato = $("#filIdCampeonato2").val();
           var nombreJugador = $("#FilNombreJugador2").val();
           var idEquipo = $("#filIdEquipo2").val();
           var combo = document.getElementById("filIdEquipo2");
           var nombreEquipo = combo.options[combo.selectedIndex].text;

           if (idEquipo == "" && idCampeonato == "" && nombreJugador == "") {
               Swal.fire('Aviso..!', 'Debe seleccionar una opcion de filtro', 'warning');
               return false;
           }

           $.ajax({
               url: 'clases/Cl_Estadistica.php?op=FiltrarTarjetasAmarillas',
               type: 'POST',
               data: {
                   nombreEquipo: nombreEquipo,
                   idCampeonato: idCampeonato,
                   nombreJugador: nombreJugador,
                   idEquipo: idEquipo
               },
               success: function(data) {
               
                   $("#contenerdor_tabla2").html('');
                   $('#example2').DataTable().destroy();
                   $("#contenerdor_tabla2").html(data);
                   $("#botonDirectivaActual2").show();
                   $("#example2").DataTable({
                       "responsive": true,
                       "lengthChange": false,
                       "autoWidth": false,
                       "language": lenguaje_español
                   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
               }
           })
       }

       function FiltrarTarjetasRojas() {
           
           var idCampeonato = $("#filIdCampeonato3").val();
           var nombreJugador = $("#FilNombreJugador3").val();
           var idEquipo = $("#filIdEquipo3").val();
           var combo = document.getElementById("filIdEquipo3");
           var nombreEquipo = combo.options[combo.selectedIndex].text;

           if (idEquipo == "" && idCampeonato == "" && nombreJugador == "") {
               Swal.fire('Aviso..!', 'Debe seleccionar una opcion de filtro', 'warning');
               return false;
           }

           $.ajax({
               url: 'clases/Cl_Estadistica.php?op=FiltrarTarjetasRojas',
               type: 'POST',
               data: {
                   nombreEquipo: nombreEquipo,
                   idCampeonato: idCampeonato,
                   nombreJugador: nombreJugador,
                   idEquipo: idEquipo
               },
               success: function(data) {
               
                   $("#contenerdor_tabla3").html('');
                   $('#example3').DataTable().destroy();
                   $("#contenerdor_tabla3").html(data);
                   $("#botonDirectivaActual3").show();
                   $("#example3").DataTable({
                       "responsive": true,
                       "lengthChange": false,
                       "autoWidth": false,
                       "language": lenguaje_español
                   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
               }
           })
       }


       function FiltrarCampeones() {
           
           var idCampeonato = $("#filIdCampeonato5").val();
       
           var idEquipo = $("#filIdEquipo5").val();         

           if (idEquipo == "" && idCampeonato == "") {
               Swal.fire('Aviso..!', 'Debe seleccionar una opcion de filtro', 'warning');
               return false;
           }

           $.ajax({
               url: 'clases/Cl_Estadistica.php?op=FiltrarCampeones',
               type: 'POST',
               data: {                 
                   idCampeonato: idCampeonato,                
                   idEquipo: idEquipo
               },
               success: function(data) {
               
                   $("#contenerdor_tabla5").html('');
                   $('#example5').DataTable().destroy();
                   $("#contenerdor_tabla5").html(data);
                   $("#botonDirectivaActual5").show();
                   $("#example5").DataTable({
                       "responsive": true,
                       "lengthChange": false,
                       "autoWidth": false,
                       "language": lenguaje_español
                   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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


            $.ajax({
                url: 'clases/Cl_Inscripcion.php?op=RegistrarInscripcion',
                type: 'POST',
                data: {
                    idEquipo: idEquipo,
                    monto: monto,
                    fecha: fecha,
                    idCampeonato: idCampeonato
                },
                success: function(vs) {
                    if (vs == 2) {
                        Swal.fire("Error..!", "Ha ocurrido un error al registrar la inscripción", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito!', 'Equipo inscrito correctamente', 'success');
                            location.reload();
                        }
                    }
                }
            })
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