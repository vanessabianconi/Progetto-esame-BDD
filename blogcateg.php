<?php 
require "connect.php";
include("header.php");
include("finesessione.php");
//recupero l'id della categoria tramite $_GET
$idcat = $_GET['id_cat'];
//seleziono i blog che appartengono a quella categoria
$stmt = $mysqli->prepare("SELECT * FROM blogs, category WHERE blogs.id_cat = ? AND blogs.id_cat = category.id_cat ");
$stmt-> bind_param('i', $idcat);
$stmt->execute();
$result = $stmt->get_result();
$blogs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

//selezioni il nome della categoria
$stmt1 = $mysqli->prepare("SELECT name_cat FROM category WHERE id_cat = ?");
$stmt1-> bind_param('i', $idcat);
$stmt1->execute();
$result1 = $stmt1->get_result();
$nome = $result1->fetch_assoc();
$stmt1->close();
?>
<!DOCTYPE html>
<head>
    <title><?php echo $nome['name_cat']?></title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<p>Blog della categoria: <b><?php echo $nome['name_cat']?></b></p>
<?php foreach ($blogs as $blog):?>
	<?php $blogid = $blog['id'] ?>
	<div class="card" id="blog_<?php echo $blogid?>" style="width: 18rem;" >
		<div class="card-body">
			<h5 class="card-title"><?php echo $blog['title'] ?></h5>
	        <div>
                <!--seleziono l'autore del blog-->
				<p> Autore: <b><?php
                $user_id = $blog['user_id'];
                 $stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
				$stmt->bind_param('i', $user_id);
				$stmt->execute();
                $result = $stmt->get_result();
                $username = $result->fetch_assoc();
                $stmt->close();
                echo $username['username'];
                ?></b></p>
                <!--stampo la data e l'ora-->
			    <p><?php echo date("d/m/Y, H:i:s", strtotime($blog["date_time"])); ?></p>
                <?php 
                $image = $blog['image'];
                if($image != NULL){?>
				<img class="rounded"  src="img/<?php echo $image?>" width="250" height="150">
                <?php } else {
                    echo "";
                }?>
                <br>
                <!--il base al tipo di utente (ospite, coautore, autore) i bottoni sono diversi-->
                <?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ ?>
                    <a id="btp"class="btn btn-primary" id="puls" href="post.php?blog_id=<?php echo $blog['id']; ?>">Apri</a>
          <?php } else {
                    if($blog['user_id'] == $_SESSION['id']){ ?>
                        <a id="btp"class="btn btn-primary" id="puls" href="open_blog.php?blog_id=<?php echo $blog['id']; ?>">Apri</a>
                        <a id="btp"class="btn btn-primary" id="puls" href="mod_blog.php?blog_id=<?php echo $blog['id']; ?>">Modifica</a>
                        <button type="button" class="btn btn-danger" name="delete" value="<?php echo $blog["id"];?>" id="deleteblog">Cancella</button>
              <?php } elseif($blog['co_author'] == $_SESSION['id']){ ?>
                        <a id="btp"class="btn btn-primary" id="puls" href="open_blog.php?blog_id=<?php echo $blog['id']; ?>">Apri</a>
                        <a id="btp"class="btn btn-primary" id="puls" href="mod_blog.php?blog_id=<?php echo $blog['id']; ?>">Modifica</a>
              <?php } else { ?>
                        <a id="btp"class="btn btn-primary" id="puls" href="post.php?blog_id=<?php echo $blog['id']; ?>">Apri</a>
              <?php } 
                }?>
        </div>
	</div>
</div>
<?php endforeach;
$mysqli->close();?>
</body>
<script type="text/javascript" src="ajax/deleteblog.js"></script>
<?php include ("footer.php"); ?>
</html>