<?php 
//includo il file relativo alla connessione
require "connect.php";
//includeo il file header
include("header.php"); 
include("finesessione.php");

//controllo che, tramite la GET, sia stato recuperato l'ID del blog
if (isset($_GET['blog_id'])) {     
    $blog_id = $_GET['blog_id'];
	//seleziono il blog
	$stmt = $mysqli->prepare("SELECT * FROM blogs WHERE id = ?");
	$stmt->bind_param('i', $blog_id);
    $stmt->execute();
	$result = $stmt->get_result();
    $blog = $result->fetch_assoc();	
	$stmt->close();

	//seleziono i post di quel blog
    $stmt1 = $mysqli->prepare("SELECT * FROM posts WHERE blog_id = ?");
	$stmt1->bind_param('i', $blog_id);
    $stmt1->execute();
	$result1 = $stmt1->get_result();
    $posts = $result1->fetch_all(MYSQLI_ASSOC);	
	$stmt1->close();
} else {
    echo 'Si è verificato un errore';
}

//recupero l'id dell'utente 
$user = $_SESSION['id'];
//recupero l'id e lo username con id diverso dall'utente che ha creato il blog
$coautore = $mysqli->prepare("SELECT id, username FROM users WHERE id != ?");
$coautore-> bind_param('i', $user);
$coautore->execute();
$result3 = $coautore->get_result();
$coaut = $result3->fetch_all(MYSQLI_ASSOC);
$coautore->close();

$errorcoaut = "";
//se l'utente clicca il bottone per aggiungere un coautore
if(isset($_POST['aggiungi'])){
	//controllo che abbia selezionato un nome utente
	if(empty($_POST['addcoautore'])){
		$errorcoaut = "<p style='color:red;'> Scegli un coautore </p>";
	} else {
	    $coautoreB = $_POST['addcoautore'];
	}
	if(empty($errorcoaut)){
		//aggiorno la tabella blogs e inserisco il coautore
	    $inserisci = $mysqli->prepare("UPDATE blogs SET co_author = ? WHERE id=?");
        $inserisci->bind_param("ii", $coautoreB,$blog_id);
        if($inserisci->execute()){
		    header("Location: open_blog.php?blog_id=$blog_id");
			exit();
	    } else {
			echo 'Si è verificato un errore';
		}
	    $inserisci->close();
	}
}
?>
<!DOCTYPE html>
<head>
    <title><?php echo $blog['title'] ?></title>
	<style> .card {text-align:center; font-size: larger; } </style>
    <!--link rel="stylesheet" href="css/style.css" type="text/css"-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<br>
<body class="<?php echo $blog['font']?>"> 
<h4 class="text-capitalize display-3 text-center m-0"><?php echo stripslashes($blog['title']); ?></h4>
<!--stampo l'immagine se è stata inserita dall'utente-->
<?php if($blog['image'] != NULL){ ?>
    <img src="img/<?php echo $blog['image']?>" width="300"/>
<?php } else {
	    echo "";
	   }?>
