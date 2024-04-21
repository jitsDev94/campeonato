   <?php 
   require_once '../conexion/parametros.php';

   $parametro = new parametros();

   $jugadores =  $parametro->TotalJugadores($idEquipoDelegado,$idRol);    
   $Equipos =  $parametro->totalEquipos();
   $inscritos = $parametro->totalInscritos(); 
   $totalMultas = $parametro->TotalMultas($idEquipoDelegado,$idRol);
   $Observaciones = $parametro->TotalObservaciones($idEquipoDelegado,$idRol);
   ?>
   <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../views/index.php" class="brand-link">
                <img src="../img/image.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Campeonato</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../img/logoEquipo.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="../views/index.php" class="d-block"><?php if($nombreEquipoDelegado == "" || $nombreEquipoDelegado == null){ echo 'Miembro Directiva';}else{ echo $nombreEquipoDelegado; }?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2 pb-6">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="../views/index.php" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                   Dashboard
                                   
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../views/SiguientesPartidos.php" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>
                                   Próximos Partidos                                   
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../views/jugadores.php" class="nav-link">
                              <i class="nav-icon fas fa-running"></i>                                  
                                <p>
                                    Jugadores        
                                    <span class="badge badge-success right"><?php echo $jugadores;?></span>                       
                                </p>
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a href="../views/transferencia.php" class="nav-link">
                              <i class="nav-icon fas fa-random"></i>
                                <p>
                                    Transferencias                               
                                </p>
                            </a>
                        </li>
                           
                        <li class="nav-item">
                            <a href="../views/equipos.php" class="nav-link">
                              <i class="nav-icon fas fa-users"></i>                                  
                                <p>
                                    Equipos
                                    <span class="badge badge-success right"><?php echo $Equipos;?></span>
                                </p>
                            </a>
                        </li>
                           
                        <li class="nav-item">
                            <a href="../views/inscripcion.php" class="nav-link">
                              <i class="nav-icon fas fa-file-signature"></i>                              
                                <p>
                                    Inscripción    
                                    <span class="badge badge-success right"><?php echo $inscritos;?></span>                                
                                </p>
                            </a>
                        </li>
                        <?php
                            if($parametro->verificarPermisos($_SESSION['idUsuario'],10) > 0){ ?>
                        <li class="nav-item">
                            <a href="../views/campeonato.php" class="nav-link">
                              <i class="nav-icon fas fa-trophy"></i>
                                <p>
                                    Torneos                               
                                </p>
                            </a>
                        </li>
                        <?php }
                            if($parametro->verificarPermisos($_SESSION['idUsuario'],9) > 0){ ?>
                        <li class="nav-item">
                            <a href="../views/Gastos.php" class="nav-link">
                              <i class="nav-icon fas fa-cash-register"></i>
                                <p>
                                    Gastos                               
                                </p>
                            </a>
                        </li>
                        <?php }?>

                        <li class="nav-item">
                            <a href="../views/Multas.php" class="nav-link">
                              <i class="nav-icon fas fa-list"></i>                              
                                <p>
                                    Multas   
                                    <span class="badge badge-success right"><?php echo $totalMultas;?></span>                             
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon fas fa-futbol"></i>
                                <p>
                                    Partido
                                    <i class="fas fa-angle-left right"></i>
                                    
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                            <?php if($parametro->verificarPermisos($_SESSION['idUsuario'],14) > 0){ ?>
                                <li class="nav-item">
                                    <a href="../views/programacionPartidos.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> Programar Partidos</p>
                                    </a>
                                </li>
                                <?php } if($parametro->verificarPermisos($_SESSION['idUsuario'],1) > 0){ ?>
                                <li class="nav-item">
                                    <a href="../views/partido.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>   Nuevo Partido</p>
                                    </a>
                                </li>
                                <?php }?>
                                <li class="nav-item">
                                    <a href="../views/DetallePartidos.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>   Detalle Partidos</p>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="../views/observaciones.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>                                      
                                        <p>   Observaciones
                                        <span class="badge badge-danger right"><?php echo $Observaciones;?></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/tarjetas.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <?php   
                                        // $condicion = "";
                                        // if($idRol == 3){
                                        //     $condicion = " and e.id = $idEquipoDelegado";
                                        // } 

                                        // $consulta = "SELECT COUNT(hp.id) as tarjetas FROM HechosPartido as hp
                                        // LEFT JOIN Partido as p on p.id = hp.idPartido
                                        // LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                                        // LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                                        // where hp.estado = 'pendiente' and hp.idHecho != 1 and c.estado = 'En Curso' $condicion";
                                        // $ejecutar = mysqli_query($conectar, $consulta) or die(mysqli_error($conectar));
                                        // $row = $ejecutar->fetch_assoc();
                                        // $tarjetas = $row['tarjetas'];
                                        $tarjetas = 0;
                                        ?>
                                        <p>   Tarjetas
                                        <span class="badge badge-info right"><?php echo $tarjetas;?></span>
                                        </p>
                                    </a>
                                </li>
                                <?php if($parametro->verificarPermisos($_SESSION['idUsuario'],4) > 0){ ?>
                                <li class="nav-item">
                                    <a href="../views/arbitraje.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>   Arbitraje</p>
                                    </a>
                                </li>   
                                <?php } if($parametro->verificarPermisos($_SESSION['idUsuario'],15) > 0){?>  
                                <li class="nav-item">
                                    <a href="../views/suspeciones.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Suspenciones</p>
                                    </a>
                                </li>
                                <?php } ?>                  
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="../views/tablaPosiciones.php" class="nav-link">
                              <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>
                                    Tabla de Posiciones
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    Reportes
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../views/Estadistica.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Estadistica Deportivas</p>
                                    </a>
                                </li>
                                <?php //if($idRol == 1){ ?>
                                <li class="nav-item">
                                    <a href="../views/Finanzas.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Datos Financieros</p>
                                    </a>
                                </li>
                                <?php //} ?>
                            </ul>
                        </li>
                        <?php if($idRol != 3){ ?>
                        <li class="nav-header">Configuraciones</li>
                        <?php } ?>
                        <?php  if($parametro->verificarPermisos($_SESSION['idUsuario'],'16,18,19') > 0){?>  
                        <li class="nav-item">
                            <a href="../views/Usuarios.php" class="nav-link">
                              <i class="nav-icon fas fa-user"></i>
                                <p>
                                   Crear Usuarios
                                </p>
                            </a>
                        </li>
                        <?php } if($parametro->verificarPermisos($_SESSION['idUsuario'],'17,23,24,25') > 0){?>  
                        <li class="nav-item">
                            <a href="../views/permisos.php" class="nav-link">
                              <i class="nav-icon fas fa-user-check"></i>
                                <p>
                                   Administrar Roles
                                </p>
                            </a>
                        </li>
                        <?php } if($parametro->verificarPermisos($_SESSION['idUsuario'],3) > 0){?>  
                        <li class="nav-item">
                            <a href="../views/configurar_Cobros.php" class="nav-link">
                              <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                   Configurar Cobros
                                </p>
                            </a>
                        </li>
                        <?php } if($parametro->verificarPermisos($_SESSION['idUsuario'],13) > 0){?>  
                        <li class="nav-item">
                            <a href="../views/Historial_Anuncios.php" class="nav-link">
                              <i class="nav-icon fas fa-history"></i>
                                <p>
                                   Historial de Anuncios
                                </p>
                            </a>
                        </li>
                        <?php }?>
                        <br><br><br>
                    
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

  