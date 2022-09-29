
<?php require "connect.php";
//recupero l'id del post da cancellare
$id = $_POST['id'];
//cancello il post
$stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();
$mysqli->close();
?>
