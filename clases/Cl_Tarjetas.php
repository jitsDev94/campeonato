<?php



include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();

session_start();
$idRol = $_SESSION['idRol'];   
$idEquipoDelegado = $_SESSION['idEquipo'];

$tipo = $_GET["op"];


if($tipo == "RegistrarPagoTarjeta"){

    $precio  = $_POST["precio"];
    $id = $_POST["id"];

    $resultado = $parametro->RegistrarPagoTarjeta($id,$precio); 
       
        if($resultado == 'ok'){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "precioTarjetaAmarilla"){

    $consultar = "SELECT precio FROM configuracionCobros where motivo = 'Tarjetas Amarillas'";
    $resultado2 = mysqli_query($conectar, $consultar);
    $row = $resultado2->fetch_assoc();
    $precio = $row['precio'];

    if($resultado2)
    {
        echo $precio;
    }
    else
    {
        echo 'error';
    }  
}

if($tipo == "precioTarjetaRoja"){

    $consultar = "SELECT precio FROM configuracionCobros where motivo = 'Tarjetas Rojas'";
    $resultado2 = mysqli_query($conectar, $consultar);
    $row = $resultado2->fetch_assoc();
    $precio = $row['precio'];

    if($resultado2)
    {
        echo $precio;
    }
    else
    {
        echo 'error';
    }  
}


if($tipo == "EliminarTarjetas"){

    $id  = $_POST["id"];
           
        $resultado = $parametro->EliminarTarjetas($id); 
       
        if($resultado == 'ok'){       
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "ListaTarjetasRojas"){
   
    $permisos = $parametro->verificarPermisos($_SESSION['idUsuario'],'2');
    $resultado1 = $parametro->ListaTarjetasRojas($idRol,$idEquipoDelegado,$permisos);    

        $tabla = "";
              
            $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Equipo</th>
                    <th width="80px">Fecha</th>';
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'2') > 0){
             $tabla .= '        <th width="65px">Acción</th>   ';   
                    }
            $tabla .= '  </tr>
            </thead>
            <tbody > ';
        
      
     
        $cont = 0;
        if ($resultado1->RowCount() > 0) {
            while (!$resultado1->EndOfSeek()) {
                $listado = $resultado1->Row();
                $nombreCompleto = $listado->nombre . " " . $listado->apellidos;
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nombre . " " . $listado->apellidos . "</td>";
                $tabla .= "<td data-title=''>" . $listado->Equipo . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fechaPartido . "</td>";
                if($parametro->verificarPermisos($_SESSION['idUsuario'],'2') > 0){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalCobrarTarjetaRoja(".chr(34). $listado->idHp.chr(34).",".chr(34). $nombreCompleto . chr(34).")'><i title= 'Regitrar Pago' class='fas fa-hand-holding-usd'></i></button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$listado->idHp.")'><i title= 'Eliminar' class='fas fa-trash'></i></button>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "ListaTarjetasAmarillas"){

    $permisos = $parametro->verificarPermisos($_SESSION['idUsuario'],'2');
    $resultado1 = $parametro->ListaTarjetasAmarillas($idRol,$idEquipoDelegado,$permisos);  
   

        $tabla = "";
       
            $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                     <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Equipo</th>
                    <th width="80px">Fecha</th>';
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'2') > 0){
             $tabla .= '<th width="65px">Acción</th>   ';   
                    }
            $tabla .= '</tr>
                </tr>
            </thead>
            <tbody > ';
        
      
     
        $cont = 0;
        if ($resultado1->RowCount() > 0) {
            while (!$resultado1->EndOfSeek()) {
                $listado = $resultado1->Row();
                $nombreCompleto = $listado->nombre . " " . $listado->apellidos;
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nombre . " " . $listado->apellidos . "</td>";
                $tabla .= "<td data-title=''>" . $listado->Equipo . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fechaPartido . "</td>";
                if($parametro->verificarPermisos($_SESSION['idUsuario'],'2') > 0){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalCobrarTarjetaAmarilla(".chr(34). $listado->idHp.chr(34).",".chr(34). $nombreCompleto . chr(34).")'><i title= 'Regitrar Pago' class='fas fa-hand-holding-usd'></i></button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$listado->idHp.")'><i title= 'Eliminar' class='fas fa-trash'></i></button>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarTarjetas"){


    $tarjetas = $_POST["tarjetas"];
    $idCampeonato = $_POST["idCampeonato"];
       
    $condicion = "";

    if($idRol != 1 && $idRol != 2){
        $condicion = "and e.id = $idEquipoDelegado";
    }


    $consultar = "SELECT hp.id as idHp, j.nombre,j.apellidos,hp.Equipo,p.fechaPartido,c.nombre as campeonato,hp.estado FROM acontecimientopartido as hp 
                LEFT JOIN acontecimiento as h on h.id = hp.idAcontecimiento 
                LEFT JOIN Jugador as j on j.id = hp.idJugador 
                LEFT JOIN Partido as p on p.id = hp.idPartido
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                where h.nombreAcontecimiento  = '$tarjetas' and p.idCampeonato = $idCampeonato $condicion
                order by p.fechaPartido desc";
    $resultado1 = mysqli_query($conectar, $consultar);
  
       if($tarjetas == "Tarjeta Amarilla"){
        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Equipo</th>
                            <th>Torneo</th>
                            <th>Fecha</th>        
                            <th>Estado</th>        
                        </tr>
                    </thead>
                    <tbody > ';
       }
       else{
        $tabla = "";
        $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Equipo</th>
                            <th>Torneo</th>
                            <th>Fecha</th> 
                            <th>Estado</th>        
                        </tr>
                    </thead>
                    <tbody > ';
       }
    
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['Equipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['estado'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo $tabla;
}



