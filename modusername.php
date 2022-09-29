<?php
require "connect.php";
include("header.php");
// Se l'utente non è loggato, lo rimando alla pagina di login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$userold = trim($_SESSION['username']);
$old_username = $new_username = "";
$old_username_err = $new_username_err = "";
//controllo che i campi non siano vuoti e che il vecchio username sia corretto
if(isset($_POST['newusernameb'])){
    if(empty(trim(htmlspecialchars($_POST['oldusername'])))){
        $old_username_err = "Inserisci il vecchio nome utente";
    } elseif($_POST['oldusername'] != $userold){
        $old_username_err = "Il vecchio username non è giusto";
    } else {
        $old_username = $_POST['oldusername'];
    }

if(isset($_POST['newusername'])){
    $sql = "SELECT id FROM users WHERE username = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s", $new_username);
        $new_username = trim(htmlspecialchars($_POST["newusername"]));
        
        if($stmt->execute()){
            $stmt->store_result();
            //se lo username esiste già stampo un messaggio di errore
            if($stmt->num_rows == 1){
                $new_username_err = "Questo username è già in uso.";
            } else{
                $new_username = trim(htmlspecialchars($_POST["newusername"]));
            }
        } else{
            echo "Oops! Qualcosa è andato storto. Riprova di nuovo.";
        }

        $stmt->close();
    }
    //controllo che il campo nuovo username non sia vuoto, controllo la lunghezza e che non sia uguale al vecchio username
    if(empty(trim(htmlspecialchars($_POST['newusername'])))){
        $new_username_err = "Inserisci un nuovo username";
    } elseif(strlen(trim($_POST['newusername']))<5){
        $new_username_err = "Il nome utente è troppo corto";
    } elseif(strlen(trim($_POST['newusername']))>15){
        $new_username_err = "Il nome utente è troppo lungo";
    } elseif(trim($_POST['newusername']) === $userold) {
        $new_username_err = "Il nome utente è uguale a quello vecchio";
    } elseif(!preg_match("/^[a-z0-9]+$/", $_POST['newusername'])){
        $new_username_err = "Usa solo lettere minuscole e numeri";
    }
}   //se non ci sono errori aggiorno il database
    if(empty($old_username_err) && empty($new_username_err)){
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("si", $new_username, $param_id);
            $param_id = $_SESSION["id"];
            if($stmt->execute()){
                // Dopo aver aggiornato il database, uso il session_destroy e rimando alla pagina di login
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Qualcosa è andato storto. Riprova più tardi.";
            }
            // Chiudo la query
            $stmt->close();
        }
    }
    
    // Chiudo la connessione
    $mysqli->close();
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
<title>Cambio username</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

<style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Cambia il tuo username</h2>
        <p>Riempi questo form per modificare il nome utente.</p>
        <form action="modusername.php" method="post"> 
            <div class="form-group">
                <label>Vecchio username:</label>
                <input type="text" name="oldusername" class="form-control <?php echo (!empty($old_username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $old_username; ?>">
                <span class="invalid-feedback"><?php echo $old_username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Nuovo username:</label>
                <input type="text" name="newusername" class="form-control <?php echo (!empty($new_username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_username; ?>">
                <span class="invalid-feedback"><?php echo $new_username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="newusernameb" class="btn btn-primary" value="Invia">
                <a class="btn btn-link ml-2" href="welcome.php">Cancella</a>
            </div>
        </form>
    </div> 
</body>
<?php include("footer.php"); ?>
</html>