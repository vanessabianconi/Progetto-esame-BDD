<?php
session_start();
require "connect.php";
if(isset($_POST['like'])) {
	//recupero l'id del post
	$id = $_POST['id'];
	//seleziono i like
	$stmt = $mysqli->prepare("SELECT * FROM likes WHERE postid = ? AND userid = ?");
	$stmt->bind_param('ii', $id, $_SESSION['id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$count = $result->num_rows;
	//se l'utente ha già messo like e clicca di nuovo sul bottone (dislike) elimino il record dalla tabella 
	if($count>0){
		$query = $mysqli->prepare("DELETE FROM LIKES WHERE userid=? AND postid=?");
		$query->bind_param('ii', $_SESSION['id'], $id);
		$query-> execute();

	//altrimenti se clicca sul bottone (like) aggiungo il record alla tabella like
	} else {
		$query = $mysqli->prepare("INSERT INTO LIKES (userid, postid) VALUES (?,?)");
		$query->bind_param('ii', $_SESSION['id'], $id);
		$query-> execute();
		
	}
	$stmt->close();
	$query->close();
	$mysqli->close();
}
?>
