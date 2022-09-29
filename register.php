<?php
// Include il file relativo alla connessione
require "connect.php";
 
// Definisco le variabili e le inizializzo
$username = $idcard = $email = $nome = $phone = $cognome = $password = $confirm_password = $confirm_password_err = "";
$username_err = $idcard_err = $email_err = $password_err = $phone_err =  "";

// Se l'utente clicca il tasto per registrarsi
if(isset($_POST['register'])){
    //controllo che lo username possa essere usato
    if(isset($_POST['username'])){
        if(!preg_match("/^[a-z0-9]+$/", $_POST['username'])){
            $username_err = "Usa solo lettere minuscole e numeri";
        }
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $username = $_POST['username'];
            $stmt->bind_param("s", $username);
            
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $username_err = "Questo username è già in uso.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Si è verificato un errore.";
            }

            $stmt->close();
        }
    }
    //controllo che l'email non sia già stata usata
    if(isset($_POST['email'])){
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $email);
            $email = trim($_POST["email"]);
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "Questo indirizzo email è già in uso.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Si è verificato un errore.";
            }
            $stmt->close();
        }
    }

    //controllo che il numero del documento non sia stato usato
    if(isset($_POST['id_card'])){
        if(!preg_match("/^([A-Z]{2})([0-9]{5})([A-Z]{2})$/", $_POST['id_card'])){
            $idcard_err = "Il formato non è giusto";
        }
        $sql = "SELECT id FROM users WHERE id_card = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $idcard);
            $idcard = trim($_POST["id_card"]);
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $idcard_err = "Questo documento è già in uso.";
                } else{
                    $idcard = trim($_POST["id_card"]);
                }
            } else{
                echo "Si è verificato un errore.";
            }
            $stmt->close();
        }
    }

    if(isset($_POST['password'])){
        if(!preg_match("/^(?=.*[A-Z])(?=.*[!@#$&*?.,-_])(?=.*[0-9])(?=.*[a-z]).{6,}$/", $_POST['password'])){
            $password_err = "La password deve contenere almeno 6 caratteri di cui almeno 1 lettera minuscola, una maiuscola, un numero e un carattere speciale";
        } else {
            $password = $_POST['password'];
        }
    }

    if(isset($_POST['confirm_password'])){
        if($_POST['confirm_password'] != $_POST['password']){
            $confirm_password_err = "Le due password non coincidono";
        }
    }

    if(isset($_POST['phone'])){
        if(!preg_match("/^[03]\d{8,10}$/", $_POST['phone'])){
            $phone_err = "Il formato del telefono non è giusto";
        } else {
            $phone = trim($_POST['phone']);
        }
    }
    
    //recupero il nome e il cognome
    $nome = trim($_POST["nome"]);
    $cognome = trim($_POST["cognome"]);
    //se non ci sono errori inserisco i dati nella tabella utenti 
    if(empty($email_err) && empty($username_err) && empty($idcard_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err)){
    $sql = "INSERT INTO users (username, email, name, last_name, phone, id_card, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            
            $stmt->bind_param("sssssss", $username, $email, $nome, $cognome, $phone, $idcard, $param_password);
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
            if($stmt->execute()){
                
                header("location: login.php");
            } else{
                echo "Si è verificato un errore.";
            }

            
            $stmt->close();
        }
    }
}  
    $mysqli->close();
?>
 
<!DOCTYPE html>
<html lang="it">
<head>
<?php include("header.php");?>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!--script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registrati</h2>
        <p>Completa il form per creare un account</p>
        <form action="register.php" method="post" name="form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email;?>" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?> "placeholder="es: mario.rossi@ciao.it">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id= "nome" name="nome" class="form-control" value="<?php echo $nome; ?>">
            </div>
            <div class="form-group">
                <label for="cognome">Cognome</label>
                <input type="text" name="cognome" class="form-control" value="<?php echo $cognome; ?>">
            </div>
            <div class="form-group">
                <label for="phone">Telefono</label>
                <input type="tel" id="phone" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                <span class="invalid-feedback"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <label>Numero carta d'identità</label>
                <input type="text" name="id_card" id="id_card" class="form-control <?php echo (!empty($idcard_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $idcard; ?>" placeholder="es: AT45678ER">
                <span class="invalid-feedback"><?php echo $idcard_err; ?></span>
            </div>   
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                <span id="usercheck"></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?> " value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Conferma la Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?> " value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" id="submit" name="register" class="btn btn-primary" value="Registrati">
                <input type="reset" class="btn btn-secondary ml-2" value="Annulla">
            </div>
            <p>Hai già un account? <a href="login.php">Accedi</a>.</p>
        </form>
    </div>    
</body>
<script type="text/javascript" src="register.js"></script>
<?php include("footer.php"); ?>
</html>