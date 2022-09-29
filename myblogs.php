<?php 
//file relativo alla connessione
require "connect.php";
//file header
include('header.php'); 
include('finesessione.php');
//se l'utente non è loggato, lo rimando alla pagina di login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit();
}
$user = $_SESSION['id'];
$username = $_SESSION['username'];
//selezioni i blog creati dall'utente che ha fatto l'accesso
$stmt = $mysqli->prepare("SELECT * FROM blogs WHERE user_id = ? ORDER BY date_time DESC");
$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();
$blogs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

//selezioni i blog di cui l'utente è coautore
$stmt1 = $mysqli->prepare("SELECT * FROM blogs WHERE co_author = ? ORDER BY date_time DESC");
$stmt1->bind_param('i', $user);
$stmt1->execute();
$result1 = $stmt1->get_result();
$coaut = $result1->fetch_all(MYSQLI_ASSOC);
$stmt1->close();


//conto i blog creati dall'autore
$stmt = $mysqli->prepare("SELECT count(id) as contablog FROM blogs WHERE user_id = ?");
$stmt->bind_param('i', $user);
$stmt->execute();
$result = $stmt->get_result();
$conta = $result->fetch_assoc();

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>I tuoi blog</title>
    <style> .card {text-align:center; font-size: larger; }, #puls{text-align:center;}</style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
<h1 class="my-5">Ciao, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> ecco i tuoi blogs!</h1>
<b>Numero blog creati: </b> <span id="totblog"><?php echo $conta['contablog']; ?></span> <br>
<?php 
// se la variabile $blogs non è vuota, stampo i blogs
if(empty($blogs)){ 
    echo ("<div'>Non hai creato nessun blog <a id='btp' class='btn btn-primary' id='puls' href='createblog.php' ?>Crea blog</a></div>");
} else { ?>
   <a id='btp' class='btn btn-outline-primary' id='puls' href='createblog.php'>Crea un nuovo blog</a>
   <br>
   <div class="row mb-2">
		<?php
			foreach ($blogs as $blog): ?>
					<?php $blog_id = $blog['id'] ?>
					<div class="card" id="blog_<?php echo $blog_id?>" style="width: 18rem;" >
					<div class="card-body">
					<h5 class="card-title" id="title"><?php echo $blog['title'] ?></h5>
	            	<div>
							<!--stampo la data e l'ora-->
						<p><?php echo date("d/m/Y, H:i:s", strtotime($blog["date_time"])); ?></p>
						<?php 
						$idcat = $blog['id_cat'];
						$stmt = $mysqli->prepare("SELECT name_cat FROM category WHERE id_cat = ?");
						$stmt->bind_param('i', $idcat);
						$stmt->execute();
						$result = $stmt->get_result();
						$ncat = $result->fetch_row();
						$stmt->close();?>
						<b>Categoria:</b><a href="blogcateg.php?id_cat=<?php echo $idcat?>"><?php echo $ncat[0]?></a>
						<?php 
						$image = $blog['image'];
						if($image != NULL){ ?>
						<img class="card-img-top2"  src="img/<?php echo $image?>" width="250" height="150">
						<?php } else{ 
							echo "";
						}?>
						<br>
						<br>
						<!--bottoni per aprire, modificare e cancellare il blog-->
						<a id="btp"class="btn btn-primary" id="puls" href="open_blog.php?blog_id=<?php echo $blog['id']; ?>">Apri</a>
                        <a id="btp"class="btn btn-primary" id="puls" href="mod_blog.php?blog_id=<?php echo $blog['id']; ?>">Modifica</a>
                        <button type="button" class="btn btn-danger" name="delete" value="<?php echo $blog["id"];?>" 
                        id="deleteblog">Cancella</button>
					</div>
					</div>
				</div>	
	        <?php endforeach ?>
		</div>
	</div>
<?php
	} ?>
<hr>
<b> Blog di cui sei coautore: </b>
<?php 
//stampo i blog di cui l'utente è coautore
if(empty($coaut)){ 
    echo ("<div class='vuoto'>Non sei coautore di nessun blog!</div>");
} else { ?>
   <div class="row mb-2">
		<?php
			foreach ($coaut as $co): ?>
					<?php $blog_idc = $co['id'] ?>
					<div class="card" id="blog_<?php echo $blogid_c?>" style="width: 18rem;" >
					<div class="card-body">
					<h5 class="card-title" id="title"><?php echo $co['title'] ?></h5>
	            	<div>
						<!--stampo lo username dell'autore-->
					    <p> Autore: <b><?php
						    $userid_c = $co['user_id'];
						    $stmt = $mysqli->prepare("SELECT username FROM users WHERE id = $userid_c");
					        $stmt->execute();
                            $result = $stmt->get_result();
                            $username = $result->fetch_assoc();
							$stmt->close();
                            echo $username['username']?></b></p>
						<p>
						<?php echo date("d/m/Y, H:i:s", strtotime($co["date_time"])); ?></p> </p> 
						<?php 
						$imagec = $co['image'];
						if($imagec != NULL){ ?>
						<img class="card-img-top2"  src="img/<?php echo $imagec?>" width="250" height="150">
						<?php } else{ 
							echo "";
						}?>
						<br>
						<br>
						<a id="btp"class="btn btn-primary" id="puls" href="open_blog.php?blog_id=<?php echo $co['id']; ?>">Apri</a>
                        <a id="btp"class="btn btn-primary" id="puls" href="mod_blog.php?blog_id=<?php echo $co['id']; ?>">Modifica</a>
					</div>
					</div>
				</div>	
	        <?php endforeach ?>
		</div>
	</div>
<?php
	} ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="ajax/deleteblog.js"></script>
</body>
<!--includo il footer-->
<?php include("footer.php");?>
</html>
