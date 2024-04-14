<?php


include_once("conexionBd.php");


class negocio
{

    public function TraerEquipoCampeon($idCampeonato)
	{       

        $consulta = "SELECT * FROM EquipoCampeon where idCampeonato =$idCampeonato";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    public function TraerCodEquipo($nombreEquipo)
	{       

        $consulta = "SELECT id FROM Equipo where nombreEquipo = '$nombreEquipo'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    public function TraerEquipoTablaPosicion($CodEquipo,$codCampeonato)
	{       

        $consulta = "SELECT * FROM TablaPosicion where idEquipo = $CodEquipo and idCampeonato = $codCampeonato";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    function TraerUltimoSede()
	{       

        $consulta = "SELECT * FROM Sede where estado = 'Habilitado'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }


     function TraerUltimoTorneo()
	{       

        $consulta = "SELECT * FROM Campeonato where estado = 'En Curso'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

     function TraerUltimoPartido()
	{       

        $consulta = "SELECT * FROM Partido order by id desc";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        $ultimoPartido = $row->id +1;
        return $row;
      
    }

    public function ActualizarTablaPosicion($puntos, $PartidosGanados, $PartidosEmpatados, $PartidosPerdidos, $golFavor, $golContra, $idValidacionEquipo)
	{       

        $consulta = "UPDATE TablaPosicion SET puntos = puntos + $puntos, partidosGanados = partidosGanados + $PartidosGanados, partidosEmpatados = partidosEmpatados + $PartidosEmpatados, partidosPerdidos  = partidosPerdidos + $PartidosPerdidos, golFavor = golFavor + $golFavor,golContra = golContra + $golContra where id = $idValidacionEquipo";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function AgregarTablaPosicion($equipo,$idCampeonato,$puntos,$PartidosGanados,$PartidosEmpatados,$PartidosPerdidos,$golFavor,$golContra)
	{       

        $consulta = "INSERT INTO TablaPosicion values(null,$equipo,$idCampeonato,$puntos,$PartidosGanados,$PartidosEmpatados,$PartidosPerdidos,$golFavor,$golContra,null)";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function ActualizarTablaPosicionWalkover($equipoGanadorWalkover,$codCampeonato)
	{       

        $consulta = "UPDATE TablaPosicion SET puntos = puntos + 3, partidosGanados = partidosGanados + 1 where id = $equipoGanadorWalkover and idCampeonato = $codCampeonato";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function ActualizarTablaPosicionWalkoverPerdedor($equipoPerdedorWalkover,$codCampeonato)
	{       

        $consulta = "UPDATE TablaPosicion SET  partidosPerdidos = partidosPerdidos + 1 where id = $equipoPerdedorWalkover and idCampeonato = $codCampeonato";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function InsertarPartido($idPartido,$equipo1,$equipo2,$idSede,$fecha,$idCampeonato,$modo,$observacion,$estadoObservacion,$idEquipoObservado,$PrecioObservacion)
	{       

        $consulta = "INSERT INTO Partido VALUES($idPartido,$equipo1,$equipo2,$idSede,'$fecha',$idCampeonato,'$modo','$observacion','$estadoObservacion','',$idEquipoObservado,$PrecioObservacion)";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function InsertarHecho($idHecho,$idJugador,$equipo,$idPartido)
	{       

        $consulta = "INSERT INTO HechosPartido values(null,$idHecho,$idJugador,'$equipo',$idPartido,0,'Pendiente')";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function actualizarProgramacionPartidos($codProgramacion)
	{       

        $consulta = "UPDATE programacionPartidos SET estado ='Completado' where codProgramacion = $codProgramacion";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function eliminarPartido($idPartido)
	{       

        $consulta = "DELETE FROM Partido where id = $idPartido";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

}
