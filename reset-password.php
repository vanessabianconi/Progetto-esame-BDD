<?php
//includo il file header
include("header.php");
// Se l'utente non è loggato, lo rimando alla pagina di login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// includo il file di connessione
require "connect.php";

 
// Definisco e inizializzo le variabili
$new_password = $confirm_password =  "";
 
// Se il tasto è stato cliccato
if(isset($_POST['newpassword'])){
 
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $sql = "UPDATE users SET password = ? WHERE id = ?";
        
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("si", $param_password, $param_id);
        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
        $param_id = $_SESSION["id"];
        if($stmt->execute()){
            // Dopo aver aggiornato il database, uso il session_destroy e rimando alla pagina di login
            session_destroy();
            header("location: login.php");
            exit();
        } else{
            echo "Si è verificato un errore.";
        }
        // Chiudo la query
        $stmt->close();
    }
    
    // Chiudo la connessione
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Cambio Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Cambia la tua Password</h2>
        <p>Riempi questo form per modificare la tua password.</p>
        <form action="reset-password.php" method="post"> 
            <div class="form-group">
                <label>Nuova Password</label>
                <input type="password" name="new_password" id="newpass" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Conferma Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="newpassword" class="btn btn-primary" value="Invia">
                <a class="btn btn-link ml-2" href="index.php">Cancella</a>
            </div>
        </form>
    </div>    
</body>
<script>
$.validator.addMethod("passwordr",
    function(value,element){
        return this.optional(element) || /^(?=.*[A-Z])(?=.*[!@#$&*?.,-_])(?=.*[0-9])(?=.*[a-z]).{6,}$/.test(value);
    }, 'La password deve essere lunga almeno 6 caratteri, contenere una lettera maiuscola, una minuscola, un carattere speciale e un numero');
$(document).ready(function(){
    $('form').validate({
        errorElement: "b",
        rules: {
            new_password: {
                
                required: true,
                passwordr: true,
                
            },
            confirm_password: {
                required: true,
                equalTo: "#newpass",
            }
        },
        messages:{
            new_password: {
                required: "Inserisci una nuova password"
            },
            confirm_password: {
                required: "La conferma della password è obbligatoria",
                equalTo: "Le 2 password non coincidono",
            },
        },
        submitHandler: function (form) {
            form.submit();
        },
    });
});
</script>
<?php include("footer.php"); ?>
</html>