<?php

session_start();

if (isset($_SESSION['user_id'])) {
	require_once("UsuarioDAO.inc");
	$usuarios = new UsuarioDAO();
	if ($usuarios->getCurrent() && $usuarios->getCurrent()->isAdmin());
	else {
		header('Location:login.php');
	}
} else {
	header('Location:login.php');
}

?>
