<?php
//includo il file relativo alla connessione al database 
require "connect.php";
//recupero l'id del commento da eliminare
$comment_id = $_POST['comment_id'];
//eseguo una query per eliminare il commento
$stmt = $mysqli->prepare("DELETE FROM comments WHERE id = ?");
$stmt->bind_param('i', $comment_id);
$stmt -> execute();
$stmt->close();
$mysqli->close();
?>	     

