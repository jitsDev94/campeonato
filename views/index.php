<?php

include '../conexion/conexion.php';
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
              <h1 class="m-0 pb-3">Noticias y Anuncios</h1>
            </div>
            <?php if ($idRol == 1) { ?>
              <div class="col-12 col-sm-2">
                <button type="button" class="btn btn-primary btn-block shadow__btn" onclick="ModalRegistrarAnuncio()"><i class="fas fa-plus-circle"></i> Nuevo anuncio</button>
              </div>
            <?php } ?>
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>

      <?php
      
       if ($idRol == 1) {
       
       ?>
           <div class="alert alert-primary alert-dismissible fade show  ml-3 mr-3" role="alert">
            
             Puedes publicar nuevamente tus anuncios vencidos desde el <a href="Historial_Anuncios.php" class="alert-link">historial de anuncios.</a>
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>
       <?php }

      $Consultar = "SELECT * FROM configuracionCobros where precio is null";
      $resultado = mysqli_query($conectar, $Consultar);
      $rowcount = mysqli_num_rows($resultado);
      if ($idRol == 1) {
        if ($rowcount > 0) {
      ?>
          <div class="alert alert-danger alert-dismissible fade show ml-3 mr-3" role="alert">
            <h4 class="alert-heading">Confirgurar Cobros!</h4>
            <p>Al parecer aun no terminaste de configurar el precio de los cobros que se realizan en el torneo.</p>
            <hr>
            <p class="mb-0"><a href="configurar_Cobros.php"  class="alert-link">Configurarlo ahora</a></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php }
      } 
        $actualizar = "UPDATE anuncios SET estado = 'Deshabilitado' where fechaLimite < sysdate()";
        $result = mysqli_query($conectar, $actualizar);

        $registrar = "SELECT id,titulo,detalle,fechaPublicacion FROM anuncios where fechaLimite >= sysdate() and estado = 'Habilitado'";
        $resultado1 = mysqli_query($conectar, $registrar);
        $rowcount1 = mysqli_num_rows($resultado1);
        if($rowcount1 > 0)
        {
          ?>
          <div class="row ml-1 mr-1">
          <?php 
          while ($listado = mysqli_fetch_array($resultado1)) {      
      ?>
      
        <section class="col-lg-4 col-md-4">
          <div class="card text-center shadow-lg">
            <div class="card-header">
              <b><?php echo $listado['titulo']; ?>   </b>
            </div>
            <div class="card-body">
              <p class="card-text"><?php echo $listado['detalle']; ?>.</p>
              <?php if ($idRol == 1) { ?>
                <button type="button" class="btn btn-primary" onclick="ModalEditarAnuncio(<?php echo $listado['id']; ?>)">Editar</button>
                <button type="button" class="btn btn-danger" onclick="ConfirmarQuitarAnuncio(<?php echo $listado['id']; ?>)">Quitar</button>             
            <?php } ?>
            </div>
            <div class="card-footer text-muted">
              Fecha Publicación: <?php echo $listado['fechaPublicacion']; ?>   
            </div>
          </div>
        </section>
      <?php 
      } ?>    
      </div>
      <?php 
      }
      else{
      }?>
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

  <!-- Modal nueva anuncio -->
  <div class="modal fade" id="ModalRegistrarAnuncio">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="tituloanuncio"> Nuevo Anuncio</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" id="formEditarLote" class="row g-3">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Titulo</label>
                <input type="email" class="form-control" id="titulo" placeholder="Ingresar el titulo">
              </div>
            </div><br>           
            <div class="col-md-6">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Limite Fecha</label>
                <input type="date" class="form-control" id="fechaLimite">
              </div>
            </div><br>
            <div class="col-md-12">
              <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Anuncio</label>
                <textarea class="form-control" id="detalle" rows="4" placeholder="Ingresar el detalle del anuncio"></textarea>
              </div>
            </div> <br> 
          </form>
        </div>
        <div class="modal-footer col-md-12">
          <div id="botonAccion"></div>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <script>

    function QuitarAnuncio(id){
      $.ajax({
        url: '../clases/Cl_Historial_Anuncios.php?op=QuitarAnuncio',
        type: 'POST',
        data: {
          id: id
        },
        success: function(vs) {
          if (vs == 2) {
            Swal.fire("Error..!", "ha ocurrido un error al crear el anuncio", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito..!', 'Anuncio quitado correctamente.', 'success');
              location.reload();
            }
          }
        }
      })
    }

    function ModalRegistrarAnuncio() {
      $("#titulo").val("");
      $("#detalle").val("");
      $("#fechaLimite").val("");
      $("#tituloanuncio").html("Nuevo Anuncio");
      $("#botonAccion").html("<button type=button' class='btn btn-primary' onclick='RegistrarAnuncio()'>Registrar</button>");
      $("#ModalRegistrarAnuncio").modal("show");
    }

    function ModalEditarAnuncio(id) {

      
      $.ajax({
        url: '../clases/Cl_Historial_Anuncios.php?op=DatosAnuncio',
        type: 'POST',
        data: {
         id:id
        },
        success: function(vs) {
          if (vs == 'error') {
            Swal.fire("Error..!", "ha ocurrido un error al crear el anuncio", "error");
          } else {
          
              var resp= $.parseJSON(vs);
          
              $("#titulo").val(resp.titulo); 
              $("#detalle").val(resp.detalle); 
              $("#fechaLimite").val(resp.fechaLimite); 
              $("#tituloanuncio").html("Editar Anuncio");
              $("#botonAccion").html("<button type=button' class='btn btn-primary shadow__btn' onclick='EditarAnuncio("+id+")'>Editar</button>");
              $("#ModalRegistrarAnuncio").modal("show");
            
          }
        }
      })
     
    }

    function EditarAnuncio(id){

      var titulo = $("#titulo").val();
      var detalle = $("#detalle").val();
      var fechaLimite = $("#fechaLimite").val();

      $.ajax({
        url: '../clases/Cl_Historial_Anuncios.php?op=EditarAnuncio',
        type: 'POST',
        data: {
         id:id,
         titulo: titulo,
         detalle: detalle,
         fechaLimite: fechaLimite,
        },
        success: function(vs) {
          if (vs == 2) {
            Swal.fire("Error..!", "ha ocurrido un error al actualizar el anuncio, intentar mas tarde", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito..!', 'Anuncio actualizado correctamente.', 'success');
              location.reload();
            }
          }
        }
      })
    }

    function ConfirmarQuitarAnuncio(id) {
      Swal.fire({
        title: 'Esta Seguro?',
        text: "Al quitarlo ya no sera visible para los delegados!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Quitar!'
      }).then((result) => {
        if (result.isConfirmed) {
          QuitarAnuncio(id);
        }
      })
    }


    function RegistrarAnuncio() {

      var titulo = $("#titulo").val();
      var detalle = $("#detalle").val();
      var fechaLimite = $("#fechaLimite").val();

      $.ajax({
        url: '../clases/Cl_Historial_Anuncios.php?op=RegistrarAnuncio',
        type: 'POST',
        data: {
          titulo: titulo,
          detalle: detalle,
          fechaLimite: fechaLimite
        },
        success: function(vs) {
          if (vs == 2) {
            Swal.fire("Error..!", "ha ocurrido un error al crear el anuncio", "error");
          } else {
            if (vs == 1) {
              Swal.fire('Exito..!', 'Anuncio creado correctamente.', 'success');
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

</body>

</html>