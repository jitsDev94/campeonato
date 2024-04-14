<?php

session_start();
$idEquipoDelegado = $_SESSION['idEquipo'];
$idRol = $_SESSION['idRol'];   
include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "RegistrarJugador"){

    $nombre  = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $carnet = $_POST["carnet"];
    $nacimiento = $_POST["fecha"];
    $nombreMision = $_POST["mision"];
    $añoMision = $_POST["anoMision"];
    $nroCamiseta = $_POST["nroCamiseta"];
    $idEquipo = $_POST["idEquipo"];

    
        $registrar = "INSERT INTO Jugador values(null,'$nombre','$apellidos','$carnet','$nacimiento','$nombreMision','$añoMision','$nroCamiseta',$idEquipo,'Habilitado')";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "EditarJugador"){

    $id  = $_POST["id"];
    $nombre  = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $carnet = $_POST["carnet"];
    $nacimiento = $_POST["fecha"];
    $nombreMision = $_POST["mision"];
    $añoMision = $_POST["anoMision"];
    $nroCamiseta = $_POST["nroCamiseta"];
    $idEquipo = $_POST["idEquipo"];
    
        $registrar = "UPDATE Jugador SET nombre = '$nombre', apellidos = '$apellidos', ci = '$carnet', fechaNacimiento = '$nacimiento', nombreMision ='$nombreMision', anoMision = '$añoMision', nroCamiseta = '$nroCamiseta' where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "ListaJugadores"){

    $condicion = "";
    if($idRol == 3){

    $condicion = "where idEquipo = $idEquipoDelegado";
    }
    $registrarInventario="SELECT j.id as idJugador,j.nombre,j.apellidos,j.ci,j.fechaNacimiento,j.nombreMision,j.anoMision,j.nroCamiseta,e.nombreEquipo,j.estado
     FROM Jugador as j left join Equipo as e on e.id = j.idEquipo $condicion order by j.idEquipo,j.nombre asc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        if($idRol == 3){
            $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Carnet</th>
                    <th>Fecha Nac.</th>                   
                    <th>Nro Camiseta</th>
                    <th>Equipo</th>
                    <th>Misión</th>
                    <th>Año Misión</th>            
                </tr>
            </thead>
            <tbody > ';
        }
        else{
            $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Carnet</th>
                    <th>Fecha Nac.</th>                   
                    <th>Nro Camiseta</th>
                    <th>Equipo</th>
                    <th>Misión</th>
                    <th>Año Misión</th>                  
                    <th>Acción</th>	             
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
                $tabla .= "<td data-title=''>" . $listado['nombre'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['ci'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaNacimiento'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nroCamiseta'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreMision'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['anoMision'] . "</td>";
                if($idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalEditarJugador(".$listado['idJugador'].")'><i title= 'Editar' class='fas fa-pen'></i></button>";      
               
                    if($listado['estado'] == "Habilitado"){
                        $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado['idJugador'].")'>Deshabilitar</button>";                          
                        $tabla .= "</td>";
                    }else{
                        $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado['idJugador'].")'>Habilitar</button>";                          
                        $tabla .= "</td>";
                    }
                }
                
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarJugadores"){

    $nombre = $_POST["nombre"];
    $idEquipo = $_POST["idEquipo"];
    $consulta == "";

    if($nombre != "" && $idEquipo > 0){
        $consulta = "where nombre like '%$nombre%' and idEquipo = $idEquipo";
    }
    else{
        if($nombre != ""){
            $consulta = "where nombre like '%$nombre%'";
        }
    
        if($idEquipo > 0){
            $consulta = "where idEquipo = $idEquipo";
        }
    }
   
    

    $registrarInventario = "SELECT j.id as idJugador,j.nombre,j.apellidos,j.ci,j.fechaNacimiento,j.nombreMision,j.anoMision,j.nroCamiseta,e.nombreEquipo,j.estado
     FROM Jugador as j left join Equipo as e on e.id = j.idEquipo $consulta order by j.idEquipo,j.nombre asc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Carnet</th>
                            <th>Fecha Nac.</th>                   
                            <th>Nro Camiseta</th>
                            <th>Equipo</th>
                            <th>Misión</th>
                            <th>Año Misión</th>
                            <th>Accion</th>	             
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
               
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['ci'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaNacimiento'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nroCamiseta'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreMision'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['anoMision'] . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalEditarJugador(".$listado['idJugador'].")'><i title= 'Editar' class='fas fa-pen'></i></button>";      
                if($listado['estado'] == "Habilitado"){
                    $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado['idJugador'].")'>Deshabilitar</button>";                          
                }else{
                    $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado['idJugador'].")'>Habilitar</button>";                          
                }
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
  
}



if($tipo == "DatosJugador"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT * FROM Jugador where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}


if($tipo == "EstadoJugador"){

    $id = $_POST["id"];
    $estado = $_POST["estado"];

    $Consultar = "UPDATE Jugador SET estado = '$estado' where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);

    if($resultado){
        echo  '1';
    }
    else{
        echo '2';
    }   
}