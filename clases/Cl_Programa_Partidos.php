<?php

session_start();
include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();


$tipo = $_GET["op"];



if($tipo == "RegistrarProgramacionPartido"){

    $idEquipoLocal  = $_POST["idEquipoLocal"];
    $idEquipoVisitante = $_POST["idEquipoVisitante"];
    $fecha  = $_POST["fecha"];
    $Cancha = $_POST["Cancha"];
    $confirmacion = $_POST["confirmacion"];

    $resultado1 = $parametro->RegistrarProgramacionPartido($idEquipoLocal,$idEquipoVisitante,$fecha,$Cancha,$confirmacion );   
    echo json_encode($resultado1);
    // $registrar = "SELECT * FROM programacionPartidos where estado = 'Pendiente' and (codEquipoLocal = $idEquipoLocal or codEquipovisita = $idEquipoLocal)";
    // $resultado = mysqli_query($conectar, $registrar);
    // $row = mysqli_num_rows($resultado);
    // if($row > 0 && $confirmacion == 0){
    //     echo "El equipo local ya tiene un partido programado";
    // }
    // else{

    //     $registrar = "SELECT * FROM programacionPartidos where estado = 'Pendiente' and  (codEquipoLocal = $idEquipoVisitante or codEquipovisita = $idEquipoVisitante)";
    //     $resultado = mysqli_query($conectar, $registrar);
    //     $row = mysqli_num_rows($resultado);
    //     if($row > 0 && $confirmacion == 0){
    //         echo "El equipo visitante ya tiene un partido programado";
    //     }
    //     else{
    //         $registrar = "INSERT INTO programacionPartidos (codEquipoLocal,codEquipoVisita,fecha,cancha,estado) values('$idEquipoLocal','$idEquipoVisitante','$fecha','$Cancha','Pendiente')";
    //         $resultado = mysqli_query($conectar, $registrar);
            
    //         if($resultado){
    //             echo '1';
    //         }
    //         else{
    //             echo 'error';
    //         }
    //     }       
    // }
}


if($tipo == "ListaEquipos2"){

  $cancha = 'Cancha 1';
    $resultado1 = $parametro->ListarPartidosProgramados($cancha);          
    $datosPartidos = $parametro->ObtenerFechaPartidosProgramados();  
    if($datosPartidos->RowCount() > 0){
        $datosPartido = $datosPartidos->Row();
        $fechaPartido = date('d-m-Y',strtotime( $datosPartido->fecha));
    }
    
   
    $tabla = "";
    $total = 0;
    $tabla .= '<div class="row text-center">';
    if ($resultado1->RowCount() > 0) {
        $total ++;
        $tabla .= '  <div class="col-md-12"><h5><b>Fecha Partido: </b> '.$fechaPartido.'</h5><h5><b>'.$cancha.'</b></h5> </div>';
        while (!$resultado1->EndOfSeek()) {
            $listado = $resultado1->Row();
           
        $tabla .= '        <div class="col-lg-4 col-md-2 col-sm-2">';
        $tabla .= '            <div class="card info-box shadow-lg" style="border-top: 3px solid green; ">';
        $tabla .= '                <div class="card-body">';                        
                    $tabla .= '        <div class="row">';
                    $tabla .= '             <div class="col-md-12"><h6><b>Hora Partido:</b><br> '.date('H:i',strtotime( $listado->fecha)).'</h6> </div>';
                    $tabla .= '            <div class="col-md-5 col-5">';                  
                    $tabla .= '                <img src="../img/logoEquipo.png" alt="Sin Logo" width="100%"><br><span >'.$listado->equipoLocal.'</span>';
                    $tabla .= '            </div>';
                    $tabla .= '            <div class="col-md-2  col-2 text-center pt-4">VS<br></div>';
                    $tabla .= '            <div class="col-md-5  col-5">';               
                    $tabla .= '                <img src="../img/logoEquipo.png" alt="Sin Logo"  width="100%"><br><span >'.$listado->equipoVisita.'';
                    $tabla .= '            </div>';
                    $tabla .= '        </div>';
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'14') > 0){
                    $tabla .= '        <div class="row text-center mt-3">';                  
                    $tabla .= '            <div class="col-md-12 col-12">';
                    $tabla .= "                 <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ModalRegistrarPartido(". chr(34) .$listado->codProgramacion. chr(34) .",". chr(34) .$listado->codEquipoLocal. chr(34) .", ". chr(34) .$listado->codEquipovisita. chr(34) .", ". chr(34) . date('Y-m-d',strtotime( $datosPartido->fecha)). chr(34) .")'>Registrar</button>";
                    $tabla .= "                 <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ModalEliminarPartido(". chr(34) .$listado->codProgramacion. chr(34) .",". chr(34) .$listado->equipoLocal. chr(34).", ". chr(34) .$listado->equipoVisita. chr(34) .")'>Eliminar</button>";
                    $tabla .= '            </div>';
                    $tabla .= '        </div>';
                    }
        $tabla .= '                </div>';
        $tabla .= '            </div>';
        $tabla .= '        </div>';
        }
    }
    $tabla .= '    </div>'; 

    $cancha = 'Cancha 2';
    $resultado1 = $parametro->ListarPartidosProgramados($cancha); 
    if ($resultado1->RowCount() > 0) { 
        $total ++;
        $tabla .= '<hr><div class="row text-center">';
       $tabla .= '  <div class="col-md-12"> <h5><b>'.$cancha.'</b></h5></div>';
        while (!$resultado1->EndOfSeek()) {
            $listado = $resultado1->Row();
           
        $tabla .= '        <div class="col-lg-4 col-md-2 col-sm-2">';
        $tabla .= '            <div class="card info-box shadow-lg">';
        $tabla .= '                <div class="card-body">';                        
                    $tabla .= '        <div class="row">';        
                    $tabla .= '             <div class="col-md-12"><h6><b>Hora Partido:</b><br> '.date('H:i',strtotime( $listado->fecha)).'</h6> </div>';          
                    $tabla .= '            <div class="col-md-5 col-5">';                  
                    $tabla .= '                <img src="../img/logoEquipo.png" alt="Sin Logo" width="100%"><br><span >'.$listado->equipoLocal.'</span>';
                    $tabla .= '            </div>';
                    $tabla .= '            <div class="col-md-2  col-2 text-center pt-4">VS<br></div>';
                    $tabla .= '            <div class="col-md-5  col-5">';               
                    $tabla .= '                <img src="../img/logoEquipo.png" alt="Sin Logo"  width="100%"><br><span >'.$listado->equipoVisita.'';
                    $tabla .= '            </div>';
                    $tabla .= '        </div>';
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'14') > 0){
                    $tabla .= '        <div class="row text-center mt-3">';                  
                    $tabla .= '            <div class="col-md-12 col-12">';
                    $tabla .= "                 <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ModalRegistrarPartido(". chr(34) .$listado->codProgramacion. chr(34) .",". chr(34) .$listado->codEquipoLocal. chr(34) .", ". chr(34) .$listado->codEquipovisita. chr(34) .", ". chr(34) . date('Y-m-d',strtotime( $datosPartido->fecha)). chr(34) .")'>Registrar</button>";
                    $tabla .= "                 <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ModalEliminarPartido(". chr(34) .$listado->codProgramacion. chr(34) .",". chr(34) .$listado->equipoLocal. chr(34).", ". chr(34) .$listado->equipoVisita. chr(34) .")'>Eliminar</button>";
                    $tabla .= '            </div>';
                    $tabla .= '        </div>';
                    }
        $tabla .= '                </div>';
        $tabla .= '            </div>';
        $tabla .= '        </div>';
        }
        $tabla .= '    </div>'; 
    }
    
    if($total == 0){
        $tabla .= '  <div class="col-md-12 text-center"><h5>Aun no hay partidos programados</h5></div>';
    }
    echo  $tabla;   
}


