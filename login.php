<?php

 
// Controllo se l'utente è loggato
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Includo il file relativo alla connessione
require "connect.php";
 
// Definisco le variabili
$username = $password = "";
$username_err = $password_err = "";
 
if(isset($_POST['login'])){
 
    // Controllo che lo username non sia vuoto
    if(empty(trim($_POST["username"]))){
        $username_err = "Inserisci il tuo nome utente.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Controllo che la password non sia vuota
    if(empty(trim($_POST["password"]))){
        $password_err = "Inserisci la tua password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valido le credenziali
    if(empty($username_err) && empty($password_err)){
        //eseguo una query 
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // uso il bind param
            $stmt->bind_param("s", $param_username);
            
            // Setto i parametri
            $param_username = $username;
            
            // Eseguo la query
            if($stmt->execute()){
                // con store_result trasferisco i risultati alla query
                $stmt->store_result();
                
                // Controllo che esista lo username e poi verifico la password
                if($stmt->num_rows == 1){                    
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Se la password è corretta, inizio una nuova sessione
                            session_start();
                            
                            // memorizzo i dati nella variabile di sessione
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;  
                            $_SESSION['last_acted_on'] = time();                          
                            
                            // Rimando alla homepage
                            header("location: index.php");
                        } else{
                            // Se la password è sbagliato, stampo un messaggio
                            $password_err = "La password è sbagliata";
                        }
                    }
                } else{
                    // Se lo username è sbagliato stampo un messaggio
                    $password_err = "Il nome utente o la password sono sbagliati.";
                }
            } else{
                echo "Oops! Qualcosa è andato storto. Riprova più tardi.";
            }

            $stmt->close();
        }
    }
    
    // Chiudo la connessione
    $mysqli->close();
}
?>
<?php include("header.php");?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Accedi</h2>
        <p>Compila i campi con le tue credenziali</p>

        <form action="login.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="login" class="btn btn-primary" value="Accedi">
                <input type="reset" class="btn btn-secondary ml-2" value="Annulla">
            </div>
            <p>Non hai un account? <a href="register.php">Registrati</a>.</p>
        </form>
    </div>
</body>
<?php include("footer.php"); ?>
</html>