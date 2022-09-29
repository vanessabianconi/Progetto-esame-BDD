<?php 
//includo il file relativo alla connessione
require "connect.php";
//includo l'header
include("header.php");
include('finesessione.php');
//seleziono le categorie
$stmt = $mysqli->query("SELECT * FROM category ORDER BY name_cat ASC");
$categorie = $stmt->fetch_all(MYSQLI_ASSOC);
$stmt->close();
//selezioni gli utenti
$stmt1 = $mysqli->query("SELECT * FROM users");
$username = $stmt1->fetch_all(MYSQLI_ASSOC);
$stmt1->close();
//seleziono titoli dei blog
$stmt = $mysqli->query("SELECT id, title FROM blogs");
$titoli = $stmt->fetch_all(MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<div class="menu text-center">
		<h2>Cerca blog:</h2>
	    <!--form per la ricerca del blog in base a categoria, titolo o username utente-->
		<form method="post" id="formRic" action="index.php">
			<br>
			<input type="radio" id="radio1" name="ricercaf" value="categ"/> Categoria<br>

			<input type="radio" id="radio2" name="ricercaf" value="title"/> Titolo<br>

            <input type="radio" id="radio3" name="ricercaf" value="username"/> Username<br>

			<div class="form-group" id="title">
                <input class="form-control" list="titolilista" name="keyword" value="<?php echo isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>" placeholder="Digita...">
                    <datalist id="titolilista">
                    <?php foreach ($titoli as $tit) : ?>
                    <?php echo "<option id=" . $tit['id'] . " >" . $tit['title'] . "</option>"; ?>
                    <?php endforeach; ?>
                    </datalist>
            </div>
            <div class="form-group" id="categ" >
            <select id="sceltacateg" class="form-control" name='keyword1' id='categoria'>
            <option value="">Seleziona...</option>
                <?php foreach ($categorie as $cat) : ?>
                    <?php echo "<option id=" . $cat['id_cat'] . " value=" . $cat['name_cat'] . ">" . $cat['name_cat'] . "</option>"; ?>
                <?php endforeach; ?>
                                
            </select>
            </div>
            <div class="form-group" id="user" >
            <select id="sceltauser" class="form-control" name='keyword2' id='username'>
            <option value="">Seleziona...</option>
                <?php foreach ($username as $user) : ?>
                    <?php echo "<option id=" . $user['id'] . " value=" . $user['username'] . ">" . $user['username'] . "</option>"; ?>
                <?php endforeach; ?>
                                
            </select>
            </div>
			<button type="submit" class="btn btn-secondary" name="search">Cerca</button>
        	<br>
            <br>
		</form>
</div>
<?php 

if(isset($_POST['search'])){
    $keyword = trim(htmlspecialchars($_POST['keyword']));
    $keyword1 = $_POST['keyword1'];
    $keyword2 = $_POST['keyword2'];
    //controllo che il criterio di ricerca non sia vuoto, altrimenti stampo un messaggio
    
    if(empty($_POST['ricercaf'])){
        echo("<div class='alert alert-danger' role='alert'> Seleziona criterio di ricerca!</div>");
    }else{
        $ricerca = $_POST['ricercaf'];
    //se la scelta del criterio di ricerca è la categoria, controllo che non sia vuota
        if($ricerca == "categ"){ 
            if(empty($keyword1)){
                echo("<div class='alert alert-danger' role='alert'> Scegli una categoria</div>");
            } else {?>
        <!-- se non è vuota, stampo i risultati-->
            <div class="text-center">
            <h2>Risultati:</h2>
            <p><b>Categoria cercata: </b><?php echo $keyword1; ?></p>
            <hr style="border-top:2px dotted #ccc;"/>
            <?php //eseguo unq query per selezionare i blog corrispondenti alla ricerca 
            $param = "%$keyword1%";
            $query = $mysqli->prepare("SELECT * FROM blogs, category WHERE blogs.id_cat = category.id_cat AND category.name_cat LIKE ?");
            $query->bind_param('s', $param);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows > 0){
                while($fetch = $result->fetch_array(MYSQLI_ASSOC)){
            //stampo i risultati?>
                <div style="word-wrap:break-word;">
                    <!--stampo il titolo e l'immagine-->
                    <a href="post.php?blog_id=<?php echo $fetch['id']?>"><h4><?php echo $fetch['title']?></h4></a><?php
                    if($fetch['image'] != NULL){ ?>
                        <img class="card-img-top2" src= "img/<?php echo $fetch['image']?>" width="250" height="150"><?php
                    } else {
                        echo "";
                    }?>
                    <!--stampo la categoria-->
                    <p><b>Categoria: </b><a href="blogcateg.php?id_cat=<?php echo $fetch['id_cat']?>"><?php echo $fetch['name_cat']?></a>
                </div>
                <hr style="border-bottom:1px solid #ccc;"/>
                <?php
                }
            } else {
                echo("<b>Nessun risultato per questa categoria</b>");
            }
            //chiudo la query
            $query->close();
        }?>
    </div>
    <?php
        
        
    //se la scelta è titolo
    } else if($ricerca == "title"){ 
        //controllo che l'utente abbia digitato una stringa
        if(empty($keyword)){
            echo("<div class='alert alert-danger' role='alert'> Digita una parola</div>");
        } else {?>
        <div class="text-center">
          <h2>Risultati:</h2>
          <p><b>Parola cercata: </b><?php echo $keyword; ?></p>
          <hr style="border-top:2px dotted #ccc;"/>
          <?php 
          $param = "%$keyword%";
          $query= $mysqli->prepare("SELECT * FROM blogs WHERE title LIKE ?");
          $query->bind_param('s', $param);
          $query->execute();
          $result = $query->get_result();
          if($result->num_rows > 0){
            while($fetch = $result->fetch_array(MYSQLI_ASSOC)){
              ?>
              <div style="word-wrap:break-word;">
                <a href="post.php?blog_id=<?php echo $fetch['id']?>"><h4><?php echo $fetch['title']?></h4></a><?php
                if($fetch['image'] == NULL) {
                    echo "";
                } else {?>
                    <img class="card-img-top2" src= "img/<?php echo $fetch['image']?>" width="250" height="150"><?php
                }?>
              </div>
              <hr style="border-bottom:1px solid #ccc;"/>
              <?php
            }
         } else {
            echo("<b>Nessun blog ha questo titolo</b>");
         }
            $query->close();
        }
          ?>
        </div>
        <?php
    } else if($ricerca == 'username') {
        if(empty($keyword2)){
            echo("<div class='alert alert-danger' role='alert'> Scegli uno username</div>");
        } else { ?>
            <h2> Risultati dei blog creati da <?php echo $keyword2?></h2>
            <hr style="border-top:2px dotted #ccc"/>
            <?php 
            $param = "%$keyword2%";
            $query = $mysqli->prepare( "SELECT blogs.id AS blogid, user_id, title, image, username, users.id AS userid FROM blogs, users WHERE blogs.user_id = users.id AND users.username LIKE ?");
            $query-> bind_param('s', $param);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows > 0){
                while($fetch = $result->fetch_array(MYSQLI_ASSOC)){
                ?>
                <div style="word-wrap:break-word;">
                <a href="post.php?blog_id=<?php echo $fetch['blogid']?>"><h4><?php echo $fetch['title']?></h4></a>
                <?php if($fetch['image'] == NULL){
                echo"";
                } else {?>
                <img class="card-img-top2" src= "img/<?php echo $fetch['image']?>" width="250" height="150">
                <?php } ?>
              </div>
              <hr style="border-bottom:1px solid #ccc;"/>
              <?php
               }
            } else {
                echo("<b>Questo utente non ha creato nessun blog</b>");
            }
            $query->close();
        }
    }
}
}
?>
<?php
//se l'utente non è loggato, rimando alla pagina di login o register
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ ?>
<div class="card" style="width: 40rem;">
  <div class="card-body">
    <p class="card-text">Per creare blog, post o commentare devi essere iscritto!</p>
    <a href="register.php" class="card-link">Registrati</a>
    <a href="login.php" class="card-link">Accedi</a>
  </div>