if($tipo == "ListaEquipos"){

        $resultado1 = $parametro->ListarPartidosProgramados();   

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Cancha</th>
                        <th>Equipo Local</th>
                        <th>Equipo Visitante</th>
                        <th>Fecha</th>                      
                        <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1->RowCount()) {
            $resultado1->MoveFirst();
            while (!$resultado1->EndOfSeek()) {
                $cont++;
                $listado = $resultado1->Row();
                $fecha = date('Y-m-d', strtotime($listado->fecha));
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->cancha . "</td>";
                $tabla .= "<td data-title=''>".$listado->equipoLocal."</td>";
                $tabla .= "<td data-title=''>" . $listado->equipoVisita . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fecha . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ModalRegistrarPartido(". chr(34) .$listado->codProgramacion. chr(34) .",". chr(34) .$listado->codEquipoLocal. chr(34) .", ". chr(34) .$listado->codEquipovisita. chr(34) .", ". chr(34) . $fecha. chr(34) .")'>Registrar</button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ModalEliminarPartido(". chr(34) .$listado->codProgramacion. chr(34) .",". chr(34) .$listado->equipoLocal. chr(34).", ". chr(34) .$listado->equipoVisita. chr(34) .")'>Eliminar</button>";      
                // if($listado['estado'] == "Habilitado"){
                //     $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado['id'].")'>Deshabilitar</button>";                          
                // }else{
                //     $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado['id'].")'>Habilitar</button>";                          
                // }
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}



if($tipo == "ListarPartidos"){

    $cancha = $_POST["cancha"];

    $resultado1 = $parametro->ListarPartidosProgramados($cancha);   

    $tabla = "";

    if($cancha = "Cancha 1"){
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">';      
    }
    else{
       
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">';
    }
       
    $tabla .= '<thead>
        <tr>
        <th>#</th>                     
        <th>Equipo Local</th>
        <th>Equipo Visitante</th>
        <th>Hora</th>                     
        </tr>
    </thead>
    <tbody > ';

        $cont = 0;
        if ($resultado1->RowCount()) {
            $resultado1->MoveFirst();
            while (!$resultado1->EndOfSeek()) {
                $cont++;
                $listado = $resultado1->Row();
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";              
                $tabla .= "<td data-title=''>".$listado->equipoLocal."</td>";
                $tabla .= "<td data-title=''>" . $listado->equipoVisita . "</td>";
                $tabla .= "<td data-title=''>" . $listado->hora . "</td>";             
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                </table>";
        echo  $tabla;   
}


if($tipo == "datosProgramacionPartidos"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as fecha,cancha FROM programacionPartidos  where estado = 'Pendiente' limit 1";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}

if($tipo == "eliminarPartidoProgramado"){

    $codProgramacion = $_POST["codProgramacion"];
    $resultado1 = $parametro->eliminarPartidoProgramado($codProgramacion);
    echo $resultado1;
    // $Consultar = "DELETE from programacionPartidos where codProgramacion = $codProgramacion";
    // $resultado = mysqli_query($conectar, $Consultar);
  

    // if($resultado){
    //     echo "1";
    // }
    // else{
    //     echo $Consultar;
    // } 
}