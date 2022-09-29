
<?php 
//script responsabile della connessione
session_start();
require "connect.php";
//recupero l'id del blog
$user = $_SESSION['id'];
$id = $_POST['id'];
//query per cancellare il blog 
$stmt = $mysqli->prepare("DELETE FROM blogs WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();
$mysqli->close();
?>