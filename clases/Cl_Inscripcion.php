<?php

session_start();
include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();

$tipo = $_GET["op"];

if($tipo == "RegistrarInscripcion"){

    $idEquipo  = $_POST["idEquipo"];
    $monto = $_POST["monto"];
   
    $idCampeonato = $_POST["idCampeonato"];
    
    $resultado1 = $parametro->RegistrarInscripcion($idCampeonato,$idEquipo,$monto);    
    echo $resultado1;
   
}


if($tipo == "EditarEquipoInscrito"){

    $idEquipo  = $_POST["idEquipo"];
    $idCampeonato  = $_POST["idCampeonato"];
    $idInscripcion = $_POST["idInscripcion"];

    $resultado1 = $parametro->EditarEquipoInscrito($idInscripcion,$idEquipo,$idCampeonato);    
    echo $resultado1;

}




if($tipo == "ListaEquiposInscritos"){

    $idEquipo = $_POST["idEquipo"];
    $idCampeonato = $_POST["idCampeonato"];

    $resultado1 = $parametro->ListaEquiposInscritos($idCampeonato,$idEquipo);    

    $tabla = "";
     
        $cont = 0;
        if ($resultado1->RowCount() > 0) {
            while (!$resultado1->EndOfSeek()) {
                $listado = $resultado1->Row();
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nombreEquipo . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fecha . "</td>";              
                $tabla .= "<td data-title=''>" . $listado->campeonato . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado->inscripcion . "</td>";    
                if($parametro->verificarPermisos($_SESSION['idUsuario'],'41,22') > 0){          
                    $tabla .= "<td data-title=''>";
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'41') > 0){
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm' onclick='ModalEditarInscripcion(" . chr(34).$listado->idInscripcion .chr(34). "," . chr(34).$listado->idEquipo . chr(34).")'>Editar</button>";
                    }
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'22') > 0){
                        if($listado->estado == "Habilitado"){                        
                            $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado->idInscripcion.")'>Deshabilitar</button>";                           
                        }
                        else{
                            $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado->idInscripcion.")'>Habilitar</button>";                           
                        }
                    }
                    $tabla .= "</td>";
                }
                $tabla .= "</tr>";
            }
        }
          
        echo  $tabla;   
}

if($tipo == "EstadoInscripcion"){

    $id = $_POST["id"];
    $estado = $_POST["estado"];
 
    $resultado = $parametro->EstadoInscripcion($id,$estado);  
    echo $resultado;
}