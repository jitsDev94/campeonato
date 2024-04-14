   <?php 
   require_once '../conexion/parametros.php';

   $parametro = new parametros();

   ?>
   <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../views/index3.html" class="brand-link">
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
                        <a href="#" class="d-block"><?php if($nombreEquipoDelegado == "" || $nombreEquipoDelegado == null){ echo 'Miembro Directiva';}else{ echo $nombreEquipoDelegado; }?></a>
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
                                    <?php  
                                        $jugadores =  $parametro->TotalJugadores($idEquipoDelegado,$idRol);                                           
                                    ?>
                                <p>
                                    Jugadores        
                                    <span class="badge badge-success right"><?php echo $jugadores;?></span>                       
                                </p>
                            </a>
                        </li>
                        <?php if($idRol == 1){ ?>
                        <li class="nav-item">
                            <a href="../views/transferencia.php" class="nav-link">
                              <i class="nav-icon fas fa-random"></i>
                                <p>
                                    Transferencias                               
                                </p>
                            </a>
                        </li>
                            <?php }
                            if($idRol == 1){ ?>
                        <li class="nav-item">
                            <a href="../views/equipos.php" class="nav-link">
                              <i class="nav-icon fas fa-users"></i>
                              <?php                                    
                                        $consulta = "SELECT count(id) as totalEquipos from Equipo where estado = 'Habilitado'";
                                        $ejecutar = mysqli_query($conectar, $consulta) or die(mysqli_error($conectar));
                                        $row = $ejecutar->fetch_assoc();
                                        $Equipos = $row['totalEquipos'];
                                    ?>
                                <p>
                                    Equipos
                                    <span class="badge badge-success right"><?php echo $Equipos;?></span>
                                </p>
                            </a>
                        </li>
                            <?php }
                            if($_SESSION['idRol'] == 1){ ?>
                        <li class="nav-item">
                            <a href="../views/inscripcion.php" class="nav-link">
                              <i class="nav-icon fas fa-file-signature"></i>
                                    <?php                                    
                                        $consulta = "SELECT count(i.id) as totalInscritos FROM Inscripcion as i
                                        LEFT JOIN Campeonato as c on c.id = i.idCampeonato
                                        where c.estado = 'En Curso'";
                                        $ejecutar = mysqli_query($conectar, $consulta) or die(mysqli_error($conectar));
                                        $row = $ejecutar->fetch_assoc();
                                        $inscritos = $row['totalInscritos'];
                                    ?>
                                <p>
                                    Inscripción    
                                    <span class="badge badge-success right"><?php echo $inscritos;?></span>                                
                                </p>
                            </a>
                        </li>
                        <?php }
                            if($_SESSION['idRol'] == 1){ ?>
                        <li class="nav-item">
                            <a href="../views/campeonato.php" class="nav-link">
                              <i class="nav-icon fas fa-trophy"></i>
                                <p>
                                    Torneos                               
                                </p>
                            </a>
                        </li>

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
                              <?php  

                                $condicion = "";
                                if($idRol == 3){
                                    $condicion = "and m.idEquipo = $idEquipoDelegado";
                                }  

                            //    $consulta = "SELECT count(m.motivoMulta) as totalMultas FROM Multa as m
                            //    LEFT JOIN Campeonato as c on c.id = m.IdCampeonato
                            //    LEFT JOIN Equipo as e on e.id = m.idEquipo
                            //    where c.estado = 'En Curso' and m.estado ='Pendiente' $condicion";
                            //    $ejecutar = mysqli_query($conectar, $consulta) or die(mysqli_error($conectar));
                            //    $row = $ejecutar->fetch_assoc();
                            //   $totalMultas = $row['totalMultas'];
                            $totalMultas = 0;?>
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
                            <?php if($idRol == 1){ ?>
                                <li class="nav-item">
                                    <a href="../views/programacionPartidos.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> Programar Partidos</p>
                                    </a>
                                </li>
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
                                        <?php  
                                        $condicion = "";
                                        if($idRol == 3){
                                            $condicion = "and p.equipoObservado = $idEquipoDelegado";
                                        }                                  
                                        $consulta = "SELECT COUNT(p.id) as tarjetas FROM Partido as p 
                                        LEFT join Campeonato as c on c.id = p.idCampeonato
                                        where p.estadoObservacion = 'Pendiente' and c.estado = 'En Curso' $condicion";
                                        $ejecutar = mysqli_query($conectar, $consulta) or die(mysqli_error($conectar));
                                        $row = $ejecutar->fetch_assoc();
                                        $Observaciones = $row['tarjetas'];
                                        ?>
                                        <p>   Observaciones
                                        <span class="badge badge-danger right"><?php echo $Observaciones;?></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/tarjetas.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <?php   
                                        $condicion = "";
                                        if($idRol == 3){
                                            $condicion = " and e.id = $idEquipoDelegado";
                                        } 

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
                                <?php if($idRol == 1){ ?>
                                <li class="nav-item">
                                    <a href="../views/arbitraje.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>   Arbitraje</p>
                                    </a>
                                </li>   
                                <li class="nav-item">
                                    <a href="../views/suspeciones.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>   Suspenciones</p>
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
                                <?php if($idRol == 1){ ?>
                                <li class="nav-item">
                                    <a href="../views/Finanzas.php" class="nav-link" style="padding-left:35px;">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Datos Financieros</p>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php if($idRol == 1){ ?>
                        <li class="nav-header">Configuraciones</li>
                        <li class="nav-item">
                            <a href="../views/Usuarios.php" class="nav-link">
                              <i class="nav-icon fas fa-user"></i>
                                <p>
                                   Crear Usuarios
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/configurar_Cobros.php" class="nav-link">
                              <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                   Configurar Cobros
                                </p>
                            </a>
                        </li>
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
                         <!--<li class="nav-header">EXTRAS</li>
                         <li class="nav-item">
                            <a href="../views/pages/calendar.html" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                <p>
                                    Calendar
                                    <span class="badge badge-info right">2</span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/pages/gallery.html" class="nav-link">
                                <i class="nav-icon far fa-image"></i>
                                <p>
                                    Gallery
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/pages/kanban.html" class="nav-link">
                                <i class="nav-icon fas fa-columns"></i>
                                <p>
                                    Kanban Board
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon far fa-envelope"></i>
                                <p>
                                    Mailbox
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../views/pages/mailbox/mailbox.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Inbox</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/mailbox/compose.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Compose</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/mailbox/read-mail.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Read</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Pages
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../views/pages/examples/invoice.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Invoice</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/profile.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Profile</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/e-commerce.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>E-commerce</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/projects.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Projects</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/project-add.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Project Add</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/project-edit.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Project Edit</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/project-detail.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Project Detail</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/contacts.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Contacts</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/faq.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>FAQ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/contact-us.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Contact us</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon far fa-plus-square"></i>
                                <p>
                                    Extras
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../views/#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Login & Register v1
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/login.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Login v1</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/register.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Register v1</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/forgot-password.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Forgot Password v1</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/recover-password.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Recover Password v1</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Login & Register v2
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/login-v2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Login v2</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/register-v2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Register v2</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/forgot-password-v2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Forgot Password v2</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/pages/examples/recover-password-v2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Recover Password v2</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/lockscreen.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Lockscreen</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/legacy-user-menu.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Legacy User Menu</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/language-menu.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Language Menu</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/404.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Error 404</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/500.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Error 500</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/pace.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pace</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/examples/blank.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Blank Page</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/starter.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Starter Page</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon fas fa-search"></i>
                                <p>
                                    Search
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../views/pages/search/simple.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Simple Search</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/pages/search/enhanced.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Enhanced</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">MISCELLANEOUS</li>
                        <li class="nav-item">
                            <a href="../views/iframe.html" class="nav-link">
                                <i class="nav-icon fas fa-ellipsis-h"></i>
                                <p>Tabbed IFrame Plugin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/https://adminlte.io/docs/3.1/" class="nav-link">
                                <i class="nav-icon fas fa-file"></i>
                                <p>Documentation</p>
                            </a>
                        </li>
                        <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>Level 1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>
                                    Level 1
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../views/#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Level 2</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Level 2
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="../views/#" class="nav-link">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Level 3</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/#" class="nav-link">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Level 3</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../views/#" class="nav-link">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Level 3</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="../views/#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Level 2</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>Level 1</p>
                            </a>
                        </li>
                        <li class="nav-header">LABELS</li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon far fa-circle text-danger"></i>
                                <p class="text">Important</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Warning</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/#" class="nav-link">
                                <i class="nav-icon far fa-circle text-info"></i>
                                <p>Informational</p>
                            </a>
                        </li> -->
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

  