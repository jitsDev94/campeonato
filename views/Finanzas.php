<?php

include 'clases/conexion.php';
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
    <title>Finanzas</title>
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
            border-top-color: red;
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
                            <h1 class="m-0">Datos Financieros</h1>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <div class="row m-2">

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
                <form role="form" method="post" id="formFiltroVenta">
                  <div class="box-body">
                    <div class="form-horizontal">
                      <div class="row">                       
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="inputCasa" class="form-label"><b>Nombre Torneo</b></label>
                            <select class="form-control" id="filCampeonato">
                              <option value="0" selected disabled>Seleccionar Torneo...</option>
                              <?php
                              $consultar = "SELECT * FROM Campeonato ";
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
                            <button type="button" class="btn btn-primary" id="btn_add1" onclick="CargarDatos()"><i class="fas fa-filter"></i> Filtrar</button>                          
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


                <section class="col-md-12">

                    <h4><b> Tipos de Ingresos: </b></h4>

                </section>

                <section class="col-md-4">
                    <div class="card text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Tarjetas Amarillas</b></h4>
                        </div>
                        <div class="card-body text-center">

                            <h4 class="card-text" id="tarjetasAmarillas">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleTarjetasAmarillas();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Tarjetas Rojas</b></h4>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text" id="tarjetasRojas">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleTarjetasRojas();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Arbitraje</b></h4>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text" id="arbitraje">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleArbitraje();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Inscripción</b></h4>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text" id="inscripcion">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleInscripcion();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Multas</b></h4>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text" id="multas">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleMulta();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Observaciones Rechazadas</b></h4>
                        </div>
                        <div class="card-body text-center ">
                            <h4 class="card-text" id="observaciones">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleObservaciones();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Transferencia</b></h4>
                        </div>
                        <div class="card-body text-center">

                            <h4 class="card-text" id="Transferencia">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleTransferencia();">Mas detalles</a>
                        </div>
                    </div>
                </section>
            </div>

            <div class="row m-2">
                <section class="col-md-12">

                    <h4><b> Tipos de Gastos: </b></h4>

                </section>

                <section class="col-md-4">
                    <div class="card card1 text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Pago Arbitro</b></h4>
                        </div>
                        <div class="card-body text-center">

                            <h4 class="card-text" id="PagoArbitro">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetallePagoArbitro();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card card1 text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Pago Cancha</b></h4>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text" id="PagoCancha">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleCancha();">Mas detalles</a>
                        </div>
                    </div>
                </section>

                <section class="col-md-4">
                    <div class="card card1 text-center info-box shadow-lg">

                        <div class="card-header">
                            <h4> <b> Gastos Internos</b></h4>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text" id="PagoInterno">Bs. 0</h4>
                            <a href="#" class="btn btn-secondary" id="btn1" onclick="ModalDetalleTGastosInternos();">Mas detalles</a>
                        </div>
                    </div>
                </section>


            </div>

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

    <!-- Modal ver detalles de las tarjetas amarrillas -->
    <div class="modal fade" id="ModalDetalle">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo"><i class="fas fa-credit-card-blank"></i></h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="contenerdor_tabla1" class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jugador</th>
                                    <th>Equipo</th>
                                    <th>Precio</th>
                                    <th>Fecha Partido</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal ver detalles de las tarjetas rojas -->
    <div class="modal fade" id="ModalDetalleTarjetasRojas">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-credit-card-blank"></i> Tarjeta Rojas</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="contenerdor_tabla1" class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jugador</th>
                                    <th>Equipo</th>
                                    <th>Precio</th>
                                    <th>Fecha Partido</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  

    <script>
        function CargarDatos() {

            //ingresos
            TarjetasAmarillas();
            TarjetasRojas();
            inscripcion();
            arbitraje();
            multas();
            observaciones();
            Transferencia();

            //gastos
            PagoCancha();
            PagoArbitro();
            PagoInterno();
        }

        //gastos
        function PagoCancha() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=PagoCancha',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total pagado de las canchas", "warning");
                    } else {
                        if(data == ""){
                            $("#PagoCancha").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#PagoCancha").html("Bs. " + data);
                        }
                    }
                }
            })
        }

        function PagoArbitro() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=PagoArbitro',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total pagado a los arbitros", "warning");
                    } else {
                        if(data == ""){
                            $("#PagoArbitro").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#PagoArbitro").html("Bs. " + data);
                        }
                    }
                }
            })
        }

        function PagoInterno() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=PagoInterno',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de gastos internos", "warning");
                    } else {
                        if(data == ""){
                            $("#PagoInterno").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#PagoInterno").html("Bs. " + data);
                        }
                    }
                }
            })
        }

        //ingresos
        function TarjetasAmarillas() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=TarjetasAmarillas',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {
                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de ingresos de tarjetas amarillas", "warning");
                    } else {
                        if(data == ""){
                            $("#tarjetasAmarillas").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#tarjetasAmarillas").html("Bs. " + data +".00");
                        }
                       
                    }
                }
            })
        }

        function TarjetasRojas() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=TarjetasRojas',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de ingresos de tarjetas rojas", "warning");
                    } else {
                        if(data == ""){
                            $("#tarjetasRojas").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#tarjetasRojas").html("Bs. " + data +".00");
                        }
                    }
                }
            })
        }

        function inscripcion() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=inscripcion',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de ingresos de inscripciones", "warning");
                    } else {
                        if(data == ""){
                            $("#inscripcion").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#inscripcion").html("Bs. " + data +".00");
                        }
                    }
                }
            })
        }

        function arbitraje() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=arbitraje',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de ingresos de arbitraje", "warning");
                    } else {
                        if(data == ""){
                            $("#arbitraje").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#arbitraje").html("Bs. " + data +".00");
                        }

                    }
                }
            })
        }

        function multas() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=multas',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de ingresos de multas", "warning");
                    } else {
                        if(data == ""){
                            $("#multas").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#multas").html("Bs. " + data +".00");
                        }
                    }
                }
            })
        }

        function observaciones() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=observaciones',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de ingresos de observaciones rechazadas", "warning");
                    } else {
                        if(data == ""){
                            $("#observaciones").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#observaciones").html("Bs. " + data +".00");
                        }
                    }
                }
            })
        }

        function Transferencia() {

            var codCampeonato = $("#filCampeonato").val();

            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=Transferencia',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    if (data == "error") {
                        Swal.fire("Oops..!", "Error al cargar el total de ingresos en transferencias de jugador", "warning");
                    } else {
                        if(data == ""){
                            $("#Transferencia").html("Bs. 0.00");
                        }
                        else{
                            data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                            data = data.split('').reverse().join('').replace(/^[\,]/,'');
                            $("#Transferencia").html("Bs. " + data +".00");
                        }
                    }
                }
            })
        }


        function ModalDetalleCancha() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetallePagoCancha',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Pago Cancha");
                    $("#ModalDetalle").modal("show");
                }
            })
        }

        function ModalDetallePagoArbitro() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetallePagoArbitraje',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Pago arbitros");
                    $("#ModalDetalle").modal("show");
                }
            })
        }


        function ModalDetalleTGastosInternos() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleGastosInternos',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Gastos Internos");
                    $("#ModalDetalle").modal("show");
                }
            })
        }



        function ModalDetalleTarjetasAmarillas() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleTarjetasAmarillas',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Tarjetas Amarillas");
                    $("#ModalDetalle").modal("show");
                }
            })
        }

        function ModalDetalleTarjetasRojas() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleTarjetasRojas',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Tarjetas Rojas");
                    $("#ModalDetalle").modal("show");
                }
            })
        }

        function ModalDetalleArbitraje() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleArbitraje',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Arbitraje");
                    $("#ModalDetalle").modal("show");
                }
            })
        }

        function ModalDetalleInscripcion() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleInscripcion',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Inscripción");
                    $("#ModalDetalle").modal("show");
                }
            })
        }

        function ModalDetalleMulta() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleMulta',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Multas");
                    $("#ModalDetalle").modal("show");
                }
            })
        }
        
        function ModalDetalleObservaciones() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleObservaciones',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Observaciones");
                    $("#ModalDetalle").modal("show");
                }
            })
        }

        function ModalDetalleTransferencia() {

            var codCampeonato = $("#filCampeonato").val();
            $.ajax({
                url: 'clases/Cl_Finanzas.php?op=DetalleTransferencia',
                type: 'POST',
                data: {
                    codCampeonato: codCampeonato
                },
                success: function(data) {

                    $("#contenerdor_tabla1").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla1").html(data);
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $("#titulo").html("Transferencias");
                    $("#ModalDetalle").modal("show");
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