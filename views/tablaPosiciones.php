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
    <title>Tabla</title>
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

<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse" onload="cargarDatos()">
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
                        <div class="col-md-10 pb-3">
                            <h1 class="m-0">Tabla de Posiciones: <?php echo $_SESSION["tipoCampeonato"]; ?></h1>
                        </div>
                                <?php   
                                if($idRol == 1 ) {
                                    if($_SESSION["tipoCampeonato"] == "Liguilla"){
                                    ?>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="btn btn-primary btn-block" onclick="ConfirmarCierreTorneo()"><i class="fas fa-plus-circle"></i> Terminar Torneo</button>
                                        </div>
                                    <?php
                                    }
                                    else{
                                        ?>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="btn btn-primary btn-block" onclick="ConfirmarSiguienteFase()" id="btnSiguienteFase"><i class="fas fa-clipboard-list"></i> Siguiente Fase</button>
                                        </div>
                                        <?php
                                    }
                                } ?>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <?php if($_SESSION["tipoCampeonato"] == 'Liguilla'){ ?>
            <section class="col-lg-12 col-md-12">
                <div class="card info-box shadow-lg">
                    <div class="card-body">
                        <div id="contenerdor_tabla" class="table-responsive shadow-lg">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Equipo</th>
                                        <th>PTS</th>
                                        <th>PJ</th>
                                        <th>PG</th>
                                        <th>PE</th>
                                        <th>PP</th>
                                        <th>GF</th>
                                        <th>GE</th>
                                        <th>GD</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody id="RellenarTabla">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <br>
            </section>
            <?php }
            else{?>
            <section class="col-lg-12 col-md-12" id="grupo1">
                <div class="card info-box shadow-lg">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10 pb-3">
                                <h5 class="m-0"><b> Grupo 1 </b></h5>
                            </div>
                            <?php   
                                if($idRol == 1 ) {
                                  
                                    ?>
                            <div class="col-12 col-md-2" id="btnAñadirEquiposGrupo1">
                                <button type="button" class="btn btn-primary btn-block" onclick="añadirEquipos1()"><i class="fas fa-plus-circle"></i> Añadir equipos</button>
                            </div>
                            <?php } ?>    
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="contenerdor_tabla4" class="table-responsive shadow-lg">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>POSICIÓN</th>
                                        <th>NOMBRE EQUIPOS</th>
                                        <th>PTS</th>
                                        <th>PJ</th>
                                        <th>PG</th>
                                        <th>PE</th>
                                        <th>PP</th>
                                        <th>GF</th>
                                        <th>GE</th>
                                        <th>GD</th>
                                        <th>ACCIÓN</th>
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

            <section class="col-lg-12 col-md-12" id="grupo2">
                <div class="card info-box shadow-lg">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10 pb-3">
                                <h5 class="m-0"><b> Grupo 2 </b></h5>
                            </div>
                            <?php   
                                if($idRol == 1 ) {
                                  
                                    ?>
                            <div class="col-12 col-md-2" id="btnAñadirEquiposGrupo2">
                                <button type="button" class="btn btn-primary btn-block" onclick="añadirEquipos2()"><i class="fas fa-plus-circle"></i> Añadir equipos</button>
                            </div>
                            <?php  } ?>                  
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="contenerdor_tabla5" class="table-responsive shadow-lg">
                            <table id="example5" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>POSICIÓN</th>
                                        <th>NOMBRE EQUIPOS</th>
                                        <th>PTS</th>
                                        <th>PJ</th>
                                        <th>PG</th>
                                        <th>PE</th>
                                        <th>PP</th>
                                        <th>GF</th>
                                        <th>GE</th>
                                        <th>GD</th>
                                        <th>ACCIÓN</th>
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

            <div class="row ml-1 mr-1">
               
                    <section class="col-lg-6 col-md-6" id="octavos">
                        <div class="card info-box shadow-lg">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-10 pb-3">
                                        <h5 class="m-0" id="TituloClasificacion"><b> Octavos de Final </b></h5>
                                    </div>            
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="contenerdor_tabla6" class="table-responsive shadow-lg">
                                    <table id="example6" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>EQUIPO 1</th>
                                                <th></th>
                                                <th>EQUIPO 2</th>                                  
                                                <th>ACCIÓN</th>
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
               

               
                    <section class="col-lg-6 col-md-6" id="cuartos">
                        <div class="card info-box shadow-lg">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-10 pb-3">
                                        <h5 class="m-0" id="TituloClasificacion1"><b> Cuartos de Final </b></h5>
                                    </div>            
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="contenerdor_tabla7" class="table-responsive shadow-lg">
                                    <table id="example7" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>EQUIPO LOCAL</th>
                                                <th></th>
                                                <th>EQUIPO VISITANTE</th>                                  
                                                <th>ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Equipo 1</td>
                                                <td>vs</td>
                                                <td>Equipo 2</td>
                                                <td><button type="button" class="btn btn-primary btn-sm" disabled>Generar Partido</button></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Equipo 3</td>
                                                <td>vs</td>
                                                <td>Equipo 4</td>
                                                <td><button type="button" class="btn btn-primary btn-sm" disabled>Generar Partido</button></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Equipo 5</td>
                                                <td>vs</td>
                                                <td>Equipo 6</td>
                                                <td><button type="button" class="btn btn-primary btn-sm" disabled>Generar Partido</button></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Equipo 7</td>
                                                <td>vs</td>
                                                <td>Equipo 8</td>
                                                <td><button type="button" class="btn btn-primary btn-sm" disabled>Generar Partido</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <br>
                    </section>
               
            </div>
            <?php }?>
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

    <!-- Modal ver detalle de partidos por equipo -->
    <div class="modal fade" id="modalVerPartidos">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloEquipo"><i class="fas fa-file-signature"></i> Partidos del Equipo</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="contenerdor_tabla2" class="table-responsive">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha Partido</th>
                                    <th>Equipo Local</th>
                                    <th>Resultados</th>
                                    <th>Equipo Visitante</th>
                                </tr>
                            </thead>
                            <tbody >

                            </tbody>
                        </table>
                    </div>
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

     <!-- Modal para añaidr equipos a los grupos -->
     <div class="modal fade" id="ModalAñadirEquipos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-file-signature"></i> Lista de Equipos</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="nombreGrupo">
                    <h5 class="modal-title" id="tituloGrupo"></h5><br>
                    <div id="contenerdor_tabla3" class="table-responsive">
                        <table id="example3" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre Equipo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody >

                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

     <!-- Modal para elegir al equipo ganador -->
     <div class="modal fade" id="ModalElegirGanador">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-trophy"></i> Equipo Ganador</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="formEditarLote" class="row g-3">
                        <div class="col-md-12">
                            <div class="callout callout-info shadow-lg">                
                                <p>Selccionar al equipo que pasara a la siguiente fase.</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Seleccionar Equipo</label>
                                <select class="form-control" id="equipoGanador"> 
                                    
                                </select>   
                            </div>
                        </div>
                        <input type="hidden" id="idClasificacion">
                    </form>
                    <div id="cargando_add"></div>
                </div>
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-primary" id="btn_nuevo_add" onclick="RegistrarEquipoGanador();">Registrar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <script>

        function RegistrarEquipoGanador(){

            $("#btn_nuevo_add").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

            var idEquipoGanador = $("#equipoGanador").val();
            var idClasificacion= $("#idClasificacion").val();

            $.ajax({
                url: 'clases/Cl_Tabla.php?op=RegistrarEquipoGanador',
                type: 'POST',
                data:{
                    idEquipoGanador: idEquipoGanador,
                    idClasificacion: idClasificacion
                },
                success: function(data) {
                   
                   
                    if(data == 1){
                        $("#cargando_add").html('');                     
                        Swal.fire("Exito..!","Equipo ganador registrado correctamente","success");
                        location.reload();
                    }
                    else{
                        $("#cargando_add").html('');
                        $("#btn_nuevo_add").prop("disabled", false); 
                        Swal.fire("Error..!","Error al registrar al equipo ganador","error");
                    }                 
                },
                error:function(){
                    Swal.fire("Error..!","Error inseperado, favor intentar mas tarde","error");
                    $("#cargando_add").html('');
                    $("#btn_nuevo_add").prop("disabled", false); 
                }
            })
        }


        //abre un modal para registrar al equipo ganador de la fase de octavos
        function ElegirGanador(id){
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=ElegirGanador',
                type: 'POST',
                data:{
                    id: id
                },
                success: function(data) {
                    
                    var resp = $.parseJSON(data);
                   
                    html = "<option value='"+resp.idEquipo1+"'>"+resp.equipo1+"</option>";
                    $("#equipoGanador").html(html+ "<option value='"+resp.idEquipo2+"'>"+resp.equipo2+"</option>");
                    $("#idClasificacion").val(id);
                    $("#ModalElegirGanador").modal("show");
                }
            })
        }

        function ConfirmarSiguienteFase() {
            var texto="";
           
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=ConfirmarSiguienteFase',
                type: 'POST',
                success: function(data) {
                    if(data == 'Octavos'){
                        texto = 'Se pasara a formar los equipos para los octavos de final, el primero del grupo 1 VS el ultimo del grupo 2 y asi sucesivamente!';
                    }
                    else{
                        if(data == 'Cuartos'){
                        texto = 'Se pasara a formar los equipos para los cuartos de final!';
                        }
                        else{
                            if(data == 'Semifinal'){
                            texto = 'Se pasara a formar los equipos para la semifinal!';
                            }
                            else{
                                if(data == 'Final'){
                                texto = 'Se pasara a formar los equipos para la final!';
                                }
                                else{
                                    if(data == 'Torneo Terminado'){
                                        swal.fire("Torneo Finalizado..!","Todos los partidos del torneo ya se jugaron",'warning');
                                                return false;
                                    }
                                    else{
                                        if(data == 'FaltanPartidos'){
                                            swal.fire("Oops..!","Faltan jugar algunos partidos para pasar a la siguiente fase",'warning');
                                                return false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    Swal.fire({
                    title: 'Esta Seguro?',
                    text: texto,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Siguiente Fase!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if(data == 'Octavos'){
                                SiguienteFaseOctavos();
                            }
                            else{
                                if(data == 'Cuartos'){
                                    SiguienteFaseCuartos();
                                }
                                else{
                                    if(data == 'Semifinal'){
                                        SiguienteFaseSemifinal();
                                    }
                                    else{
                                        if(data == 'Final'){
                                            SiguienteFaseFinal();
                                        }
                                    }
                                }
                            }
                        }
                    })
                }
            })
         
        }

        function SiguienteFaseFinal(){
            $("#btn_nuevo_add1").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=SiguienteFaseFinal',
                type: 'POST',
                success: function(data) { 
                 
                    if(data == 1){                    
                        $("#cargando_add1").html('');
                        $("#btn_nuevo_add").prop("disabled", false); 
                        
                        ListaEquiposFinal1();
                        ListaEquiposFinal2();

                        $("#octavos").show();
                        $("#cuartos").show();
                        $("#grupo1").hide();
                        $("#grupo2").hide();
                        Swal.fire("Exito..!","Se paso a la siguiente fase correctamente","success");
                    }
                    else{
                        Swal.fire("Oops..!","Ha ocurrido un error, no se ha podido pasar a la siguiente fase","error");
                    }
                },
                error: function(){
                    Swal.fire("Oops..!","Error inesperado, intente de nuevo mas tarde","error");
                }
            })       
        }

        function SiguienteFaseSemifinal(){
            $("#btn_nuevo_add1").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=SiguienteFaseSemifinal',
                type: 'POST',
                success: function(data) { 
                 
                    if(data == 1){                    
                        $("#cargando_add1").html('');
                        $("#btn_nuevo_add").prop("disabled", false); 
                        
                        ListaEquiposSemifinal();
                        
                        $("#octavos").show();
                        $("#cuartos").show();
                        $("#grupo1").hide();
                        $("#grupo2").hide();
                        Swal.fire("Oops..!","Se paso a la siguiente fase correctamente","success");
                    }
                    else{
                        Swal.fire("Oops..!","Ha ocurrido un error, no se ha podido pasar a la siguiente fase","error");
                    }
                },
                error: function(){
                    Swal.fire("Oops..!","Error inesperado, intente de nuevo mas tarde","error");
                }
            })       
        }

        function SiguienteFaseCuartos(){
            $("#btn_nuevo_add1").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=SiguienteFaseCuartos',
                type: 'POST',
                success: function(data) { 
                 
                    if(data == 1){                    
                        $("#cargando_add1").html('');
                        $("#btn_nuevo_add").prop("disabled", false); 
                        
                       
                        ListaEquiposCuartos();
                        $("#octavos").show();
                        $("#cuartos").show();
                        $("#grupo1").hide();
                        $("#grupo2").hide();
                        Swal.fire("Exito..!","Se paso a la siguiente fase correctamente","success");
                       
                    }
                    else{
                        Swal.fire("Oops..!","Ha ocurrido un error, no se ha podido pasar a la siguiente fase","error");
                    }
                },
                error: function(){
                    Swal.fire("Oops..!","Error inesperado, intente de nuevo mas tarde","error");
                }
            })       
        }

        function SiguienteFaseOctavos(){
            $("#btn_nuevo_add1").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=SiguienteFaseOctavos',
                type: 'POST',
                success: function(data) { 
                 
                    if(data == 1){                    
                        $("#cargando_add1").html('');
                        $("#btn_nuevo_add").prop("disabled", false); 
                        
                        ListaEquiposOctavos();
                        
                        $("#octavos").show();
                        $("#cuartos").show();
                        $("#grupo1").hide();
                        $("#grupo2").hide();
                        Swal.fire("Exito..!","Se paso a la siguiente fase correctamente","success");
                    }
                    else{
                        //Swal.fire("Oops..!","Ha ocurrido un error, no se ha podido pasar a la siguiente fase","error");
                        Swal.fire("Oops..!",data,"error");
                    }
                },
                error: function(){
                    Swal.fire("Oops..!","Error inesperado, intente de nuevo mas tarde","error");
                }
            })       
        }

        function ListaEquiposOctavos() {
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaOctavos',
                type: 'POST',
                success: function(data) {
                    $("#TituloClasificacion").html("<b>Octavos de Final</b>");
                    $("#TituloClasificacion1").html("<b>Cuartos de Final</b>");
                    $("#contenerdor_tabla6").html('');
                    $('#example6').DataTable().destroy();
                    $("#contenerdor_tabla6").html(data);            
                    
                }
            })
    
        }

        function ListaEquiposCuartos() {
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaCuartos',
                type: 'POST',
                success: function(data) {
                    $("#TituloClasificacion").html("<b>Cuartos de Final</b>");
                    $("#TituloClasificacion1").html("<b>Semifinal</b>");
                    $("#contenerdor_tabla6").html('');
                    $('#example6').DataTable().destroy();
                    $("#contenerdor_tabla6").html(data);            
                    
                }
            })
    
        }
        
        function ListaEquiposSemifinal() {
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaSemifinal',
                type: 'POST',
                success: function(data) {
                    $("#TituloClasificacion").html("<b>Semifinal</b>");
                    $("#TituloClasificacion1").html("<b>Final</b>");
                    $("#contenerdor_tabla6").html('');
                    $('#example6').DataTable().destroy();
                    $("#contenerdor_tabla6").html(data);            
                    
                }
            })
    
        }

        function ListaEquiposFinal1() {
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaFinal1',
                type: 'POST',
                success: function(data) {
                    $("#TituloClasificacion").html("<b>Semifinal</b>");
                    $("#TituloClasificacion1").html("<b>Final</b>");
                    $("#contenerdor_tabla6").html('');
                    $('#example6').DataTable().destroy();
                    $("#contenerdor_tabla6").html(data);            
                    
                }
            })
    
        }

        function ListaEquiposFinal2() {
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaFinal2',
                type: 'POST',
                success: function(data) {
                    $("#TituloClasificacion").html("<b>Final</b>");
                    $("#TituloClasificacion1").html("<b>3er y 4to Lugar</b>");
                    $("#contenerdor_tabla7").html('');
                    $('#example7').DataTable().destroy();
                    $("#contenerdor_tabla7").html(data);            
                    
                }
            })
    
        }

        function GenerarPartidoOctavos(id) {
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=DatosEquipoPartidoOctavos',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {       
                    if(data == 'Error'){
                        Swal.fire("Oops..!","Error al generar el partido","error");
                    }
                    else{
                        var resp = $.parseJSON(data);
                        
                        location.href = "partido.php?idEquipo1="+resp.idEquipo1+"&idEquipo2="+resp.idEquipo2+"&modo="+resp.nombreClasificacion+"";
                    }
                }
            })
    
        }
        

        function añadirGrupo(id){
            var nombreGrupo =  $("#nombreGrupo").val();
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=AñadirEquipoGrupo',
                type: 'POST',
                data:{
                    nombreGrupo: nombreGrupo,
                    idEquipo: id
                },
                success: function(data) {
                   if(data == 1){
                        Swal.fire("Exito..!","Equipo añadido correctamente", "success");
                        location.reload();
                   }
                   else{
                        Swal.fire("Oops..!","Error al añadir el equipo al grupo "+nombreGrupo,"error");
                   }
                }
            })
        }

        function añadirEquipos1() {
            
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=ListadoEquiposDisponibles',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla3").html('');
                    $('#example3').DataTable().destroy();
                    $("#contenerdor_tabla3").html(data);
                    $("#nombreGrupo").val(1);
                    $("#tituloGrupo").html("Asignar al Grupo 1");
                    $("#ModalAñadirEquipos").modal("show");
                   
                }
            })
    
        }

        function añadirEquipos2() {
          
          
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=ListadoEquiposDisponibles',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla3").html('');
                    $('#example3').DataTable().destroy();
                    $("#contenerdor_tabla3").html(data);
                    $("#nombreGrupo").val(2);
                    $("#tituloGrupo").html("Asignar al Grupo 2");
                    $("#ModalAñadirEquipos").modal("show");
                   
                }
            })
           
        }

            function ConfirmarCierreTorneo() {

                Swal.fire({
                title: 'Esta Seguro?',
                text: "Se registrara como campeón y subcampeón al primero y segundo de la tabla de posición respectivamente!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Terminar torneo!'
                }).then((result) => {
                if (result.isConfirmed) {
                    CierreTorneo();
                }
                })
            }

            function CierreTorneo() {

              
                let arreglo_equipo = [];

                $("#example1 tbody#RellenarTabla tr").each(function() {
                
                arreglo_equipo.push($(this).find('td').eq(1).text());
                })
               
                let equipo = arreglo_equipo.toString();
               
                $.ajax({
                url: 'clases/Cl_Tabla.php?op=terminarTorneo',
                type: 'POST',
                data: {
                    equipo: equipo
                },
                success: function(vs) {
                    if (vs == 2) {
                        Swal.fire("Error..!", "ha ocurrido un error al terminar el torneo", "error");
                    } else {
                        if (vs == 1) {
                            Swal.fire('Exito..!', 'Torneo terminado correctamente.', 'success');
                            location.reload();
                        }
                        else {
                            if (vs == 3) {
                                Swal.fire('Advertencia..!', 'El torneo ya fue finalizado anteriormente.', 'warning');
                               
                            }
                        }
                    }
                }
                })
            }

      

        function cargarDatos() {

            $("#octavos").hide();
            $("#cuartos").hide();
            TablaPosiciones();
            ValidarBotonAñadir1();
            ValidarBotonAñadir2();
            TablaPosicionesGrupo1();
            TablaPosicionesGrupo2();
            TablaSiguienteFase();
        }

        function TablaSiguienteFase(){
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=ValidarSiguienteFase',
                type: 'POST',          
                success: function(data) {
                
                    if(data == 1){
                        $("#octavos").hide();
                        $("#cuartos").hide();
                    }
                    else{
                        if(data == 3){
                            Swal.fire('Error..!', 'Error al validar fase de grupo', 'error');
                        } 
                        else{
                            if(data == 'Octavos'){
                                ListaEquiposOctavos();
                                $("#octavos").show();
                                $("#cuartos").show();
                                $("#grupo1").hide();
                                $("#grupo2").hide();
                            }
                            else{
                                if(data == 'Cuartos'){
                                    ListaEquiposCuartos();
                                    $("#octavos").show();
                                    $("#cuartos").show();
                                    $("#grupo1").hide();
                                    $("#grupo2").hide();
                                }
                                else{
                                    if(data == 'Semifinal'){
                                        ListaEquiposSemifinal();
                                        $("#octavos").show();
                                        $("#cuartos").show();
                                        $("#grupo1").hide();
                                        $("#grupo2").hide();
                                    }
                                    else{
                                        if(data == 'Final2' || data == 'Final' ){
                                            $("#btnSiguienteFase").prop("disabled", true);
                                            ListaEquiposFinal1();
                                            ListaEquiposFinal2();
                                            $("#octavos").show();
                                            $("#cuartos").show();
                                            $("#grupo1").hide();
                                            $("#grupo2").hide();
                                        }
                                    }
                                }
                            }
                        }
                    }
                   
                }
            })
        }

        function ValidarBotonAñadir1() {
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=ValidarBotonAñadir1',
                type: 'POST',
              
                success: function(data) {
                    if(data == 1){
                        $("#btnAñadirEquiposGrupo1").hide();
                    }
                   
                }
            })
        }

        function ValidarBotonAñadir2() {
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=ValidarBotonAñadir2',
                type: 'POST',
              
                success: function(data) {
                    if(data == 1){
                        $("#btnAñadirEquiposGrupo2").hide();
                    }               
                }
            })
        }

        function verDetallesPartidos(idEquipo) {

            var modo = '';
            $.ajax({
                url: 'clases/Cl_Tabla.php?op=DetallePartidosEquipos',
                type: 'POST',
                data: {
                    idEquipo: idEquipo,
                    modo:modo
                },
                success: function(data) {
                    $("#contenerdor_tabla2").html('');
                    $('#example2').DataTable().destroy();
                    $("#contenerdor_tabla2").html(data);
                    $("#modalVerPartidos").modal("show");
                    // $("#example1").DataTable({
                    //     "responsive": true, "lengthChange": false, "autoWidth": false,
                    //     "language": lenguaje_español
                    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function verDetallesPartidosClasificados(idEquipo) {

            var modo = 'octavos';

            $.ajax({
                url: 'clases/Cl_Tabla.php?op=DetallePartidosEquipos',
                type: 'POST',
                data: {
                    idEquipo: idEquipo,
                    modo: modo
                },
                success: function(data) {
                    $("#contenerdor_tabla2").html('');
                    $('#example2').DataTable().destroy();
                    $("#contenerdor_tabla2").html(data);
                    $("#modalVerPartidos").modal("show");
                }
            })
        }

        function TablaPosiciones() {

            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaPosiciones',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla").html('');
                    $('#example1').DataTable().destroy();
                    $("#contenerdor_tabla").html(data);
                    //$("#botonDirectivaActual").hide();
                    // $("#example1").DataTable({
                    //     "responsive": true, "lengthChange": false, "autoWidth": false,
                    //     "language": lenguaje_español
                    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function TablaPosicionesGrupo1() {

            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaPosicionesGrupo1',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla4").html('');
                    $('#example4').DataTable().destroy();
                    $("#contenerdor_tabla4").html(data);
                    //$("#botonDirectivaActual").hide();
                    // $("#example1").DataTable({
                    //     "responsive": true, "lengthChange": false, "autoWidth": false,
                    //     "language": lenguaje_español
                    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            })
        }

        function TablaPosicionesGrupo2() {

            $.ajax({
                url: 'clases/Cl_Tabla.php?op=TablaPosicionesGrupo2',
                type: 'POST',
                success: function(data) {
                    $("#contenerdor_tabla5").html('');
                    $('#example5').DataTable().destroy();
                    $("#contenerdor_tabla5").html(data);
                    //$("#botonDirectivaActual").hide();
                    // $("#example1").DataTable({
                    //     "responsive": true, "lengthChange": false, "autoWidth": false,
                    //     "language": lenguaje_español
                    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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