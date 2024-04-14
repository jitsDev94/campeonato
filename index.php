<?php

include 'clases/conexion.php';
session_start();

if (!isset($_SESSION['idUsuario'])) {
  header("Location: views/login.php");
} else {
  $idRol = $_SESSION['idRol'];
  $usuario = $_SESSION['usuario'];
  $idEquipoDelegado = $_SESSION['idEquipo'];
  $nombreEquipoDelegado = $_SESSION['nombreEquipo'];
  header("Location: views/index.php");
}
?>