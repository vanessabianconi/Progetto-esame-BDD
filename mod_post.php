<?php 
require "connect.php";
include('header.php');
include("finesessione.php");
//recupero l'id del blog e del post che voglio modificare
$postID = $_GET['post_id'];
$blogID = $_GET['blog_id'];
//seleziono il post
$stmt = $mysqli->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param('i', $postID);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
//variabili per stampare gli errori e i nuovi valori
$errortit = $errortes = $errorimg1 = $errorimg2 = "";
$titolo = $post['title'];
$testo = $post['body'];
$image1 = $post['image1'];
$image2 = $post['image2'];
$mod_titolo = $mod_testo = $immagine1 = $immagine2 = "";

//se il tasto modifica post è stato cliccato
if(isset($_POST['modifica_post'])){
    //controllo che il campo titolo non sia vuoto e che la lunghezza sia giusta
    if(empty($_POST['mod_titolo'])){
        $errortit = "<p style= 'color: red;'>" . 'Il titolo è obbligatorio'. "</p>";
    //assegno alla variabile il nuovo titolo
    } elseif(isset($_POST['mod_titolo'])){
        $mod_titolo = trim($_POST['mod_titolo']);
    } elseif (strlen($_POST['mod_titolo']) > 100){
        $errortit = "<p style= 'color: red;'>"  . "Il titolo è troppo lungo" . "</p>";
    } else {
        //altrimenti il titolo rimane quello precedente
        $mod_titolo = trim($titolo);
    }

    if(empty($_POST['mod_testo'])){
        //controllo che il testo sia vuoto e la lunghezza
        $errortes = "<p style= 'color: red;'>" . 'Il contenuto del post è obbligatorio'. "</p>";
    } elseif(isset($_POST['mod_testo'])){
        $mod_testo = trim($_POST['mod_testo']);
    } elseif (strlen($_POST['mod_testo']) > 2000){
        $errortes = "<p style= 'color: red;'>"  . "Il contenuto del post è troppo lungo" . "</p>";
    } else {
        $mod_testo = trim($testo);
    }

    //controllo se l'utente ha modificato l'immagine
    if(isset($_FILES['image1']) && !empty($_FILES['image1']['name'])){
        //controllo la dimensione
        if(filesize($_FILES['image1']['tmp_name']) < 5242880) {

            $immagine1 = $_FILES['image1']['name'];
            $immagine1_tmp = $_FILES['image1']['tmp_name'];
            $cartella = "img/";
            $tipiaccettati = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $tipoimg = exif_imagetype($immagine1_tmp);
            //controllo l'estensione
            if (!in_array($tipoimg, $tipiaccettati)) {
                $errorimg1 = "<p style= 'color: red;'>"  . "Il formato dell'immagine non è accettato" . "</p>";
            }
        } else {
            $errorimg1 = "<p style= 'color: red;'>"  . "l'immagine è troppo grande" . "</p>";
        }
        //controllo la lunghezza del nome del file
        if(strlen($_FILES['image1']['name'])>30){
            $errorimg1 = "<p style= 'color: red;'>"  . "Il nome del file è troppo lungo" . "</p>";
        }

        //se non ci sono errori sposto l'immagine nella cartella img
        if(empty($errorimg1)) {
            move_uploaded_file($immagine1_tmp, "$cartella/$immagine1");

        }
        //altrimenti l'immagine è NULL oppure quella che l'utente ha inserito al momento della creazione del post
    } else {
        if($image1 == NULL){
            $immagine1 == NULL;
        } else {
        $immagine1 = trim($image1);
        }
    }

    if(isset($_FILES['image2']) && !empty($_FILES['image2']['name'])){
        if(filesize($_FILES['image2']['tmp_name']) < 5242880) {

            $immagine2 = $_FILES['image2']['name'];
            $immagine2_tmp = $_FILES['image2']['tmp_name'];
            $cartella1 = "img/";
            $tipiaccettati1 = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $tipoimg1 = exif_imagetype($immagine2_tmp);
            if (!in_array($tipoimg1, $tipiaccettati1)) {
                $errorimg2 = "<p style= 'color: red;'>"  . "Il formato dell'immagine non è accettato" . "</p>";
            }
        } else {
            $errorimg2 = "<p style= 'color: red;'>"  . "l'immagine è troppo grande" . "</p>";
        }
        if(strlen($_FILES['image2']['name'])>30){
            $errorimg2 = "<p style= 'color: red;'>"  . "Il nome del file è troppo lungo" . "</p>";
        }
        
        if (empty($errorimg2)) {

            move_uploaded_file($immagine2_tmp, "$cartella1/$immagine2");

        }
    } else {
        if($image2 == NULL){
            $immagine2 == NULL;
        } else {
        $immagine2 = trim($image2);
        }
    }
    //se non ci sono errori aggiorno la tabella posts con i nuovi dati
    if(empty($errortit) && empty($errortes) && empty($errorimg1) && empty($errorimg2)){
        $modifica = $mysqli->prepare("UPDATE posts SET title=?, body=?, image1=?, image2=? WHERE id=?");
        $modifica->bind_param('ssssi', $mod_titolo, $mod_testo, $immagine1, $immagine2, $postID);
        if($modifica->execute()){
            header("location:open_blog.php?blog_id=$blogID");
            exit();

        }else{
            echo "Si è verificato un errore";
        }
        $modifica->close();
    }
    $mysqli->close();
    
} 
?>


