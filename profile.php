<?php 
require "connect.php";
include("header.php");
include("finesessione.php");


if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Profilo</title>
    <style> .card {text-align:center; font-size: larger; } </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
<h1 class="my-5">Ciao, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Modifica il nome utente</h5>
    <p class="card-text">Cliccando sul bottone, potrai modificare il nome utente.</p>
    <a href="modusername.php" class="btn btn-outline-secondary">Modifica username</a>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Cambia la password</h5>
    <p class="card-text">Cliccando sul bottone, potrai modificare la password.</p>
    <a href="reset-password.php" class="btn btn-outline-secondary">Modifica la password</a>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Esci dal tuo profilo:</h5>
    <a href="logout.php" class="btn btn-outline-dark">Logout</a>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Cancella il profilo</h5>
    <p class="card-text">Cliccando sul bottone, cancellerai il tuo profilo.</p>
    <a href="deleteprof.php" class="btn btn-outline-danger">Cancella</a>
  </div>
</div>
</body>
<?php include("footer.php");?>
</html>