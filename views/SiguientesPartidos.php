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
          <div class="col-sm-10">
            <h1 class="m-0">Partidos de la Siguiente Fecha</h1>
          </div>             
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="col-lg-12 col-md-12">
        <div class="row"> 
        <section class="col-lg-6 col-md-6">
            <div class="card info-box shadow-lg">            
              <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-md-6 col-sm-6">
                        <label for="">Fecha Partidos</label>
                        <input type="date" class="form-control shadow-lg" id="fecha" readonly>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label for="">Cancha</label>
                        <input type="text" class="form-control shadow-lg" value="Cancha 1" readonly>
                    </div>
                </div>
                <div id="contenerdor_tabla" class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>                     
                      <th>Equipo Local</th>
                      <th>Equipo Visitante</th>
                      <th>Hora</th>                     
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


        <section class="col-lg-6 col-md-6">
            <div class="card info-box shadow-lg">            
              <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-md-6 col-sm-6">
                        <label for="">Fecha Partidos</label>
                        <input type="date" class="form-control shadow-lg" id="fecha2" readonly>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label for="">Cancha</label>
                        <input type="text" class="form-control shadow-lg" value="Cancha 2" readonly>
                    </div>
                </div>
                <div id="contenerdor_tabla2" class="table-responsive">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>                     
                      <th>Equipo Local</th>
                      <th>Equipo Visitante</th>
                      <th>Hora</th>                     
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

        </div>
        
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
  <script>


     function ListarPartidos(){
        
      $("#contenerdor_tabla").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Partidos ...</center></div>');
      $("#contenerdor_tabla2").html('<div class="loading"> <center><i class="fa fa-spinner fa-pulse fa-5x" style="color:#3c8dbc"></i><br/>Cargando Partidos ...</center></div>');

      //partidos cancha 1
        $.ajax({
            url: '../clases/Cl_Programa_Partidos.php?op=ListarPartidos',
            type: 'POST', 
            data: {
                cancha : "Cancha 1"
            },
            success: function(data) {
                $("#contenerdor_tabla").html('');
                $('#example1').DataTable().destroy();
                $("#contenerdor_tabla").html(data);
                // $("#example1").DataTable({
                //     "responsive": true, "lengthChange": false, "autoWidth": false, "search": false,
                //     "language": lenguaje_español
                // });            
            }          
        })  
        
        //partidos cancha 2
          $.ajax({
            url: '../clases/Cl_Programa_Partidos.php?op=ListarPartidos',
            type: 'POST',
            data: {
                cancha : "Cancha 2"
            },
            success: function(data) {
                $("#contenerdor_tabla2").html('');
                $('#example2').DataTable().destroy();
                $("#contenerdor_tabla2").html(data);
                // $("#example2").DataTable({
                //     "responsive": true, "lengthChange": false, "autoWidth": false, "search": false,
                //     "language": lenguaje_español
                // });            
            }          
        })  
        

        $.ajax({
            url: '../clases/Cl_Programa_Partidos.php?op=datosProgramacionPartidos',
            type: 'POST',
            success: function(data) {
              if(data != ""){
                var resp= $.parseJSON(data);
                             
                    $("#fecha").val(resp.fecha); 
                    $("#fecha2").val(resp.fecha);   
                // Swal.fire("Error..!", "Ha ocurrido un error al obtener numero del ultimo partido", "error");      
              }
              else{                
                         
              }              
            }            
          })   
    }

   
      </script>


      <?php
      require "../template/piePagina.php";
      ?>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, 
      "lengthChange": false,
       "autoWidth": false,
      "language": lenguaje_español
    }) ;
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
