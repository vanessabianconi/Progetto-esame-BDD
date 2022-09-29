<?php 
//includo il file relativo alla connessione al database 
require "connect.php";
include("header.php");
include('finesessione.php');
    //eseguo una query per selezionare tutti i blog (ordinati in base alla data di creazione in ordine decrescente)
	$stmt = $mysqli->query("SELECT * FROM blogs ORDER BY date_time DESC");
	$blogs = $stmt->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<head>
    <title>Blogs</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body> 
    <h1> Blogs </h1>
	<!--controllo che sia presente almeno un blog-->
    <?php 
	if(empty($blogs)){ ?>
		<b>Non è presente nessun blog!</b>>
<?php
	}else{ ?>

	<div class="row mb-2">
		<?php
			foreach ($blogs as $blog): ?>
					<?php $blogid = $blog['id'] ?>
					<div id="blog_<?php echo $blogid?>" class="card">
					<div class="card-body d-flex flex-column align-items-start">
					<h5 class="card-title" id="title"><?php echo $blog['title'] ?></h5> <!-- stampo il titolo -->
	            	<div>
					<!--stampo l'autore del blog, eseguendo una query sulla tabella users.-->
					    <p> Autore: <b><?php
						$userid = $blog['user_id'];
						 $stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
						    $stmt->bind_param('i', $userid);
					        $stmt->execute();
                            $result = $stmt->get_result();
                            $username = $result->fetch_assoc();
							$stmt->close();
                            echo $username['username'];
                             ?></b></p> 
						<!--stampo ora e data-->
						<?php echo date("d/m/Y, H:i:s", strtotime($blog["date_time"]));
						$image = $blog['image'];
						if($image != NULL){?>
						    <img class="card-img-right flex-auto d-none d-md-block"  src="img/<?php echo $image?>" width="250" height="150"> <?php
						} else {
							echo "";
						}?>
						<p><b>Categoria:</b> <?php 
						//eseguo una query per selezionare il nome della categoria di cui fa parte il blog
						$stmt = $mysqli->prepare("SELECT name_cat FROM category, blogs WHERE category.id_cat = blogs.id_cat AND blogs.id = ?");
						$stmt->bind_param('i', $blogid);
						$stmt ->execute();
						$result  = $stmt->get_result();
						$cat = $result ->fetch_assoc();
						$stmt->close();?>
						<a href="blogcateg.php?id_cat=<?php echo $blog['id_cat']?>"><?php echo $cat['name_cat']?></a></p>
						<!--se l'utente non è loggato può solo aprire il blog-->
						<?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ ?>
						<a id="btp"class="btn btn-primary" id="puls" href="post.php?blog_id=<?php echo $blogid; ?>">Apri il blog</a>
						<?php } else {
						//se l'utente è l'autore del blog può anche modificarlo o cancellarlo
							if($userid == $_SESSION['id']){ ?>
                                <a id="btp"class="btn btn-primary" id="puls" href="open_blog.php?blog_id=<?php echo $blogid; ?>">Apri</a>
                                <a id="btp"class="btn btn-primary" id="puls" href="mod_blog.php?blog_id=<?php echo $blogid; ?>">Modifica</a>
                                <button type="button" class="btn btn-danger" name="delete" value="<?php echo $blogid;?>" id="deleteblog">Cancella</button>
								<!--se è coautore può modificarlo ma non eliminarlo-->
                            <?php } elseif($blog['co_author'] == $_SESSION['id']){ ?>
                                <a id="btp"class="btn btn-primary" id="puls" href="open_blog.php?blog_id=<?php echo $blogid; ?>">Apri</a>
                                <a id="btp"class="btn btn-primary" id="puls" href="mod_blog.php?blog_id=<?php echo $blogid; ?>">Modifica</a>
								<?php } else { ?>
									<!--altrimenti può solo aprirlo-->
									<a id="btp"class="btn btn-primary" id="puls" href="post.php?blog_id=<?php echo $blogid; ?>">Apri</a>
								<?php } 
							}?>
					</div>
					</div>
				</div>	
	        <?php endforeach ?>
		</div>
	</div>
<?php
	} 
$mysqli->close();?>
</div>
<script type="text/javascript" src="ajax/deleteblog.js"></script>
</body>
<br>
<!--include il file footer.php -->
<?php include("footer.php")?>
</html>

