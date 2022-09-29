<?php 
//script responsabile della connessione
require "connect.php";
include("header.php");
include("finesessione.php");
//se è settato l'ID del blog restituisce i post di quel blog
if (isset($_GET['blog_id'])) {     
	//recupero l'ID del blog
    $blogID = $_GET['blog_id'];
	//eseguo una query sulla tabella post e selezioni quelli che sono contenuti in quel blog 
	$stmt = $mysqli->prepare("SELECT * FROM posts WHERE blog_id = ? ORDER BY date_time DESC");
	$stmt->bind_param('i', $blogID);
    $stmt->execute();
	$result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);	
	$stmt->close();

	$stmt = $mysqli->prepare("SELECT background, title FROM blogs WHERE id = ?");
	$stmt->bind_param('i', $blogID);
    $stmt->execute();
	$result = $stmt->get_result();
    $blog = $result->fetch_row();	
	$stmt->close();
}
else{ 
	echo "si è verificato un errore";
}; ?>

<!DOCTYPE html>
<head>

	<title><?php echo ($blog[1])?></title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<!--includo il file che contiene l'header-->

<body style="background-color: <?php echo ($blog[0]) ?>;">

	<h1>Blog: <b> <?php echo ($blog[1])?> </b> </h1> <?php 
	if(empty($posts)){ //se la variabile posts è vuota restituisco un div ?> 
		<div class="vuoto">Non è presente nessun post!</div>
<?php
	}else{ //se non lo è restituisco i post usando il costrutto foreach?>

	<div class="row mb-2">
		<?php
			foreach ($posts as $post): ?>
					<div class="card" style="width: 400px;" >
					<div class="card-body d-flex flex-column align-items-start">
					<!--stampo il titolo del post-->
					<h5 class="card-title" id="title"><?php echo $post['title'] ?></h5>
	            	<div class="post_info">
					<!--eseguo una query per selezionare l'autore del post-->
					    <p> Autore: <b><?php 
						$userid = $post['user_id'];
						$stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
						$stmt->bind_param('i', $userid);
						$stmt->execute();
						$result = $stmt->get_result();
						$username = $result->fetch_assoc();
						$stmt->close();
						echo $username['username'];
						 ?></b></p>
						 <!--eseguo una query per selezionare il titolo del blog-->
						<p> Blog: <?php 
						$bid = $post['blog_id'];
						$stmt = $mysqli->prepare("SELECT title, id FROM blogs WHERE id = ?");
						$stmt->bind_param('i', $bid);
						$stmt->execute();
						$result = $stmt->get_result();
						$blog = $result->fetch_assoc();
						$stmt->close();
						?> <a href="post.php?blog_id=<?php echo $blog['id']?>"><?php echo $blog['title'];?></a>
						  </p>

						<p>
						<!--stampo la data-->
						<?php echo date("d-m-Y, H:i:s", strtotime($post["date_time"])); ?><br>
						<!--stampo una sottostringa del corpo del post-->
						<p class="card-text"><?php echo stripslashes(substr($post['body'], 0, 100))?>...</p>
						<!--se l'utente ha inserito le immagine le stampo-->
						<?php 
						$image1 = $post['image1'];
						$image2 = $post['image2'];
						if($image1 != NULL){
			                echo("<img class='card-img-top1'  src= 'img/$image1' width='100' height='100'>");
			                } else {
				                echo "";
			                }?>
			            <?php if($image2!= NULL){
			                echo("<img class='card-img-top1'  src= 'img/$image2' width='100' height='100'> <br> <br>");
						    } else {
								echo "";
							}?>
                        </p> 
						<?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ ?>
						<a id="btp"class="btn btn-primary" id="puls" href="viewpost.php?post_id=<?php echo $post['id']; ?>">Apri il post</a>
						<?php } else {
						//se l'utente è l'autore del blog può anche modificarlo o cancellarlo
							if($post['user_id'] == $_SESSION['id']){ 
							     $postid = $post['id'] ?>
                                <a id="btp"class="btn btn-primary" id="puls" href="viewpost.php?post_id=<?php echo $post['id']; ?>">Apri</a>
								<a id="btp"class="btn btn-primary" id="puls" href="mod_post.php?post_id=<?php echo $post['id']; ?>&blog_id=<?php echo $post['blog_id']; ?>">Modifica</a>
                                <button type="button" class="btn btn-danger" name="delete" id="deletepost" value="<?php echo $postid;?>" >Cancella</button>
								<!--se è coautore può modificarlo ma non eliminarlo-->
                        <?php } else { ?>
									<!--altrimenti può solo aprirlo-->
									<a id="btp"class="btn btn-primary" id="puls" href="viewpost.php?post_id=<?php echo $post['id']; ?>">Apri</a>
								<?php } 
							}?>
					</div>
					</div>
				</div>	
				<!--chiudo il foreach-->
	        <?php endforeach ?>
		</div>
	</div>
<?php
	} ?>
</div>
<script type="text/javascript" src="ajax/deletepost.js"></script>
</body>
<br>
<!--inserisco il file relativo al footer-->
<?php include("footer.php")?>
</html>

