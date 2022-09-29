
<?php session_start();?>
<!DOCTYPE html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css?ts=<?=time()?>&quot" type="text/css">
<script src="https://code.jquery.com/jquery-3.4.1.slim.js" integrity="sha256-BTlTdQO9/fascB1drekrDVkaKd9PkwBymMlHOiG+qLI=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<header>
  <nav class="navbar navbar-expand-lg navbar-light"  style="background-color: #e3f2fd;">  
      
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	 <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item px-2">
        <a class="nav-link"href="blog.php">Blog</a>
      </li>
      <!--controllo che l'utente sia loggato-->
    <?php if(isset($_SESSION['id'])): ?> 
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <!--stampo lo username-->
          <?php echo $_SESSION['username']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item"  href="myblogs.php">I tuoi blogs</a>
          <div class="dropdown-divider">

          </div>
          <a class="dropdown-item"  href="profile.php">Profilo</a>
          <a class="dropdown-item"  href="logout.php">Logout</a>
        </div>
      </li>
      </div>
      <!--se non è loggato, inserisco nell'header le pagine register.php e login.php-->
    <?php else : ?> 
        <li class="nav-item px-2">
            <a class="nav-link" href="register.php">Registrati</a>
        </li>
        <li class="nav-item px-2">
            <a class="nav-link" href="login.php">Accedi</a>
        </li>
    <?php endif; ?>
  </ul>

</header>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</head>
</html>


