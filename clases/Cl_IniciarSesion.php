<?php
session_start();

require_once '../conexion/parametros.php';

$parametro = new parametros();

    $usuario = $_POST["user"];
    $password = $_POST["contra"];
      
    $resp = $parametro->autenticacionUsuario($usuario,$password); 
      
    if($resp->RowCount() > 0){
        $resp->MoveFirst();
        $row = $resp->Row();		      
        if ($row->estadoUsuario == "Deshabilitado") {
            echo 'deshabilitado';
        } else {
            $_SESSION['idUsuario'] = $row->idUsuario;
            $_SESSION['idRol'] = $row->idRol;
            $_SESSION['nombreRol'] = $row->nombreRol;
            $_SESSION['usuario'] = $row->usuario;
            $_SESSION['idEquipo'] = $row->idEquipo;
            $_SESSION['nombreEquipo'] = $row->nombreEquipo;

            //tipo de campeonato actual
            $resp2 = $parametro->TraerUltimoTorneo();                   
            $_SESSION['tipoCampeonato']= $resp2->tipo;
            echo 'ok';
        }          
    }
    else{
        echo 'no existe';
    }
      
?>