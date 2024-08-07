<?php

session_start();
$idEquipoDelegado = $_SESSION['idEquipo'];
$idRol = $_SESSION['idRol'];   
include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();


$tipo = $_GET["op"];


if($tipo == "RegistrarJugador"){

    $nombre  = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $carnet = $_POST["carnet"];
    $nacimiento = $_POST["fecha"];
    // $nombreMision = $_POST["mision"];
    // $añoMision = $_POST["anoMision"];
    $nroCamiseta = $_POST["nroCamiseta"];
    $idEquipo = $_POST["idEquipo"];

    $resultado = $parametro->RegistrarJugador($nombre,$apellidos,$carnet,$nacimiento,$nroCamiseta,$idEquipo); 
     echo $resultado;
}

if($tipo == "EditarJugador"){

    $id  = $_POST["id"];
    $nombre  = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $carnet = $_POST["carnet"];
    $nacimiento = $_POST["fecha"];
    // $nombreMision = $_POST["mision"];
    // $añoMision = $_POST["anoMision"];
    $nroCamiseta = $_POST["nroCamiseta"];
    $idEquipo = $_POST["idEquipo"];
    
    $resultado = $parametro->EditarJugador($id,$nombre,$apellidos,$carnet,$nacimiento,$nroCamiseta,$idEquipo);    

    echo $resultado;
     
}


if($tipo == "ListaJugadores"){

    $nombre = @$_POST["nombre"];
    $idEquipo = @$_POST["idEquipo"];
    $idRol = @$_POST["idRol"];
    $idEquipoDelegado = @$_POST["idEquipoDelegado"];

    $resultado1 = $parametro->ListaJugadores($idEquipoDelegado,$idRol,$nombre,$idEquipo);    
  
    $resultado1->MoveFirst();
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
                    <th>Equipo</th>';
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'30,31') > 0){
                        $tabla .= '<th>Acción</th>';
                    }                                 
                    $tabla .= '</tr>
            </thead>
            <tbody > ';
      
        $cont = 0;
        if ($resultado1->RowCount() > 0) {
            while (!$resultado1->EndOfSeek()) {
                $listado = $resultado1->Row();
                $cont++;
             
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nombre . "</td>";
                $tabla .= "<td data-title=''>" . $listado->apellidos . "</td>";
                $tabla .= "<td data-title=''>" . $listado->ci . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fechaNacimiento . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nroCamiseta . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nombreEquipo . "</td>";               
                if($parametro->verificarPermisos($_SESSION['idUsuario'],'30,31') > 0){
                    $tabla .= "<td data-title=''>";
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],30) > 0){
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalEditarJugador(".chr(34).$listado->idJugador.chr(34).",".chr(34).$listado->nombre.chr(34).",".chr(34).$listado->apellidos.chr(34).",".chr(34).$listado->ci.chr(34).",".chr(34).$listado->fechaNacimiento.chr(34).",".chr(34).$listado->nroCamiseta.chr(34).",".chr(34).$listado->idEquipo.chr(34).")'><i title= 'Editar' class='fas fa-pen'></i></button>";      
                    }
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],31) > 0){
                        if($listado->estado == "Habilitado"){                        
                            $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado->idJugador.")'>Deshabilitar</button>";
                           
                        }else{
                            $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado->idJugador.")'>Habilitar</button>";                           
                        }
                    }
                    $tabla .= "</td>";
                }
                
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
 
    $resultado = $parametro->EstadoJugador($id,$estado);  
    echo $resultado;
}