<br><br>
<!--seleziono il nome del coautore (se l'utente lo ha nominato)-->
<?php if($blog['co_author'] != NULL){ 
	$coautoreblog = $blog['co_author'];
	$stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
	$stmt->bind_param('i', $coautoreblog);
   $stmt->execute();
   $result = $stmt->get_result();
   $username = $result->fetch_assoc();
   $stmt->close();?>
	<div id="coautore_">
		<p><b>Coautore del blog: </b> <?php echo $username['username']?>
		<?php if($blog['user_id'] == $user){ ?>
		<button type="button" class="btn btn-outline-danger" name="delete" id="delete_coauthor" data-id="<?php echo $blog_id;?>">Cancella </button>
		<?php } ?> </p>
    </div>
<?php }  else{ ?>
		<!--form per inserire il coautore-->
		<button type="button" class="btn btn-primary" id="aggiungi">Aggiungi coautore</button>
		<div id="addcoautore">
		<form method="POST" id="aggiungicoautore">
			<div id="errorcoaut" style="color:red"></div>
		<label for="coautore"> Seleziona username: </label>
	        <select id="coautore" class="form-control" name='addcoautore'>
		        <option value=""> Seleziona un coautore </option>
                    <?php foreach ($coaut as $co) : ?>
                        <?php echo "<option id=" . $co['id'] . " value=" . $co['id'] . " >" . $co['username'] . "</option>"; ?>
                    <?php endforeach; ?>
            </select>
			<button type="submit" class="btn btn-secondary" id="add_coauthor" name="aggiungi" value="<?php echo $blog_id?>">Aggiungi </button>
		</form>

        </div> 
		<div><?php echo $errorcoaut?></div>
    <?php
    } ?> 
<?php 
if(empty($posts)){ ?>
    <div class='card'>
	<div class='card-body'>
	  <p class='card-text' id="nopost">Non hai creato nessun post.</p> 
	  <a href='create_post.php?blog_id=<?php echo $blog_id; ?>' class='btn btn-outline-primary'>Crea post</a>
	</div>
    </div>
	<?php 
} else { ?>
	<div class='card'>
	<div class='card-body'>
	  <p class='card-text' id="creanuovo">Crea un nuovo post</p>
	  <a href='create_post.php?blog_id=<?php echo $blog_id; ?>' class='btn btn-outline-primary'>Crea post</a>
	</div>
    </div>
	<div class="row mb-2" style="background-color: <?php echo ($blog['background']) ?>;">
   <?php
   //stampo i post del blog
foreach ($posts as $post): ?>
	<?php $userID = $post['user_id'] ?>
	<?php $postID = $post['id'] ?>
	<?php $src = $post['image1'] ?>
	<?php $src1 = $post['image2'] ?>
	<div class="card" id="post_<?php echo $postID?>" style="width: 300px; background-color: <?php echo ($blog['background']) ?>;">
	<div class="card-body">
	<h5 class="card-title" id="title"><?php echo $post['title'] ?></h5>
    <div class="post_info">
	<!--stampo l'autore del post-->
	<p> Autore: <b><?php $stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
	$stmt->bind_param('i', $userID);
	$stmt->execute();
	$result = $stmt->get_result();
	$username = $result->fetch_row();
	$stmt->close();
	echo $username[0];?></b></p>
		<p>
		<!--stampo data e ora del post-->
		<?php echo date("d-m-Y, H:i:s", strtotime($post["date_time"])); ?><br>
        </p> 
		<!--seleziono una sottostringa del testo del post-->
		<p class="card-text"><?php echo stripslashes(substr($post['body'], 0, 100))?>...</p>
		<!--se ci sono, stampo le immagini-->
		<?php if($src != NULL){
			echo("<img class='card-img-top2'  src= 'img/$src' width='100' height='100'>");
			} else {
				echo "";
			}?>
			<?php if($src1!= NULL){
			echo("<img class='card-img-top2'  src= 'img/$src1' width='100' height='100'> <br>
				<br>");
			} else {
				echo "<br> <br>";
			}?>
			<!--bottoni per aprire, modificare o eliminare i post-->
            <a id="btp"class="btn btn-primary" id="puls" href="viewpost.php?post_id=<?php echo $post['id']; ?>">Apri</a>
			<a id="btp"class="btn btn-primary" id="puls" href="mod_post.php?post_id=<?php echo $post['id']; ?>&blog_id=<?php echo $blog_id; ?>">Modifica</a>
			<?php if($post['user_id'] == $_SESSION['id'] || $_SESSION['id'] == $blog['user_id']){?>
			<button type="button" class="btn btn-danger" name="delete" id="deletepost" value="<?php echo $postID;?>" >Cancella</button>
		<?php } ?>
			</div>
			</div>
			</div>
<?php endforeach ?>
	</div>
<?php
	} ?>
</div>
</div>
<script type="text/javascript" src="ajax/deletepost.js"></script>
<script type="text/javascript" src="ajax/delcoautore.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
		$("#addcoautore").hide();
		$("#aggiungi").click(function(){
			$("#addcoautore").toggle();
			var color=$("#addcoautore").is(':hidden') ? '#FF0' : 'white';
        });
	});
</script>
</body>
</html>
<?php include("footer.php");?>

            






