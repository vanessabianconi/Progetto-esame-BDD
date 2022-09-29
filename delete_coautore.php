<?php require("connect.php");
//recupero l'id del blog
$id = $_POST['id'];
//aggiorno la tabella blogs
$cancella = $mysqli->prepare("UPDATE blogs SET co_author = NULL WHERE id=?");
$cancella->bind_param("i", $id);
$cancella->execute();
$cancella->close();
$mysqli->close();
?>