<?php
//file relativo alla connessione
require "connect.php";
//includo l'header
include("header.php");
include('finesessione.php');
//se l'utente non è loggato non può crere il blog
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    //rimando alla pagina login.php
    header("location: login.php");
    exit;
}

//seleziono le categorie del blog in ordine alfabetico
$stmt = $mysqli->query("SELECT * FROM category ORDER BY name_cat ASC");
$categorie = $stmt->fetch_all(MYSQLI_ASSOC);	


$id = $_SESSION['id'];
//selezioni gli utenti diversi dall'utente che ha fatto l'accesso 
$coautore = $mysqli->prepare("SELECT id, username FROM users WHERE id != $id");
$coautore->execute();
$result = $coautore->get_result();
$coaut = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
//inizializzo le variabili
$titolo = $image = $username = $categoria = $idBlog = $coauthor= "";
$errortit = $errorcateg = $errorimg = "";

//se è stato cliccato il tasto crea blog
if(isset($_POST['crea_blog'])){
    //controllo che i campi non siano vuoti 
    if(empty($_POST['titolo'])) {
        $errortit = 'Il titolo è obbligatorio';
    } elseif(strlen($_POST['titolo']) > 100){
        $errortit = "Il titolo è troppo lungo";
    } else {
        $titolo = trim($_POST['titolo']);
    }
    if(empty($_POST['categoria'])) {
        $errorcateg = "<p style = color:red;> Seleziona una categoria </p>";
    } else {
        $categoria = $_POST['categoria'];
    }
    if(!empty($_POST['coautore'])){
        $coauthor = $_POST['coautore'];
    } else {
        //il coautore è facoltativo: se l'utente non lo seleziona è NULL
        $coauthor = NULL;
    }
    $font = $_POST['font'];
    $background = $_POST['background'];

    if(isset($_FILES['image']) && !empty($_FILES['image']['name'])){
        //controllo la dimensione dell'immagine
        if(filesize($_FILES['image']['tmp_name']) < 5242880) {

            $immagine = $_FILES['image']['name'];
            $immagine_tmp = $_FILES['image']['tmp_name'];
            $cartella = "img/";
            $tipiaccettati = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $tipoimg = exif_imagetype($immagine_tmp);
            if (!in_array($tipoimg, $tipiaccettati)) {
                $errorimg = "Il formato dell'immagine non è accettato";
            }
        } else {
            $errorimg = "l'immagine è troppo grande";
        }

        if(strlen($_FILES['image']['name'])>30){
            $errorimg = "Il nome dell'immagine è troppo lungo";
        }
        if (empty($errorimg)) {
            //se non ci sono errori, sposto l'immagine 
            move_uploaded_file($immagine_tmp, "$cartella/$immagine");

        }
    } else {
        //se l'utente non sceglie nessuna immagine, il valore che inserisco è NULL (l'immagine è facoltativa)
        $immagine = NULL;
    }

    if(empty($errorimg) && empty($errortit) && empty($errorcateg)) {

        //se non ci sono errori, inserisco i valori nel database nella tabella blogs
        $stmt = $mysqli->prepare("INSERT INTO blogs (user_id, id_cat, title, image, co_author, font, background, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, now())");
        $stmt->bind_param('iississ', $id, $categoria, $titolo, $immagine, $coauthor, $font, $background);

        if ($stmt->execute()) {
            header("Location: myblogs.php");

        } else {

            //errore
            echo 'Non è stato posibile creare il blog!';

        }
        $stmt->close();
    }
    
    $mysqli->close();
}

?>
<!DOCTYPE html>
<head>
    <title>Crea blog</title>
    <link rel="stylesheet" href="css/style.css?ts=<?=time()?>&quot" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body> 
<div>
        <div class="col-12 px-5" style="background-color:#F0F0F0;">

            <div>
                <div>

                    <h4 class="card-title text-center"><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, crea il tuo Blog</h4>

                    <form method="POST" name="creaBlog" action="createblog.php" enctype="multipart/form-data">
                        <div>

                            <!-- titolo -->
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="titoloCreaBlog">Titolo del blog:</label>
                                    <input type="text" class="form-control <?php echo (!empty($errortit)) ? 'is-invalid' : ''; ?>" id="titoloCreaBlog" placeholder="Dai un titolo" value="<?php echo htmlspecialchars($titolo) ?>" name="titolo">
                                    <span class="invalid-feedback"><?php echo $errortit; ?></span>
                                </div>
                            </div>

                            <!-- Immagine -->
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="formFile" class="form-label">Immagine: </label>
                                    <input class="form-control <?php echo (!empty($errorimg)) ? 'is-invalid' : ''; ?>" type="file" name="image" value="<?php echo htmlspecialchars($immagine) ?>" id="formFile">
                                    <span class="invalid-feedback"><?php echo $errorimg; ?></span>
                                </div>
                            </div>


                        </div>
                        <!-- Categoria -->
                        <div class="col-8">
                        <div class="form-group">
                            <label for="categoria">Seleziona la categoria</label>
                            <select id="categblog" class="form-control" name='categoria' id='categoria'>
                                <option value="">Seleziona...</option>
                                <?php foreach ($categorie as $cat) : ?>
                                    <?php echo "<option id=" . $cat['id_cat'] . " value=" . $cat['id_cat'] . ">" . $cat['name_cat'] . "</option>"; ?>
                                <?php endforeach; ?>
                                
                            </select>
                            <span><?php echo $errorcateg; ?></span>
                                </div>
                        </div>
                        <!-- Coautore -->
                        <div class="col-8">
                        <div class="form-group">
                            <label for="coautore"> Seleziona username: </label>
	                        <select id="coautore" class="form-control" name='coautore'>
		                    <option value=""> Seleziona un coautore </option>
                                <?php foreach ($coaut as $co) : ?>
                                <?php echo "<option id=" . $co['id'] . " value=" . $co['id'] . ">" . $co['username'] . "</option>"; ?>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        </div>
                        <!-- Font -->
                        <div class="col-8">
                        <div class="form-group">
                            <label for="font">Scegli un font per il tuo blog</label>
                            <select class="form-control" id="font" name="font">
                                <option value="arial" class="arial">Arial</option>
                                <option value="bodoni" class="bodoni">Bodoni</option>
                                <option value="copperplate" class="copperplate">Copperplate</option>
                                <option value="consolas" class="consolas">Consolas</option>
                                <option value="verdana" class="verdana">Verdana</option>
                                <option value="garamond" class="garamond">Garamond</option>
                                <option value="helvetica" class="helvetica">Helvetica</option>
                                <option value="gill_sans" class="gill_sans">Gill Sans</option>
                                <option value="monaco" class="monaco">Monaco</option>
                                <option value="futura" class="futura">Futura</option>
                                <option value="georgia" class="georgia">Georgia</option>
                                <option value="optima" class="optima">Optima</option>
                                <option value="didot" class="didot">Didot</option>
                                <option value="frutiger" class="frutiger">Frutiger</option>
                            </select>
                                </div>
                        </div>
                        <!-- colore -->
                        <div class="col-8">
                        <div class="form-group">
                            <label for="background">Scegli il colore di sfondo</label>
                            <input type="color" id="background" name="background">
                        </div>
                        </div>
                        <div class="form-group p-3">
                            <button type="submit"
                                    value="Crea"
                                    class="btn btn-secondary"
                                    name="crea_blog">
                                Crea
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
<!--includo il footer-->
<?php include('footer.php'); ?>
</html>
