<?php


session_start();
include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();
$idRol = $_SESSION['idRol'];   
$idEquipoDelegado = $_SESSION['idEquipo'];

$tipo = $_GET["op"];


if($tipo == "RegistrarPago"){

    $MotivoGasto  = $_POST["MotivoPago"];
    $totalPago  = $_POST["totalPago"];
    $fechaPago = $_POST["fechaPago"];
  
    $resultado = $parametro->RegistrarPago($MotivoGasto,$fechaPago,$totalPago);    
        
        if($resultado){
            echo 1;
        }
        else{
            echo 2;
        }
}

if($tipo == "RegistrarGasto"){

    $MotivoGasto  = $_POST["MotivoGasto"];
    $otros = $_POST["otros"];
    $totalGasto  = $_POST["totalGasto"];
    $fechaGasto = $_POST["fechaGasto"];
  
    $cantidad = $_POST["cantidad"];
    $precio  = $_POST["precio"];

    if($MotivoGasto == "Otros"){
        $MotivoGasto = $otros;
    }
    
    $resultado = $parametro->RegistrarGasto($MotivoGasto,$fechaGasto,$cantidad,$precio,$totalGasto);    

       
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}
 

if($tipo == "TotalGastado"){

    $idCampeonato = $_POST["idCampeonato"];
    
    $resultado = $parametro->TotalPagoCancha($idCampeonato);       
    if($resultado->RowCount() > 0){
        $row= $resultado->Row();
        $totalGastoCancha = $row->total;
    }
    else{
        $totalGastoCancha = 0;
    }


    $resultado = $parametro->TotalPagoArbitraje($idCampeonato); 
    if($resultado->RowCount() > 0){
        $row= $resultado->Row();
        $totalGastoArbitraje =  $row->total;
    }
    else{
        $totalGastoArbitraje = 0;
    }


    $resultado = $parametro->TotalGastosInternos($idCampeonato); 
    if($resultado->RowCount() > 0){
        $row= $resultado->Row();
        $totalGastosInternos = $row->total;
    }
    else{
        $totalGastosInternos = 0;
    }         

    $total = $totalGastoCancha + $totalGastoArbitraje + $totalGastosInternos;

    $res = array('totalGastoCancha' => number_format($totalGastoCancha,2), 'totalGastoArbitraje' => number_format($totalGastoArbitraje,2), 'totalGastosInternos' => number_format($totalGastosInternos,2), 'totalGastoGlobal' => number_format($total,2));   
    echo json_encode($res);
   
}



if($tipo == "ListarPagos"){

    $idCampeonato = @$_POST["idCampeonato"];
   
    $resultado = $parametro->listarPagos($idCampeonato);    

        $tabla = "";

            $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        $total = 0;
        if ($resultado->RowCount() > 0) {
            while (!$resultado->EndOfSeek()) {
                $listado = $resultado->Row();
                $cont++;
                $total = $total + $listado->total;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->motivoGasto . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fecha . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado->total . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody> 
        <tfoot>";
        
        $tabla .= "<tr>";            
        $tabla .= "<td data-title='' colspan=3 style='text-align:right;'><b>Total Gastos Cancha y Arbitraje: </b></td>";
        $tabla .= "<td data-title=''><b>Bs. " . number_format($total,2) . "</b></td>";
        $tabla .= "</tr>
        </tfoot>";
        $tabla .= "</table>";
        echo  $tabla;     
}


if($tipo == "FiltrarGastosInternos"){

    $idCampeonato = $_POST["idCampeonato"];

    $resultado = $parametro->obtenerListadoGastos($idCampeonato); 

    $tabla="";
        $tabla .= '<table id="example2" class="table table-bordered table-striped table-hover"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Motivo</th>
                            <th>Fecha</th>
                            <th>Cant.</th>
                            <th>Precio</th>
                            <th>Total</th>     
                        </tr>
                    </thead>
                    <tbody > ';
    
        $cont = 0;
        $total = 0;
        if ($resultado->RowCount() > 0) {
            while (!$resultado->EndOfSeek()) {
                $listado = $resultado->Row();
                $cont++;
                $total = $total + $listado->total;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->motivoGasto . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fecha . "</td>";
                $tabla .= "<td data-title=''>" . $listado->cantidad . "</td>";
                $tabla .= "<td data-title=''>Bs. " . number_format($listado->precio,2) . "</td>";
                $tabla .= "<td data-title=''>Bs. " . number_format($listado->total,2) . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody> 
        <tfoot>";
        
        $tabla .= "<tr>";            
        $tabla .= "<td data-title='' colspan=5 style='text-align:right;'><b>Total Gastos Internos: </b></td>";
        $tabla .= "<td data-title=''><b>Bs. " . number_format($total,2) . "</b></td>";
        $tabla .= "</tr>
        </tfoot>";
        $tabla .= "</table>";
        echo  $tabla;   
}

 



