<?php
// Inizializzo la sessione
session_start();
 
// Disattivo tutte le variabili della sessione
$_SESSION = array();
 
// Distruggo la sessione
session_destroy();
 
// Rimando alla pagina di login 
header("location: login.php");
exit;
?>