
<?php require "connect.php"; 
include ("header.php");
include('finesessione.php');?>


<?php 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $id = $_SESSION['id']; 
}

//recupero l'id del post che l'utente vuole visualizzare
	if (isset($_GET['post_id'])) {
		$post_id=$_GET['post_id'];
		//seleziono il post
		$stmt = $mysqli->prepare("SELECT * FROM posts WHERE id = ? ");
		$stmt->bind_param('i', $post_id);
		$stmt -> execute();
		$result = $stmt->get_result();
		$post = $result->fetch_assoc();
		$stmt->close();

		//seleziono i commenti del post 
		$stmt = $mysqli->prepare("SELECT * FROM comments WHERE post_id = ?");
		$stmt->bind_param('i', $post_id);
        $stmt->execute();
	    $result = $stmt->get_result();
		$count = $result->num_rows;
        $comments = $result->fetch_all(MYSQLI_ASSOC);
		$stmt->close();

		//conto i like
		$stmt = $mysqli->prepare("SELECT count(*) as Contalike1 FROM likes WHERE postid = ?");
		$stmt->bind_param('i', $post_id);
        $stmt -> execute();
	    $result = $stmt->get_result();
		$conta = $result->fetch_assoc();
		
	}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $post['title']?></title>
	<div class="container-fluid">
    <link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com"> 
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<style>
.like {
    background-image: url(like.png);
     background-color: hsl(0, 0%, 97%);
     background-repeat: no-repeat; 
     background-position: 4px 5px;
     border: none;           
     cursor: pointer;       
     height: 30px;          
     padding-left: 24px;    
     vertical-align: middle;
     color: hsl(0, 0%, 33%);
     border-color: hsl(0, 0%, 60%);
     -webkit-box-shadow: inset 0 1px 0 hsl(0, 100%, 100%),0 1px 0 hsla(0, 0%, 0%, .08);
     box-shadow: inset 0 1px 0 hsl(0, 100%, 100%),0 1px 0 hsla(0, 0%, 0%, .08);
  
 }
 
 .unlike {
  background-image: url(unlike.png);
     background-color: hsl(0, 0%, 97%);
     background-repeat: no-repeat; 
     background-position: 4px 6px;
     border: none;           
     cursor: pointer;       
     height: 30px;          
     padding-left: 30px;    
     vertical-align: middle;
     color: hsl(0, 0%, 33%);
     border-color: hsl(0, 0%, 60%);
     -webkit-box-shadow: inset 0 1px 0 hsl(0, 100%, 100%),0 1px 0 hsla(0, 0%, 0%, .08);
     box-shadow: inset 0 1px 0 hsl(0, 100%, 100%),0 1px 0 hsla(0, 0%, 0%, .08);
 
     }
</style>
  </head>
  <?php $blogID = $post['blog_id'];
  //selezioni il font del blog
  $stmt = $mysqli->prepare("SELECT font FROM blogs WHERE id = ?");
  $stmt->bind_param('i', $blogID);
  $stmt->execute();
  $result = $stmt->get_result();
  $font = $result->fetch_row();
  $stmt->close();
  ?>
<body class="<?php echo $font[0];?>">



<br><br>
<div class="content" >
    <?php $src1 = $post['image1'] ?> 
	<?php $src2 = $post['image2'] ?>
	<div>
			<h2 class="post-title"><?php echo stripslashes($post['title']); ?></h2>
            <br>
		<div>
			<?php echo stripslashes($post['body']); ?><br><br>
        </div>
		<!--stampo le immagini (se il valore non è NULL)-->
		<?php if($src1 != NULL){
			echo("<img src= 'img/$src1' width='250' height='150'> ");
		} else {
			echo "";
		}?> 
		<?php if($src2 != NULL){
			echo("<img src= 'img/$src2' width='250' height='150'> ");
		} else {
			echo "";
		}?> 
    </div><br>
    <div class="likepost">
    <?php
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		//se l'utente ha già inserito il like viene mostrato il bottone "non mi piace" 
        $stmt = $mysqli->prepare("SELECT count(*) as Contalike FROM likes WHERE postid = ? AND userid = ?");
		$stmt->bind_param('ii', $post_id, $id);
        $stmt -> execute();
	    $result = $stmt->get_result();
		$conta1 = $result->fetch_assoc();
        if($conta1['Contalike']>0){ ?>
            <button value="<?php echo $post_id; ?>" class="unlike">Non mi piace</button>
            <?php 
        } else { ?>
		    <button value="<?php echo $post_id; ?>" class="like">Mi piace</button>
		<?php 
        }?>
		<!--totale like del post-->
		<span id="punteggio"><?php echo $conta['Contalike1']?></span>
    <?php } else {
	?> 
	<span id="punteggio"><img src="like.png"></img> Mi piace <?php echo $conta['Contalike1']?></span>
	<div class="alert alert-warning" role="alert">Se non sei loggato non puoi mettere like!</div>
	<?php } ?>

</div>	
<br>

  <div class="comments1">	
        <h2> Commenti </h2>
		<hr style="border-top: 1px dotted red;"><br>	

    <!--stampo i commenti-->
	<?php foreach ($comments as $comment){?>
	<div id="comment_<?php echo $comment['id']; ?>">
         <div class="textcomment">
		 <?php $stmt = $mysqli->prepare("SELECT username FROM users, comments WHERE comments.post_id = ? AND comments.user_id = users.id AND users.id = ?");
		 $stmt->bind_param("ii", $post['id'], $comment['user_id']);
		 $stmt->execute();
		 $result = $stmt->get_result();
		 $username = $result->fetch_row();
		 $stmt->close();?>
		   <h3>commento scritto da:  <?php echo $username['0']?></h3><br>
		   <p style="border: 1px solid red; border-radius: 10px; width: 50%;" type="text" name="comment" ><?php echo stripslashes($comment['textc']);?></p>
		   <span><?php echo date("d/m/Y, H:i:s", strtotime($comment["date_time"])); ?></span>
		   <br> 
		   <br>
		 </div>
		 <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		    if($_SESSION['id'] == $comment['user_id'] ){ ?>
		    <button type="button" data-id="<?php echo $comment['id']?>" class="btn btn-outline-danger" name="delete" id="deletecomment">Cancella commento</button>
	    <?php }
		}?>
		  

		 
	</div>
	<?php 
   } ?>
	<?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    //se l'utente non è loggato, stampo la seguente stringa
    echo "Se non sei loggato non puoi commentare <a id='btp' class='btn btn-outline-secondary' id='puls' href='login.php'>Login</a>";
    exit;
} else { ?>
	<div class="commentnew">	
    <h4> Scrivi un commento </h4>
	<br>
	    
	    <div id="errorcomment" style="color:red"></div>
	    <textarea class="form-control-sm" id="message" name="message" rows="4"></textarea> <button type="button" id="addcomments" data-id="<?php echo $post_id?>" data-user="<?php echo $id?>" class="btn btn-primary" name="commentapost">Scrivi</button><br>
	<br><hr>
</div>
<?php } ?>
    

 </div>
<script type="text/javascript" src="ajax/like.js"></script>
<script type="text/javascript" src="ajax/deletecomment.js"></script>
<script type="text/javascript" src="ajax/addcomments.js"></script>
 </body>
 <?php include("footer.php"); ?>
 </html>
