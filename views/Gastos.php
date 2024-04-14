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
  <title>Gastos</title>
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
    .card{
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
            <h1 class="m-0">Gastos</h1>
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
 
        <section class="content">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-lg">
                <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">              
                  <span class="info-box-text"><h5>Cancha</h5></span>
                  <span class="info-box-number" id ="TotalGastosCancha"></span>
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
                  <span class="info-box-text"><h5>Arbitro</h5></span>
                  <span class="info-box-number" id="TotalGastosArbitraje"></span>
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
                  <span class="info-box-text"><h5>Interno</h5></span>
                  <span class="info-box-number" id="TotalGastosInternos"></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-lg">
                <span class="info-box-icon bg-secondary"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">              
                  <span class="info-box-text"><h5>Total</h5></span>
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
                            Filtros Historial de Gastos
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
                                                <label for="inputCasa" class="form-label"><b>Nombre Torneo</b></label> 
                                                <select class="form-control" id="filCampeonato"> 
                                                    <option value="0" selected disabled>Seleccionar Torneo...</option>
                                                    <?php 
                                                      $consultar = "SELECT * FROM Campeonato where estado = 'Concluido'";
                                                      $resultado1 = mysqli_query($conectar, $consultar);
                                                      while ($listado = mysqli_fetch_array($resultado1)) {                                                
                                                    ?>
                                                    <option value="<?php echo $listado['id']; ?>"><?php echo $listado['nombre']; ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-top:32px;">
                                              <button type="button" class="btn btn-primary" id="btn_add1" onclick="Filtrar();"><i class="fas fa-filter"></i>  Filtrar</button>
                                              <button type="button" class="btn btn-secondary" id="botonDirectivaActual" onclick="CargarDatos();"> Mostrar Actual</button>
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

        <section class="col-lg-12 col-md-12">
            <div class="row">        
                <section class="col-lg-6 col-md-6"> <br>  
                    <div class="card info-box shadow-lg">
                    <div class="card-header">             
                        <div class="row"> 
                            <div class="col-12 col-md-8">
                                <label for=""><h4>Gastos Internos</h4></label>
                            </div>        
                            <div class="col-12 col-md-4">
                                <button type="button" class="btn btn-primary btn-block" onclick="ModalRegistrarGastosInternos()"><i title="Agregar nuevos miembros a la directiva" class="fas fa-plus-circle"></i> Nuevo Gasto</button>                             
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
                                  <th>Motivo</th>
                                  <th>Fecha</th>
                                  <th>Cantidad</th>
                                  <th>Precio</th>
                                  <th>Total</th>
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
                                <label for=""><h4>Cancha y Arbitraje</h4></label>
                            </div>       
                            <div class="col-12 col-md-4">
                                <button type="button" class="btn btn-primary btn-block" onclick="ModalRegistrarPagoCanchaArbitraje()"><i class="fas fa-plus-circle"></i> Nuevo Pago</button>                 
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
                                  <th>Motivo</th>
                                  <th>Fecha</th>
                                  <th>Total</th>
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

        <!-- Modal registro de gastos internos --> 
    <div class="modal fade" id="ModalRegistrarGastosInternos">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloDirectiva"><i class="nav-icon fas fa-cash-register"></i> Registro Nuevo Gasto</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarLote" class="row g-3">                                                     
                    <div class="col-md-6">
                      <div class="form-group">
                      <br>
                          <label for="inputCasa" class="form-label"><b>Motivo de Pago(*)</b></label> 
                          <select class="form-control" id="MotivoGasto" onchange="motivo();">                       
                              <option value="Transporte">Transporte</option>
                              <option value="Primeros auxilios">Primeros Auxilios</option>
                              <option value="Balon">Balon</option>
                              <option value="Ponchillos">Ponchillos</option>
                              <option value="Material de escritorio">Material de escritorio</option>
                              <option value="Otros">Otros</option>
                          </select>                       
                      </div>
                    </div>   
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Campeonato(*)</b></label>
                        <input type="text" class="form-control shadow-lg" id ="nombreCampeonato2" disabled>                     
                    </div> 
                    <div class="col-md-12" id="grupoOtros">
                      <br>
                        <label for="inputName" class="form-label"><b>Otros(*)</b></label>
                        <input type="text" class="form-control shadow-lg" id ="otros" placeholder="Ingresar motivo otros">                               
                    </div> 
                    <div class="col-md-6" id="grupoOtros">
                      <br>
                        <label for="inputName" class="form-label"><b>cantidad(*)</b></label>
                        <input type="text" class="form-control shadow-lg" id ="cantidad" placeholder="Ingresar cantidad">                 
                    </div> 
                    <div class="col-md-6" id="grupoOtros">
                      <br>
                        <label for="inputName" class="form-label"><b>Precio Unitario(*)</b></label>
                        <input type="text" class="form-control shadow-lg" id ="precio" placeholder="Ingresar precio unitario">                    
                    </div> 
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Total(*)</b></label>
                        <input type="text" class="form-control shadow-lg" id ="totalGasto" placeholder="Ingresar monto del pago">                
                    </div> 
                    <div class="col-md-6">
                        <br>
                            <label for="inputName" class="form-label"><b>Fecha Pago(*)</b></label>
                            <input type="date" class="form-control shadow-lg" id ="fechaGasto">                         
                    </div>          
                    
                    <div class="col-md-6">               
                        <input type="hidden" class="form-control" id ="idCampeonato2">                                          
                    </div> 
                </form>
                <br><div id="cargando_add"></div>
            </div>
            <div class="modal-footer col-md-12">
                <button type='button' class='btn btn-primary' id="btn_add" onclick='RegistrarGasto()'>Registrar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->

  
      <!-- Modal registrar pago de chancha y arbitraje --> 
    <div class="modal fade" id="ModalRegistrarPagoCanchaArbitraje">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloDirectiva"><i class="nav-icon fas fa-cash-register"></i> Registro Nuevo Pago</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarLote" class="row g-3">                                                     
                    <div class="col-md-6" id="Equipos">
                      <div class="form-group">
                      <br>
                          <label for="inputCasa" class="form-label"><b>Motivo de Pago(*)</b></label> 
                          <select class="form-control" id="MotivoPago">                       
                              <option value="Cancha">Pagar Cancha</option>
                              <option value="Arbitraje">Pagar Arbitraje</option>
                          </select>                       
                      </div>
                    </div>  
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Campeonato(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-trophy"></i></span>
                            </div>
                            <input type="text" class="form-control" id ="nombreCampeonato" disabled>                          
                        </div>                 
                    </div>  
                    <div class="col-md-6">
                      <br>
                        <label for="inputName" class="form-label"><b>Total(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" class="form-control" id ="totalPago" placeholder="Ingresar monto del pago">                          
                        </div>                 
                    </div> 
                    <div class="col-md-6">
                        <br>
                            <label for="inputName" class="form-label"><b>Fecha Pago(*)</b></label>
                            <div class="input-group">                 
                                <input type="date" class="form-control" id ="fechaPago">                          
                            </div>                 
                    </div>          
                   
                    <div class="col-md-6">               
                        <input type="hidden" class="form-control" id ="idCampeonato">                                          
                    </div> 
                </form>
               <br> <div id="cargando_add2"></div>
            </div>
            <div class="modal-footer col-md-12">
                <button type='button' class='btn btn-primary' id="btn_add2" onclick='RegistrarPago()'>Registrar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
      
  <script>


        function CargarDatos(){

          $("#botonDirectivaActual").hide();
          CargarCampeonato();
          ListaPagos();
          ListarGastos();

          TotalPagoCancha();
          TotalPagoArbitraje();
          TotalGastosInternos();
          Totales();
        }

        function Filtrar(){

          var idCampeonato = $("#filCampeonato").val();

          FiltrarGastosInternos();
          FiltrarPagosCancha();
          $("#botonDirectivaActual").show();
          TotalPagoCancha(idCampeonato);
          TotalPagoArbitraje(idCampeonato);
          TotalGastosInternos(idCampeonato);
          Totales(idCampeonato);

        
        }

        function TotalPagoCancha(idCampeonato){
        
            $.ajax({
            url: 'clases/Cl_Gasto.php?op=TotalPagoCancha',
            type: 'POST', 
            data:{
              idCampeonato: idCampeonato
            },
            success: function(data) {
                    if(data == "error"){                       
                        Swal.fire("Oops..!", "Error al cargar el total de pagos de la cancha", "warning");    
                    }
                    else {
                      if(data == "" || data == null){
                        $("#TotalGastosCancha").html("<h4>Bs. 0</h4>"); 
                      }
                      else{
                        data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                        data = data.split('').reverse().join('').replace(/^[\,]/,'');
                        $("#TotalGastosCancha").html("<h4>Bs. "+data+"</h4>"); 
                      }
                                              
                    }                 
                }          
            })    
        }

        function TotalPagoArbitraje(idCampeonato){
        
            $.ajax({
            url: 'clases/Cl_Gasto.php?op=TotalPagoArbitraje',
            type: 'POST', 
            data:{
              idCampeonato: idCampeonato
            },
            success: function(data) {
                    if(data == "error"){                       
                        Swal.fire("Oops..!", "Error al cargar el total de al arbtiro", "warning");    
                    }
                    else {
                      if(data == "" || data == null){
                        $("#TotalGastosArbitraje").html("<h4>Bs. 0</h4>"); 
                      }
                      else{
                        data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                        data = data.split('').reverse().join('').replace(/^[\,]/,'');
                        $("#TotalGastosArbitraje").html("<h4>Bs. "+data+"</h4>"); 
                      }
                                              
                    }                 
                }          
            })    
        }

        function TotalGastosInternos(idCampeonato){

             $.ajax({
             url: 'clases/Cl_Gasto.php?op=TotalGastosInternos',
             type: 'POST', 
             data:{
              idCampeonato: idCampeonato
            },
             success: function(data) {
                     if(data == "error"){                       
                       Swal.fire("Oops..!", "Error al cargar el total de gastos internos de la directiva", "warning");    
                     }
                     else { 
                      if(data == "" || data == null){
                        $("#TotalGastosInternos").html("<h4>Bs. 0</h4>"); 
                      }
                      else{
                        data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                        data = data.split('').reverse().join('').replace(/^[\,]/,'');
                        $("#TotalGastosInternos").html("<h4>Bs. "+data+"</h4>"); 
                      }
                                                
                    }                 
                 }          
             })    
        }


        function Totales(idCampeonato){
        
            $.ajax({
            url: 'clases/Cl_Gasto.php?op=TotalGastado',
            type: 'POST', 
            data:{
              idCampeonato: idCampeonato
            },
            success: function(data) {
                    if(data == "error"){                       
                        Swal.fire("Oops..!", "Error al calcular el total gastado", "warning");    
                    }
                    else {
                      if(data == "" || data == null){
                        $("#Totales").html("<h4>Bs. 0</h4>"); 
                      }
                      else{
                        data = data.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,');
                        data = data.split('').reverse().join('').replace(/^[\,]/,'');
                        $("#Totales").html("<h4>Bs. "+data+".00</h4>"); 
                      }
                                        
                    }                 
                }          
            })    
        }

       

        function motivo(){
            var motivo = $("#MotivoGasto").val();

            if(motivo == "Otros"){
                $("#grupoOtros").show();
            }
            else{
                $("#grupoOtros").hide();
            }
        }

        function CargarCampeonato(){
            $.ajax({
                url: 'clases/Cl_Partido.php?op=UltimoTorneo',
                type: 'POST',
                success: function(data) {
                if(data == "error"){
                    Swal.fire("Error..!", "Ha ocurrido un error al obtener el nombre del torneo actual", "error");      
                }
                else{
                    var resp= $.parseJSON(data);
                    $("#idCampeonato").val(resp.id); 
                    $("#nombreCampeonato").val(resp.nombre); 
                    $("#idCampeonato2").val(resp.id); 
                    $("#nombreCampeonato2").val(resp.nombre);
                }              
                }            
            })   
        }
       
        function ModalRegistrarPagoCanchaArbitraje() { 
           
            let hoy = new Date();
            document.getElementById("fechaPago").value = hoy.toJSON().slice(0,10); 
          $('#ModalRegistrarPagoCanchaArbitraje').modal('show');
        }

        function ModalRegistrarGastosInternos(id) {    

            $("#grupoOtros").hide();        
            let hoy = new Date();
            document.getElementById("fechaGasto").value = hoy.toJSON().slice(0,10); 
          $('#ModalRegistrarGastosInternos').modal('show');
        }

      /*
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
                 }else{
                   if(vs== 1){
                        Swal.fire('Exito..!','Eliminado correctamente.', 'success');
                        location.reload();
                   }           
                 }                  
             }
         })         
     }*/

     function ListaPagos(){
        
      $("#contenerdor_tabla3").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Pagos Partidos ...</center></div>');
        $.ajax({
            url: 'clases/Cl_Gasto.php?op=ListarPagos',
            type: 'POST', 
            success: function(data) {
                $("#contenerdor_tabla3").html('');
                $('#example3').DataTable().destroy();
                $("#contenerdor_tabla3").html(data);   
                //$("#botonDirectivaActual").hide(); 
                $("#example3").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }          
        })         
    }


     function ListarGastos(){
      $("#contenerdor_tabla2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Gastos Internos ...</center></div>');
        $.ajax({
            url: 'clases/Cl_Gasto.php?op=ListarGastos',
            type: 'POST', 
            success: function(data) {
                $("#contenerdor_tabla2").html('');
                $('#example2').DataTable().destroy();
                $("#contenerdor_tabla2").html(data);  
                $("#example2").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "language": lenguaje_español
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');  
            }          
        })         
    }
    

    function FiltrarGastosInternos(){
        
        
      var idCampeonato = $("#filCampeonato").val();

      if(idCampeonato == null){
        Swal.fire('Aviso..!','Debe seleccionar una opcion de filtro','warning');
        return false;
      }

       
        $("#contenerdor_tabla2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Gastos Internos ...</center></div>');
 
        $.ajax({
            url: 'clases/Cl_Gasto.php?op=FiltrarGastosInternos',
            type: 'POST', 
            data:{
               
               idCampeonato: idCampeonato
             },
            success: function(data) {
                $("#contenerdor_tabla2").html('');
                $('#example2').DataTable().destroy();
                $("#contenerdor_tabla2").html(data);  
                $("#botonDirectivaActual").show();
            }
        })         
    }

    function FiltrarPagosCancha(){
        
        
        var idCampeonato = $("#filCampeonato").val();
  
        if(idCampeonato == null){
          Swal.fire('Aviso..!','Debe seleccionar una opcion de filtro','warning');
          return false;
        }
  
        $("#contenerdor_tabla3").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Pagos Partidos ...</center></div>');
          $.ajax({
              url: 'clases/Cl_Gasto.php?op=FiltrarPagosCancha',
              type: 'POST', 
              data:{
               
                idCampeonato: idCampeonato
              },
              success: function(data) {
                  $("#contenerdor_tabla3").html('');
                  $('#example3').DataTable().destroy();
                  $("#contenerdor_tabla3").html(data);  
                  $("#botonDirectivaActual").show();
              }          
          })         
      }


      function RegistrarGasto() {
         
         var MotivoGasto = $('#MotivoGasto').val();
         var otros = $('#otros').val();
         var totalGasto = $('#totalGasto').val();
         var fechaGasto = $('#fechaGasto').val();
         var idCampeonato2 = $('#idCampeonato2').val();
         var cantidad = $('#cantidad').val();
         var precio = $('#precio').val();
        
         if(totalGasto == "" || fechaGasto == "" || cantidad == "" || precio == ""){
           Swal.fire("Campos Vacios..!", "Debe ingresar todos los campos requeridos", "warning");
           return false;
         }

         if(MotivoGasto == "Otros" && otros == ""){
           Swal.fire("Campos Vacios..!", "Debe ingresar el motivo gastos otros ", "warning");
           return false;
         }
 
            $("#btn_add").prop("disabled", true);
            $("#cargando_add").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');

         
              $.ajax({
              url: 'clases/Cl_Gasto.php?op=RegistrarGasto',
              type: 'POST',
              data: {
                MotivoGasto: MotivoGasto,
                otros: otros,
                totalGasto: totalGasto,
                fechaGasto: fechaGasto,
                idCampeonato2: idCampeonato2,
                cantidad: cantidad,
                precio: precio
              }, 
              success: function(vs) { 
                  $("#cargando_add").html('');
                  $("#btn_add").prop("disabled", false); 
                  if (vs == 2) {                   
                     Swal.fire("Error..!", "Ha ocurrido un error al registrar la direciva", "error");                     
                  }else{
                     if (vs == 1) {
                     Swal.fire('Exito!', 'Gasto interno registrado correctamente',  'success');
                     location.reload();
                     }
                  }                  
              },
              error: function(vs){
                $("#cargando_add").html('');
                $("#btn_add").prop("disabled", false); 
              }
          })         
      }


      function RegistrarPago() {
         
        var MotivoPago = $('#MotivoPago').val();
        var totalPago = $('#totalPago').val();
        var fechaPago = $('#fechaPago').val();
        var idCampeonato = $('#idCampeonato').val();

        if(totalPago == ""){
           Swal.fire("Campos Vacios..!", "Debe ingresar todos los campos requeridos", "warning");
           return false;
         }

         $("#btn_add2").prop("disabled", true);
          $("#cargando_add2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
 
           
          $.ajax({
              url: 'clases/Cl_Gasto.php?op=RegistrarPago',
              type: 'POST',
              data: {
                MotivoPago: MotivoPago,
                totalPago: totalPago,
                fechaPago: fechaPago,
                idCampeonato: idCampeonato
              }, 
              success: function(vs) {   
                $("#cargando_add2").html('');
                  $("#btn_add2").prop("disabled", false); 
                 if (vs == 2) {                   
                    Swal.fire("Error..!", "Ha ocurrido un error al registrar el pago", "error");                     
                 }else{
                    if (vs == 1) {
                    Swal.fire('Exito!', 'Pago registrado correctamente',  'success');
                    location.reload();
                    }
                   
                 }                  
              },
              error:function(vs){
                $("#cargando_add2").html('');
                    $("#btn_add2").prop("disabled", false); 
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
