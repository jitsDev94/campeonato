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
  <title>Torneo</title>
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
              <h1 class="m-0">Torneo</h1>
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

      <section class="content">
        <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg">
              <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">
                  <h5>Ingresos</h5>
                </span>
                <span class="info-box-number" id="TotalIngresos"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- ./col -->

          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg">
              <span class="info-box-icon bg-warning"><i class="fas fa-shopping-cart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">
                  <h5>Gastos</h5>
                </span>
                <span class="info-box-number" id="TotalGastos"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- ./col -->

          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg">
              <span class="info-box-icon bg-info"><i class="fas fa-hand-holding-usd"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">
                  <h5>Ganancias</h5>
                </span>
                <span class="info-box-number" id="TotalGanacias"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg">
              <span class="info-box-icon bg-secondary"><i class="fas fa-wallet"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">
                  <h5>Totales</h5>
                </span>
                <span class="info-box-number" id="Totales"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- ./col -->
        </div>
      </section>

      <section class="col-lg-12 col-md-12"><br>
        <div id="accordion">
          <div class="card info-box shadow-lg">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Filtros Historial Directiva
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
                          <label for="inputfecha" class="form-label">Nombre Encargado</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="filNombre" placeholder="Ingresar Nombre">
                          </div>
                          <br>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="inputCasa" class="form-label"><b>Nombre Torneo</b></label>
                            <select class="form-control" id="filCampeonato">
                              <option value="0" selected disabled>Seleccionar Torneo...</option>
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
                            <button type="button" class="btn btn-primary" id="btn_add1" onclick="FiltrarDirectivas()"><i class="fas fa-filter"></i> Filtrar</button>
                            <button type="button" class="btn btn-secondary" id="botonDirectivaActual" onclick="ListaDirectiva()"> Mostrar Actual</button>
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
        <div class="row">
          <section class="col-lg-6 col-md-6"> <br>
            <div class="card info-box shadow-lg">
              <div class="card-header">
                <div class="row">
                  <div class="col-12 col-md-4">
                    <label for="">
                      <h4>Directiva</h4>
                    </label>
                  </div>
                  <div class="col-12 col-md-4">
                    <button type="button" class="btn btn-danger btn-block" onclick="ConfirmarReiniciarDirectiva()"><i title="Nueva directiva" class="fas fa-folder-plus"></i> Nueva</button>
                  </div>
                  <div class="col-12 col-md-4">
                    <button type="button" class="btn btn-primary btn-block" onclick="ModalRegistrarDirectiva()"><i title="Agregar nuevos miembros a la directiva" class="fas fa-plus-circle"></i> Agregar</button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="contenerdor_tabla2" class="table-responsive">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Gestion</th>
                        <th>Fecha Inicio</th>
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
          </section>

          <section class="col-lg-6 col-md-6"> <br>
            <div class="card info-box shadow-lg">
              <div class="card-header">
                <div class="row">
                  <div class="col-12 col-md-8">
                    <label for="">
                      <h4>Torneos</h4>
                    </label>
                  </div>
                  <div class="col-12 col-md-4">
                    <button type="button" class="btn btn-primary btn-block" onclick="ModalRegistrarTorneo()"><i class="fas fa-plus-circle"></i> Nuevo Torneo</button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="contenerdor_tabla3" class="table-responsive">
                  <table id="example3" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre Torneo</th>
                        <th>Fecha Inicio</th>
                        <th>Estado</th>
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

  <!-- Modal nuevo/editar torneo -->
  <div class="modal fade" id="ModalRegistrarTorneo">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="tituloJugador"><i class="fas fa-trophy-alt"></i> Nuevo Torneo</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" id="formEditarLote" class="row g-3">
            <div class="col-md-6">
              <br>
              <label for="inputName" class="form-label shadow-lg"><b>Nombre Torneo(*)</b></label>
              <input type="text" class="form-control shadow-lg" id="nombre" placeholder="Ingresar Nombre">
            </div>
            <div class="col-md-6">
              <br>
              <label for="inputName" class="form-label"><b>Fecha Inicio(*)</b></label>
              <input type="date" class="form-contro shadow-lg" id="fecha">
            </div>
            <div class="col-md-6">
              <br>
              <label for="inputPassword" class="col-form-label">Tipo Campeonato(*)</label>
             
                <select class="form-control shadow-lg" id="tipoCampeonato">
                  <option selected>Liguilla</option>
                  <option>Fase de Grupo</option>
                </select>
              
            </div>
          </form>
          <div id="cargando_add"></div>
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


  <!-- Modal nuevo/editar directiva -->
  <div class="modal fade" id="ModalRegistrarDirectiva">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="tituloDirectiva"><i class="nav-icon fas fa-briefcase"></i> Nueva Directiva</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" id="formEditarLote" class="row g-3">
            <div class="col-md-12">
              <br>
              <label for="inputName" class="form-label"><b>Nombre(*)</b></label>
              <input type="text" class="form-control shadow-lg" id="nombre2" placeholder="Ingresar Nombre">
            </div>
            <div class="col-md-12">
              <br>
              <label for="inputName" class="form-label"><b>Cargo(*)</b></label>
              <input type="text" class="form-control shadow-lg" id="cargo2" placeholder="Ingresar Cargo">
            </div>
            <div class="col-md-6">
              <br>
              <label for="inputName" class="form-label"><b>Fecha Inicio(*)</b></label>
              <div class="input-group">
                <input type="date" class="form-control shadow-lg" id="fecha2">
              </div>
            </div>
            <div class="col-md-6" id="Equipos">
              <div class="form-group">
                <br>
                <label for="inputCasa" class="form-label"><b>Torneo(*)</b></label>
                <select class="form-control shadow-lg" id="idCampeonato">
                  <!-- <option value="0" selected disabled>Seleccionar...</option> -->
                  <?php
                  $consultar = "SELECT * FROM Campeonato where estado = 'En Curso'";
                  $resultado1 = mysqli_query($conectar, $consultar);
                  while ($listado = mysqli_fetch_array($resultado1)) {
                  ?>
                    <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombre']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div id="cargando_add"></div>
              <input type="hidden" class="form-control" id="id">
            </div>
          </form>
          
        </div>
        <div class="modal-footer col-md-12">
          <div id="botonRegistroDirectiva"></div>
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

      ListaTorneo();
      ListaDirectiva();

      TotalIngresos();
      TotalGastos();
      TotalGanacias();
      Totales();
    }


    function TotalIngresos() {

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=TotalIngresos',
        type: 'POST',
        success: function(data) {
          if (data == "error") {
            Swal.fire("Oops..!", "Error al cargar el total de ingresos", "warning");
          } else {
            if(data == null || data == ""){
              $("#TotalIngresos").html("<h4>Bs. 0 </h4>");
            }
            else{
              data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
              data = data.split('').reverse().join('').replace(/^[\,]/,'');
              $("#TotalIngresos").html("<h4>Bs. " + data + ".00</h4>");
            }
            
          }
        }
      })
    }

    function TotalGastos() {

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=TotalGastos',
        type: 'POST',
        success: function(data) {

          if (data == "error") {
            Swal.fire("Oops..!", "Error al cargar el total de gastos", "warning");
          } else {
            if (data == null || data == "") {
              $("#TotalGastos").html("<h4>Bs. 0</h4>");
            } else {
              data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
              data = data.split('').reverse().join('').replace(/^[\,]/,'');
              $("#TotalGastos").html("<h4>Bs. " + data + "</h4>");
            }

          }
        }
      })
    }

    function TotalGanacias() {

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=TotalGananciaActual',
        type: 'POST',
        success: function(data) {
          if (data == "error") {
            Swal.fire("Oops..!", "Error al calcular la ganancia total", "warning");
          } else {
            if (data == null || data == "") {
              $("#TotalGanacias").html("<h4>Bs. 0</h4>");
            }
            else{
              data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
              data = data.split('').reverse().join('').replace(/^[\,]/,'');
              $("#TotalGanacias").html("<h4>Bs. " + data + ".00</h4>");
            }
          }
        }
      })
    }


    function Totales() {

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=TotalGanancia',
        type: 'POST',
        success: function(data) {
          if (data == "error") {
            Swal.fire("Oops..!", "Error al calcular el total de ganancia general", "warning");
          } else {
            if (data == null || data == "") {
              $("#Totales").html("<h4>bs. 0</h4>");
            }
            else{
              data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
              data = data.split('').reverse().join('').replace(/^[\,]/,'');
              $("#Totales").html("<h4>bs. " + data + ".00</h4>");
            }
          }
        }
      })
    }

    function ConfirmarReiniciarDirectiva(id) {
      Swal.fire({
        title: 'Esta Seguro?',
        text: "Se removera a los miembros de la directiva actual..!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, remover!'
      }).then((result) => {
        if (result.isConfirmed) {
          ReiniciarDirectiva();
        }
      })
    }

    function ReiniciarDirectiva() {
      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=ReiniciarDirectiva',
        type: 'POST',
        success: function(data) {
          if (data == "2") {
            Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del miembro de la directiva", "error");
          } else {
            if (data == "1") {
              Swal.fire("Exito..!", "Directiva reiniciada correctamente", "success");
              location.reload();
            }
          }
        }
      })
    }

    function ModalRegistrarTorneo() {
      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=TorneoFinalizado',
        type: 'POST',
        success: function(data) {

          if (data == "error") {
            Swal.fire("Error..!", "Ha ocurrido un error al validar el registro de un equipo campeón", "error");
          } else {
            if (data == "abierto") {
              Swal.fire("Alerta..!", "No se puede finalizar el torneo actual porque no ha registrado un campeón y subcampeón", "warning");
            } else {
              if (data == "cerrado") {
                ConfirmarNuevoTorneo();

              }
            }
          }
        }
      })

    }

    function ConfirmarNuevoTorneo() {

      Swal.fire({
        title: 'Esta Seguro?',
        text: "Al iniciar un nuevo torneo ya no podra cobrar las deudas pendientes del actual torneo!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Nuevo Torneo!'
      }).then((result) => {
        if (result.isConfirmed) {
          let hoy = new Date();
          document.getElementById("fecha").value = hoy.toJSON().slice(0, 10);
          $("#tituloJugador").html("<i class='fas fa-trophy'></i> Nuevo Torneo");
          $("#botonRegistro").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarTorneo()'>Registrar</button>");
          $('#ModalRegistrarTorneo').modal('show');
        }
      })
    }


    function ModalRegistrarDirectiva(id) {
      $("#tituloDirectiva").html("<i class='fas fa-briefcase'></i> Nuevos Miembros Directiva");
      $("#botonRegistroDirectiva").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='RegistrarDirectiva()'>Registrar</button>");
      $("#Equipos").show();
      $("#id").val("");
      $("#nombre2").val("");
      $("#fecha2").val("");
      $("#cargo2").val("");
      $('#ModalRegistrarDirectiva').modal('show');
    }

    function modalEditarDirectiva(id) {

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=DatosMiembrosDirectiva',
        type: 'POST',
        data: {
          id: id
        },
        success: function(data) {
         
          if (data == "") {
            Swal.fire("Error..!", "Ha ocurrido un error al obtener los datos del miembro de la directiva", "error");
          } else {
            var resp = $.parseJSON(data);
            $("#tituloDirectiva").html("<i class='nav-icon fas fa-edit'></i> Editar Encargados");
            $("#botonRegistroDirectiva").html("<button type='button' class='btn btn-primary' id='botonRegistro' onclick='EditarMiembroDirectiva()'>Editar</button>");
            $("#id").val(resp.id);
            $("#nombre2").val(resp.nombre);
            $("#fecha2").val(resp.fechaNombramiento);
            $("#cargo2").val(resp.cargo);
            $("#Equipos").hide();
            $('#ModalRegistrarDirectiva').modal('show');
          }
        }
       
        
      })
    }

    function EditarMiembroDirectiva() {

      var id = $('#id').val();
      var nombre = $('#nombre2').val();
      var fecha = $('#fecha2').val();
      var cargo = $('#cargo2').val();


      if (nombre == "" || cargo == "" || fecha == "") {
        Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
        return false;
      }

      $("#botonRegistro").prop("disabled", true);
      $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');


      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=EditarMiembroDirectiva',
        type: 'POST',
        data: {
          id: id,
          nombre: nombre,
          cargo: cargo,
          fecha: fecha
        },
        success: function(vs) {
          $("#cargando_add").html('');
          $("#botonRegistro").prop("disabled", false); 
          if (vs == 2) {
            Swal.fire("Error..!", "Ha ocurrido un error al editar a miembro de la directiva", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito!', 'Datos editado correctamente', 'success');
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


    function ConfirmarEliminar(id) {
      Swal.fire({
        title: 'Esta Seguro?',
        text: "Ya no podra recurar la información!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
      }).then((result) => {
        if (result.isConfirmed) {
          EliminarDirectiva(id);
        }
      })
    }

    function EliminarDirectiva(id) {

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=EliminarDirectiva',
        type: 'POST',
        data: {
          id: id
        },
        success: function(vs) {
          if (vs == 2) {
            Swal.fire("Error..!", "ha ocurrido un error al deshabilitar", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito..!', 'Eliminado correctamente.', 'success');
              location.reload();
            }
          }
        }
      })
    }

    function ListaDirectiva() {

      $("#contenerdor_tabla2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Directiva ...</center></div>');

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=ListaDirectiva',
        type: 'POST',
        success: function(data) {
          $("#contenerdor_tabla2").html('');
          $('#example2').DataTable().destroy();
          $("#contenerdor_tabla2").html(data);
          $("#botonDirectivaActual").hide();
        }
      })
    }


    function ListaTorneo() {

      $("#contenerdor_tabla3").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Torneos ...</center></div>');

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=ListaTorneo',
        type: 'POST',
        success: function(data) {
          $("#contenerdor_tabla3").html('');
          $('#example3').DataTable().destroy();
          $("#contenerdor_tabla3").html(data);
        }
      })
    }

    function FiltrarDirectivas() {

      var nombre = $("#filNombre").val();
      var idCampeonato = $("#filCampeonato").val();

      if (nombre == "" && idCampeonato == null) {
        Swal.fire('Aviso..!', 'Debe seleccionar una opcion de filtro', 'warning');
        return false;
      }

      $("#btn_add1").prop("disabled", true);
      $("#cargando_add1").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
        

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=FiltrarDirectivas',
        type: 'POST',
        data: {
          nombre: nombre,
          idCampeonato: idCampeonato
        },
        success: function(data) {
          $("#cargando_add1").html('');
          $("#btn_add1").prop("disabled", false); 

          $("#contenerdor_tabla2").html('');
          $('#example2').DataTable().destroy();
          $("#contenerdor_tabla2").html(data);
          $("#botonDirectivaActual").show();
        },
        error: function(data){
          $("#cargando_add1").html('');
          $("#btn_add1").prop("disabled", false); 
        }
      })
    }


    function RegistrarDirectiva() {

      var nombre = $('#nombre2').val();
      var cargo = $('#cargo2').val();
      var fecha = $('#fecha2').val();
      var idCampeonato = $('#idCampeonato').val();

      if (nombre == "") {
        Swal.fire("Campos Vacios..!", "Debe ingresar el nombre del nuevo integrante de la direciva", "warning");
        return false;
      } else {
        if (cargo == "") {
          Swal.fire("Campos Vacios..!", "Debe ingresar el cargo", "warning");
          return false;
        } else {
          if (fecha == "") {
            Swal.fire("Campos Vacios..!", "Debe ingresar la fecha de nombramiento", "warning");
            return false;
          } else {
            if (idCampeonato == null) {
              Swal.fire("Campos Vacios..!", "Debe ingresar el nombre del campeonato", "warning");
              return false;
            }
          }
        }
      }

      $("#botonRegistro").prop("disabled", true);
      $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
     

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=RegistrarDirectiva',
        type: 'POST',
        data: {
          nombre: nombre,
          cargo: cargo,
          fecha: fecha,
          idCampeonato: idCampeonato
        },
        success: function(vs) {
          $("#cargando_add").html('');
          $("#botonRegistro").prop("disabled", false); 
          if (vs == 2) {
            Swal.fire("Error..!", "Ha ocurrido un error al registrar la direciva", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito!', 'Encargado registrado correctamente', 'success');
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


    function RegistrarTorneo() {

      var nombre = $('#nombre').val();
      var fecha = $('#fecha').val();
      var tipoCampeonato = $("#tipoCampeonato").val();

      if (nombre == "" || fecha == "") {
        Swal.fire("Campos Vacios..!", "Debe completar todos los campos obligatorios", "warning");
        return false;
      }

      $("#botonRegistro").prop("disabled", true);
      $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
 

      $.ajax({
        url: 'clases/Cl_Campeonato.php?op=RegistrarTorneo',
        type: 'POST',
        data: {
          nombre: nombre,
          fecha: fecha,
          tipoCampeonato: tipoCampeonato
        },
        success: function(vs) {
          $("#cargando_add").html('');
          $("#botonRegistro").prop("disabled", false);

          if (vs == 2) {
            Swal.fire("Error..!", "Ha ocurrido un error al registrar el torneo", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito!', 'Torneo registrado correctamente', 'success');
              location.reload();
            } else {
              if (vs == 3) {
                Swal.fire('Error!', 'No se ha podido actualizar los anteriores torneos', 'error');

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