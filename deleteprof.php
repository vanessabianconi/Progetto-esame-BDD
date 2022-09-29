<?php
//avvio la sessione
session_start();
//includo file connessione al db
require "connect.php";


if (isset($_SESSION['id'])) {
    $id = trim($_SESSION['id']);

// sql to delete a record
    $sql = $mysqli->prepare("DELETE FROM `users` WHERE id = ?");
    $sql->bind_param('i', $id);


    if ($sql->execute()) {
        //se la query è andata a buon fine stampo un messaggio
        echo '<div class="alert alert-success" role="alert"><p><strong>' . "Utente cancellato" . '</strong></p></div>';
        // chiudo la sessione
        unset($_SESSION['id']);
        session_destroy();
    } else {
        echo '<div class="alert alert-danger" role="alert"><p><strong>' . "Si è verificato un errore" . '</strong></p></div>';
    }
    $sql->close();

    // chiudo la connessione
    $mysqli->close();
}
?>

<!DOCTYPE html>
<head>
    <title> Cancella utente </title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css?ts=<?=time()?>&quot" type="text/css">
</head>
<div class="col-10 text-left">
    <a class="btn btn-outline-secondary btn-sm" href="index.php">
        <i class="fa fa-arrow-left"></i>
        Torna alla home
    </a>
</div>
<?php include("footer.php")?>
