<?php 
//richiamo il file di configurazione
require "config.php";
// Controllo la connessione
if($mysqli === false){
    die("ERRORE: Impossibile connettersi. " . $mysqli->connect_error);
    exit();
}
?>