<!DOCTYPE html>
<head>
    <title>Modifica post</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body> 
<div>
        <div class="col-12 px-5" style="background-color:#F0F0F0;">

            <div>
                <div>

                    <h4 class="card-title text-center"><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, modifica il tuo post</h4>

                    <form method="POST" name="creaPost" action="" enctype="multipart/form-data">
                        <div class="form-row">
                            <!--titolo-->
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="titoloCreaPost">Titolo del post:</label>
                                    <input type="text" class="form-control" id="titoloCreaPost" placeholder="Dai un titolo" value="<?php echo htmlspecialchars($titolo) ?>" name="mod_titolo">
                                </div>
                                <div><?php echo $errortit?></div>
                            </div>
                            <!--testo-->
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="testo">Contenuto del post:</label>
                                    <textarea class="form-control" id="testo" rows="10" placeholder="Testo" value="<?php echo ($testo) ?>" name="mod_testo"><?php echo ($testo) ?></textarea>
                                </div>
                                <div><?php echo $errortes?></div>
                            </div>
                            <!--immagine-->
                            <div class="col-8">
                            <label for="image">Modifica o aggiungi la prima immagine:</label>
                            <div class="custom-file">
                                <input type="file" name="image1" class="custom-file-input" id="customFile1" value="<?php echo ($immagine1) ?>">
                                <label class="custom-file-label" for="customFile" id="custfile1">
                                <?php if($image1 != NULL){
                                        echo ($image1);
                                    } else {
                                        echo("Scegli file...");
                                    }?>
                                </label>
                            </div>
                            <div><?php echo $errorimg1?></div>
                            </div>
                            <!--immagine-->
                            <div class="col-8">
                            <label for="image">Modifica o aggiungi la seconda immagine:</label>
                            <div class="custom-file">
                                <input type="file" name="image2" class="custom-file-input" id="customFile2" value="<?php echo ($immagine2) ?>">
                                <label class="custom-file-label" for="customFile" id="custfile2">
                                <?php if($image2 != NULL){
                                        echo ($image2);
                                    } else {
                                        echo("Scegli file...");
                                    }?>
                                </label>
                            </div>
                            <div><?php echo $errorimg2?></div>
                            </div>

                        </div>

                        <div class="form-group p-3">
                            <button type="submit" value="Modifica" class="btn btn-secondary" name="modifica_post">Modifica</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
$("#customFile1").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings("#custfile1").addClass("selected").html(fileName);
});
$("#customFile2").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings("#custfile2").addClass("selected").html(fileName);
});
</script>
</body>
<?php include('footer.php'); ?>
</html>