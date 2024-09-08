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

// if($parametro->verificarPermisos($_SESSION['idUsuario'],'11,43,42') == 0){
//   echo "Su usuario no tiene permisos para entrar a esta pagina";
//   exit();
// }
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
    .card{
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
            <h1 class="m-0">Gastos</h1>
          </div>
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
                                                  <?php 
                                                      $parametro->DropDownListarTorneos();
                                                  ?>
                                                  
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-top:32px;">
                                              <button type="button" class="btn btn-primary" id="btn_add1" onclick="Filtrar();"><i class="fas fa-filter"></i>  Filtrar</button>
                                           
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

      <?php
      require "../template/footer.php";
      ?>
    
    </div>
    <!-- ./wrapper -->




<!--        MODAL         -->

        <!-- Modal registro de gastos internos --> 
    <div class="modal fade" id="ModalRegistrarGastosInternos">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloDirectiva"> Registro Nuevo Gasto</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarLote" class="row g-3">                                                     
                    <div class="col-md-6">
                      <div class="form-group">
                      
                          <label for="inputCasa" class="form-label"><b>Motivo de Pago(*)</b></label> 
                          <select class="form-control shadow-lg" id="MotivoGasto" onchange="motivo();">                       
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
                        <label for="inputName" class="form-label"><b>Fecha Pago(*)</b></label>
                        <input type="date" class="form-control shadow-lg" id ="fechaGasto" value='<?php echo date('Y-m-d'); ?>'>                         
                    </div>    

                    <div class="col-md-6" style='display:none;'>                    
                        <label for="inputName" class="form-label"><b>Campeonato(*)</b></label>
                        <input type="text" class="form-control shadow-lg" id ="nombreCampeonato2" disabled>                     
                    </div> 

                    <div class="col-md-12" id="grupoOtros" style='display:none;'>                      
                        <label for="inputName" class="form-label"><b>Otros(*)</b></label>
                        <input type="text" class="form-control shadow-lg" id ="otros" placeholder="Ingresar motivo otros">                               
                    </div> 

                    <div class="col-md-6" id="grupoOtros">                      
                        <label for="inputName" class="form-label"><b>cantidad(*)</b></label>
                        <input type="number" class="form-control shadow-lg" id ="cantidad" placeholder="Ingresar cantidad">                 
                    </div> 

                    <div class="col-md-6" id="grupoOtros">                      
                        <label for="inputName" class="form-label"><b>Precio Unitario(*)</b></label>
                        <input type="number" class="form-control shadow-lg" id ="precio" placeholder="Ingresar precio unitario">                    
                    </div> 

                    <div class="col-md-6">                      
                        <label for="inputName" class="form-label"><b>Total(*)</b></label>
                        <input type="number" class="form-control shadow-lg" id ="totalGasto" placeholder="Ingresar monto del pago">                
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
              <h4 class="modal-title" id="tituloDirectiva">  Registro Nuevo Pago</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditarLote" class="row g-3">                                                     
                    <div class="col-md-6" id="Equipos">                    
                          <label for="inputCasa" class="form-label"><b>Motivo de Pago(*)</b></label> 
                          <select class="form-control" id="MotivoPago">                       
                              <option value="Cancha">Pagar Cancha</option>
                              <option value="Arbitraje">Pagar Arbitraje</option>
                          </select>                                           
                    </div>  
                    <div class="col-md-6" style='display:none;'>                     
                        <label for="inputName" class="form-label"><b>Campeonato(*)</b></label>                      
                        <input type="text" class="form-control" id ="nombreCampeonato" disabled>                                                                  
                    </div>  
                    <div class="col-md-6">
                    
                        <label for="inputName" class="form-label"><b>Total(*)</b></label>                       
                        <input type="number" class="form-control" id ="totalPago" placeholder="Ingresar monto del pago">                                                                  
                    </div> 
                    <div class="col-md-6">                       
                      <label for="inputName" class="form-label"><b>Fecha Pago(*)</b></label>                                          
                      <input type="date" class="form-control" id ="fechaPago" value='<?php echo date('Y-m-d'); ?>'>                                                                      
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


        function Filtrar(){

          var idCampeonato = $("#filCampeonato").val();

          FiltrarGastosInternos();
          ListarPagos();
      
       
          Totales(idCampeonato);

        
        }

        function Totales(idCampeonato=''){
        
          $.ajax({
              url: '../clases/Cl_Gasto.php?op=TotalGastado',
              type: 'POST', 
              dataType: 'json',
              data:{
                idCampeonato: idCampeonato
              },
              success: function(data) {
                                
                $("#TotalGastosCancha").html("<h4>Bs. "+data['totalGastoCancha']+"</h4>");               
                $("#TotalGastosArbitraje").html("<h4>Bs. "+data['totalGastoArbitraje']+"</h4>");               
                $("#TotalGastosInternos").html("<h4>Bs. "+data['totalGastosInternos']+"</h4>");               
                $("#Totales").html("<h4>Bs. "+data['totalGastoGlobal']+"</h4>"); 
                            
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

     
       
        function ModalRegistrarPagoCanchaArbitraje() {                      
          $('#ModalRegistrarPagoCanchaArbitraje').modal('show');
        }

        function ModalRegistrarGastosInternos() {    

          //  $("#grupoOtros").hide();        
            $("#otros").val('');
          $('#ModalRegistrarGastosInternos').modal('show');
        }

    function ListarPagos(){
        
      var idCampeonato = $("#filCampeonato").val();

     
      $("#contenerdor_tabla3").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Pagos Partidos ...</center></div>');
        $.ajax({
            url: '../clases/Cl_Gasto.php?op=ListarPagos',
            type: 'POST', 
            data:{
               
               idCampeonato: idCampeonato
             },
            success: function(data) {
                $("#contenerdor_tabla3").html('');
                $('#example3').DataTable().destroy();
                $("#contenerdor_tabla3").html(data);                  
                $("#example3").DataTable({
                        "responsive": true,
                        "lengthChange": false, 
                        "autoWidth": false,
                        "language": lenguaje_español
                    });
            }          
        })         
    }


    function FiltrarGastosInternos(){
        
        
      var idCampeonato = $("#filCampeonato").val();
 
        $("#contenerdor_tabla2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Gastos Internos ...</center></div>');
 
        $.ajax({
            url: '../clases/Cl_Gasto.php?op=FiltrarGastosInternos',
            type: 'POST', 
            data:{
               
               idCampeonato: idCampeonato
             },
            success: function(data) {
                $("#contenerdor_tabla2").html('');
                $('#example2').DataTable().destroy();
                $("#contenerdor_tabla2").html(data);  
                $("#example2").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "language": lenguaje_español
                });  
            }
        })         
    }

   


      function RegistrarGasto() {
         
         var MotivoGasto = $('#MotivoGasto').val();
         var otros = $('#otros').val();
         var totalGasto = $('#totalGasto').val();
         var fechaGasto = $('#fechaGasto').val();
       
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
              url: '../clases/Cl_Gasto.php?op=RegistrarGasto',
              type: 'POST',
              data: {
                MotivoGasto: MotivoGasto,
                otros: otros,
                totalGasto: totalGasto,
                fechaGasto: fechaGasto,
               
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
       
        if(totalPago == ""){
           Swal.fire("Campos Vacios..!", "Debe ingresar todos los campos requeridos", "warning");
           return false;
         }

         $("#btn_add2").prop("disabled", true);
          $("#cargando_add2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Procesando ...</center></div>');
 
           
          $.ajax({
              url: '../clases/Cl_Gasto.php?op=RegistrarPago',
              type: 'POST',
              data: {
                MotivoPago: MotivoPago,
                totalPago: totalPago,
                fechaPago: fechaPago                
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


<?php
      require "../template/piePagina.php";
      ?>
<script>

        $(function() {
           
          FiltrarGastosInternos();                  
          ListarPagos();               
          Totales();
        })

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
