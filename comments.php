<?php
//includo il file relativo alla connessione
require "connect.php";
//recupero l'id del post e l'id dell'utente che ha scritto il commento
$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];
$message = trim($_POST['message']);
//inserisco il commento nel database
$stmt = $mysqli->prepare("INSERT INTO comments (user_id, post_id, textc, date_time) VALUES (?, ?, ?, now())");
$stmt->bind_param('iis', $user_id, $post_id, $message);
$stmt->execute();
$stmt->close();
$mysqli->close();
?>

