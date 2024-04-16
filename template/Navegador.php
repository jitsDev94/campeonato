<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">  
        <!-- <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true">
            <?php 
               /* $consultartipo= "SELECT e.nombre,t.tipoUsuario FROM usuario as u LEFT JOIN empleado as e on e.id = u.idEmpleado LEFT JOIN tipousuario as t on t.id = u.idTipoUsuario WHERE u.usuario = '$usuario'";
                $resultados = mysqli_query($conectar, $consultartipo);
                $rows = $resultados->fetch_assoc();
                $rol = $rows['tipoUsuario']; */
            ?>
                <b>Cargo:</b> Secretario
            </a>
        </li> -->
       <!-- <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>-->

        
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" >
                <?php echo $usuario; ?> <i class="fas fa-user fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style=" width: 320px;" aria-labelledby="navbarDropdown">
                <div class="card-group">
                    <div class="card  w-75">
                        <img src="../img/desconocido.jpg" class="card-img-top" alt="...">
                        <div class="card-body text-center card-lg">
                            <div class="row">
                                <div class="col-12">
                                    <b><h5 class="card-text"><?php if($idRol == 1 || $idRol == 2) {echo 'Miembro Directiva';} else{ echo 'Delegado';}?></h5></b>
                                </div>  
                            </div>          
                            <div class="row">
                                <div class="col-12">
                                    <p class="card-text"><?php if($idRol == 1 || $idRol == 2){ echo $_SESSION['nombreRol'];} else{echo $nombreEquipoDelegado;} ?></p>                                                          
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="card-footer">
                            <div class="row pt-3 pb-3">
                                <div class="col-12">                      
                                    <p><button type="button" class="btn btn-success btn-sm" onclick="ModalCambiarContrasena()">Cambiar Contraseña</button> &nbsp; <a href="logout.php" type="button" class="btn btn-danger btn-sm">Cerrar Sesión <i class="fas fa-sign-out-alt"></i></a></p>
                                </div>
                            </div>
                        </div>                    
                    </div>
                </div>
             
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

   <!-- Modal modificar contrasena -->
   <div class="modal fade" id="ModalModificarContrasena">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
           
          <h5 class="modal-title"> Modificar Contraseña</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" id="formEditarLote" class="row g-3">
            <div class="col-md-12">
                <div class="callout callout-danger shadow-lg">                
                  <p>Iniciara sesión nuevamente una vez cambie la contraseña.</p>
                </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Contraseña Actual (*)</label>
                <input type="password" class="form-control" id="actual">
              </div>
            </div><br>  
            <div class="col-md-12">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Contraseña Nueva(*)</label>
                <input type="password" class="form-control" id="nueva">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer col-md-12">
          <button type="button" class="btn btn-primary" onclick="actualizarContrasena()">Actualizar</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <script>

      function ModalCambiarContrasena(){
          $("#ModalModificarContrasena").modal("show");
      }

      function actualizarContrasena(){

         var contraActual = $("#actual").val();
         var contraNueva = $("#nueva").val();

          if(contraActual == contraNueva){
            Swal.fire("Oops..!","La nueva contraseña no puede ser la misma que la contraseña actual","warning");
            return false;
          }

          if(contraActual == "" || contraNueva == ""){
            Swal.fire("Campos Vacios..!","Debe ingresar todos los campos requeridos","warning");
            return false;
          }
        

        $.ajax({
            url: '../clases/Cl_Index.php?op=ActualizarContra',
            type: 'POST',
            data: {
                contraNueva:contraNueva
            },
            success: function(vs) {
                if (vs == 'error') {
                Swal.fire("Error..!", "ha ocurrido un error al actualizar la contraseña, intentar mas tarde", "error");
                } else {
                    Swal.fire("Exito..!", "Contraseña actualizada correctamente", "success");
                    location.href = "../views/login.php";
                }
            }
        })
      }

  </script>