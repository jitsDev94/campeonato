<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio de Sesion</title>
  <link rel="icon" type="image/jpg" href="../img/image.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <!-- <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <style>
   .tarjeta{
    opacity: 0.6;
   }
 </style>
</head>
<body class="hold-transition login-page"  style="background: url(../img/portada.png);
    background-attachment: fixed;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    ">
<div class="login-box">
  <div class="image text-center" >
    <img src="../img/image.png" class="img-circle elevation-2" alt="User Image" width="80px;">
  </div>
  <div class="login-logo">
    <b>Campeonato</b> 
  </div>
  <!-- /.login-logo -->
  <div class="card tarjeta">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicio de Sesión</p>

      <form method="post">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="usuario" placeholder="Usuario">
            <label for="floatingInput">Usuario</label>         
          </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="contra" placeholder="Contraseña">
          <label for="floatingInput">Contraseña</label>
        </div>
        <div class="row">       
          <div class="col-12">
            <button type="button" class="btn btn-primary btn-block" onclick="autenticacion();">Iniciar Sesion</button>
          </div>
        </div>
      </form>
    <!--<br>
       <p class="mb-0">
        <a href="views/Registrarse.php" class="text-center">Registrar Nueva Cuenta</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->



    <!-- Modal para cargar--> 
    <div class="modal fade" id="ModalCargarPagina">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="text-center">
                <div class="spinner-border" role="status">
                  <span class="visually-hidden"></span>
                </div>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

<script>



   function autenticacion(){
   
  
        var user = $("#usuario").val();
        var contra = $("#contra").val();

       if(user == "" || contra == ""){
         swal.fire('Oops!!','Debe ingresar el usuario y/o contraseña','warning');
         return false;
       }

        $.ajax({
        url: '../clases/Cl_IniciarSesion.php',
        type: 'POST',
        data: {
            user: user,
            contra: contra
            },     
            success: function(data) {
                if(data == 'ok'){ 
                    $("#ModalCargarPagina").modal("show");               
                    window.location.href = "index.php";
                }
                else{
                    if(data == 'deshabilitado'){                     
                      swal.fire('Oops..!','Su usuario ha sido deshabilitado, favor hablar con un administrador','error');                        
                    }
                    else{
                      swal.fire('Oops..!','Usuario y/o contraseña incorrecto, favor intente nuevamente','error');  
                     
                    }   
                    event.preventDefault();                              
                }
            }            
        })         
    }

</script>


<!-- SweetAlert2 -->
<script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootratstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

</body>
</html>