</div>
<br>

<?php } else { ?>
<h1 class="my-5">Ciao, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
<div class='d-flex align-items-center justify-content-center'>
    <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="img/homeimg/profile.webp" alt="Card image cap">
        <div class="card-body">
            <p class="card-text">Vai sul tuo profilo:</p>
            <a id='btp' class='btn btn-outline-success' id='puls' href='profile.php'>Profile</a>
        </div>
    </div>
    <div class="card" style="width: 18rem; heigth: 200rem;">
        <img class="card-img-top" src="img/homeimg/blog.webp" alt="Card image cap">
        <div class="card-body">
            <p class="card-text">Crea un nuovo blog:</p>
            <a id='btp' class='btn btn-outline-secondary' id='puls' href='createblog.php'>Crea blog</a>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
    //nascondo i div
    $("div#title").hide();
    $("div#categ").hide();
    $("div#user").hide();
    //in base alla scelta che fa l'utente mostro un div per la ricerca
    $("input[name=ricercaf]").on("click", function() {
        var selectedValue = $("input[name=ricercaf]:checked").val();
        if(selectedValue == 'categ') {
            $("div#categ").show();
            $("div#title").hide();
            $("div#user").hide();
        } else if(selectedValue == 'title') {
            $("div#title").show();
            $("div#categ").hide();
            $("div#user").hide();
            
        } else if(selectedValue == 'username') {
            $("div#title").hide();
            $("div#categ").hide();
            $("div#user").show();
        }
    });
});

</script>

<?php include("footer.php");?>