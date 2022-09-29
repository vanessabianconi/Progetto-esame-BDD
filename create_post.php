<?php 
//script responsabile della connessione
require "connect.php";
//includo l'header
include("header.php");
include('finesessione.php');
//se l'utente non è loggato, rimando alla pagina login.php
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//recupero l'id dell'utente
$id = $_SESSION['id'];
//inizializzo le variabili 
$titolo = $testo = $image1 = $image2 = $username = $idBlog = "";
$errortit = $errortes = $errorimg1 = $errorimg2 = "";

//controllo che l'id del blog 
if(isset($_GET['blog_id'])){
    $idBlog = $_GET['blog_id']; 
    $_SESSION['blog_id'] = $idBlog;
}

//se è stato schiacciato il bottone crea_post
if(isset($_POST['crea_post'])) {
    $idBlog = $_SESSION['blog_id'];
    //se il titolo è vuoto o troppo lungo stampo un messaggio di errore altrimenti assegno alla variabile $titolo il contenuto di $_POST['titolo']
    if(empty($_POST['titolo'])) {
        $errortit = "<p style= 'color: red;'>" . 'Il titolo è obbligatorio'. "</p>";
    } elseif (strlen($_POST['titolo']) > 100){
        $errortit = "<p style= 'color: red;'>"  . "Il titolo è troppo lungo" . "</p>";

    }else {
        $titolo = trim($_POST['titolo']);
    }
    //se il corpo del post è vuoto o se è troppo lungo stampo un messaggio di errore altrimenti assegno alla variabile $testo il contenuto di $_POST['testo']
    if(empty($_POST['testo'])) {
        $errortes = "<p style= 'color: red;'>" . 'Il contenuto del post è obbligatorio'. "</p>";
    } elseif (strlen($_POST['testo']) > 2000){
        $errortes = "<p style= 'color: red;'>"  . "Il testo è troppo lungo" . "</p>";
    }  else {
        $testo = trim(htmlspecialchars($_POST['testo']));
    } 
    
    //controllo che il campo immagine non sia vuoto
    if(isset($_FILES['image1']) && !empty($_FILES['image1']['name'])){
        if(filesize($_FILES['image1']['tmp_name']) < 5242880) {

            $immagine1 = $_FILES['image1']['name'];
            $immagine_tmp1 = $_FILES['image1']['tmp_name'];
            $cartella = "img/";
            $tipiaccettati = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_WEBP);
            $tipoimg = exif_imagetype($immagine_tmp1);
            //controllo l'estensione dell'immagine
            if (!in_array($tipoimg, $tipiaccettati)) {
                $errorimg1 = "<p style= 'color: red;'>"  . "Il formato dell'immagine non è accettato" . "</p>";
            }
        } else {
            $errorimg1 = "<p style= 'color: red;'>"  . "L'immagine è troppo grande" . "</p>";
        }
        //controllo la lunghezza del nome dell'immagine
        if(strlen($_FILES['image1']['name'])>30){
            $errorimg1 = "<p style= 'color: red;'>"  . "Il nome dell'immagine è troppo lungo" . "</p>";
        }
        if (empty($errorimg1)) {
            move_uploaded_file($immagine_tmp1, "$cartella/$immagine1");

        }
    } else{
        //se l'utente non sceglie nessuna immagine, il valore è NULL
        $immagine1 = NULL;
    }

    if(isset($_FILES['image2']) && !empty($_FILES['image2']['name'])){
        if(filesize($_FILES['image2']['tmp_name']) < 5242880) {

            $immagine2 = $_FILES['image2']['name'];
            $immagine_tmp2 = $_FILES['image2']['tmp_name'];
            $cartella = "img/";
            $tipiaccettati = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_WEBP);
            $tipoimg = exif_imagetype($immagine_tmp2);
            if (!in_array($tipoimg, $tipiaccettati)) {
                $errorimg2 = "<p style= 'color: red;'>"  . "Il formato dell'immagine non è accettato" . "</p>";
            }
        } else {
            $errorimg2 = "<p style= 'color: red;'>"  . "L'immagine è troppo grande" . "</p>";
        }
        if(strlen($_FILES['image2']['name'])>30){
            $errorimg2 = "<p style= 'color: red;'>"  . "Il nome dell'immagine è troppo lungo" . "</p>";
        }
        if (empty($errorimg2)) {
            move_uploaded_file($immagine_tmp2, "$cartella/$immagine2");


        }
    } else {
        $immagine2 = NULL;
    }

    //se non ci sono errori inserisco i valori nel database
    if(empty($errortit) && empty($errortes) && empty($errorimg1) && empty($errorimg2)) {


        $stmt = $mysqli->prepare("INSERT INTO posts (blog_id, user_id, title, body, image1, image2, date_time) VALUES (?, ?, ?, ?, ?, ?, now())");
        $stmt->bind_param('iissss', $idBlog, $id, $titolo, $testo, $immagine1, $immagine2);

        if($stmt->execute()) {
            $idBlog = $_SESSION['blog_id'];
            header("Location: open_blog.php?blog_id=$idBlog");
        } else {

            echo 'Errore nella creazione del post';

        }
        $stmt->close();
    }

    $mysqli->close();
}

?>

<!DOCTYPE html>
<head>
    <title>Crea post</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body> 
    <div>
        <div class="col-12 px-5" style="background-color:#F0F0F0;">

            <div>
                <div>
                    <h4 class="card-title text-center"><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, crea il tuo post</h4>

                    <form method="POST"
                          name="creaPost"
                          action="create_post.php?blog_id=<?php echo $idBlog?>"
                          enctype="multipart/form-data"
                          novalidate>
                        <div class="form-row">

                            <!-- titolo -->
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="titoloCreaPost">Titolo del post:</label>
                                    <input type="text" rows="10" class="form-control" id="titoloCreaPost"placeholder="Dai un titolo" value="<?php echo ($titolo) ?>" name="titolo">
                                </div>
                                <div><?php echo $errortit?></div>
                            </div>

                            
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="contenutopost">Contenuto del post:</label>
                                    <textarea class="form-control" id="contenutopost" rows="10" placeholder="Testo" value="<?php echo ($testo) ?>" name="testo"></textarea>
                                    <div><?php echo $errortes?></div>
                                </div>
                            </div>

                            <!-- Immagini -->
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="formFile" class="form-label">Prima immagine: </label>
                                    <input class="form-control" type="file" name="image1" value="<?php echo ($immagine1) ?>" id="formFile">
                                </div>
                                <div><?php echo $errorimg1?></div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="formFile" class="form-label">Seconda immagine: </label>
                                    <input class="form-control" type="file" name="image2" value="<?php echo($immagine2) ?>" id="formFile">
                                </div>
                                <div><?php echo $errorimg2?></div>
                            </div>

                        </div>

                        <div class="form-group p-3">
                            <button type="submit" value="Crea" class="btn btn-secondary" name="crea_post">Crea</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
<?php include('footer.php'); ?>
</html>