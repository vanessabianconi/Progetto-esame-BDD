<?php 
//includo il file relativo alla connessione
require "connect.php";
//includo l'header
include('header.php');
include("finesessione.php");
//recupero l'id del blog tramite la variabile $_GET
$blogID = $_GET['blog_id'];
//seleziono il blog in base all'ID
$stmt = $mysqli->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param('i', $blogID);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();
$stmt->close();



//seleziono le categorie 
$stmt = $mysqli->prepare("SELECT * FROM category ORDER BY name_cat ASC");
$stmt->execute();
$result = $stmt->get_result();
$categorie = $result->fetch_all(MYSQLI_ASSOC);	
$stmt->close();

$nomeUtente = $_SESSION['id'];

//inizializzo le variabili
$mod_titolo = $immagine = $mod_categoria = $mod_coautore = $mod_font = $mod_background = "";
$errortit = $errorimg = $errorcateg = "";
$titolo = $blog['title'];
$image = $blog['image'];

//se clicco il bottone modifica blog 
if(isset($_POST['modifica_blog'])){
    //controllo che il campo non sia vuoto
    if(empty($_POST['mod_titolo'])){
        $errortit = "<p style= 'color: red;'>" . 'Il titolo è obbligatorio'. "</p>";
    } elseif(strlen($_POST['mod_titolo']) > 100){
        $errortit = "<p style= 'color: red;'>"  . "Il titolo è troppo lungo" . "</p>";
    } elseif(isset($_POST['mod_titolo'])){
        //assegno il nuovo titolo
        $mod_titolo = trim($_POST['mod_titolo']);
    }  else {
        //altrimenti non aggiorno il titolo 
        $mod_titolo = $titolo;
    }

    //controllo il campo immagine
    if(isset($_FILES['mod_image']) && !empty($_FILES['mod_image']['name'])){
        //controllo che non superi i 3MB
        if(filesize($_FILES['mod_image']['tmp_name']) < 5242880) {

            $immagine = $_FILES['mod_image']['name'];
            $immagine_tmp = $_FILES['mod_image']['tmp_name'];
            $cartella = "img/";
            //controllo estensione
            $tipiaccettati = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $tipoimg = exif_imagetype($immagine_tmp);
            if (!in_array($tipoimg, $tipiaccettati)) {
                $errorimg = "<p style= 'color: red;'>"  . "Il formato dell'immagine non è accettato" . "</p>";
            }
        } else {
            $errorimg = "<p style= 'color: red;'>"  . "l'immagine è troppo grande" . "</p>";
        }
        //controllo la lunghezza del nome del file
        if(strlen($_FILES['mod_image']['name'])>30){
            $errorimg = "<p style= 'color: red;'>"  . "Il nome del file è troppo lungo" . "</p>";
        }

        if (empty($errorimg)) {
            move_uploaded_file($immagine_tmp, "$cartella/$immagine");

        }
        //se non ha modificato l'immagine resta la stessa
    } else {
        if($image == NULL){
            $immagine == NULL;
        } else {
        $immagine = trim($image);
        }
    }

    //controllo se l'utente ha modificato la categoria
    if(empty($_POST['mod_categoria'])){
        $errorcateg = "<p style= 'color: red;'>"  . "Seleziona una categoria" . "</p>";
    }
    elseif(isset($_POST['mod_categoria'])) {
        $mod_categoria = $_POST['mod_categoria'];
    } else {
        $mod_categoria = $blog['id_cat'];
    }

    //font
    if(isset($_POST['mod_font'])){
        $mod_font = $_POST['mod_font'];
    } else {
        $mod_font = $blog['font'];
    }

    //sfondo
    if(isset($_POST['mod_background'])){
        $mod_background = $_POST['mod_background'];
    } else {
        $mod_background = $blog['background'];
    }

    //se non ci sono errori aggiorno la tabella blogs 
    if(empty($errorimg) && empty($errorcateg) && empty($errortit)){
        $modifica = $mysqli->prepare("UPDATE blogs SET title=?, image=?, id_cat=?, font=?, background=? WHERE id=?");
        $modifica->bind_param('ssissi', $mod_titolo, $immagine, $mod_categoria, $mod_font, $mod_background, $blogID);
        if($modifica->execute()){
            header("location:myblogs.php");

        }
        $modifica->close();
    }
    $mysqli->close();
    
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Modifica il blog</title>
    <style> .card {text-align:center; font-size: larger; }, #puls{text-align:center;}</style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
<h1 class="my-5">Ciao <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, stai per modificare il blog <?php echo $blog['title']; ?> </h1>
<div>
        <div class="col-12 px-5" style="background-color:#F0F0F0;">

            <div>
                <div>

                    <h4 class="card-title text-center">Cosa vuoi modificare?</h4>


                    <form method="POST" name="modificaaBlog" action="" enctype="multipart/form-data">
                        <div>

                            <!-- titolo -->
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="titoloCreaBlog">Titolo del blog:</label>
                                    <input type="text" class="form-control" id="titoloCreaBlog" placeholder="Dai un titolo" value="<?php echo htmlspecialchars($titolo) ?>" name="mod_titolo">
                                </div>
                                <div><?php echo $errortit?></div>
                            </div>

                            <!-- Immagine -->
                            <div class="col-8">
                            <label for="image">Modifica o aggiungi un'immagine:</label>
                            <div class="custom-file">
                                <input type="file" name="mod_image" class="custom-file-input" id="customFile" value="<?php echo ($immagine) ?>">
                                <label class="custom-file-label" for="customFile">
                                <?php if($image != NULL){
                                        echo ($image);
                                    } else {
                                        echo("Scegli file...");
                                    }?>
                                </label>
                            </div>
                            <div><?php echo $errorimg?></div>
                            </div>

                        </div>
                        <!--categoria-->
                        <div class="col-8">
                        <div class="form-group">
                            <label for="categoria">Modifica la categoria</label>
                            <select id="categoria" class="form-control" name='mod_categoria' id='mod_categoria' required>
                            <?php foreach ($categorie as $cat) : ?>
                                <?php if($cat["id_cat"] == $blog["id_cat"]){
                                    echo "<option value = '" . $cat["id_cat"] . "' selected>" . $cat["name_cat"] . "</option>";

                                } else { ?>
                                    <?php echo "<option id=" . $cat['id_cat'] . " value=" . $cat['id_cat'] . ">" . $cat['name_cat'] . "</option>"; 
                                } ?>
                            <?php endforeach; ?>
                                
                            </select>
                            </div>
                            <div><?php echo $errorcateg?></div>
                        </div>
                        <!--font-->
                        <div class="col-8">
                        <div class="form-group">
                            <label for="font">Scegli un font per il tuo blog</label>
                            <select class="form-control" id="font" name="mod_font" required>
                                <option value="<?php echo $blog['font']?>" class="<?php echo $blog['font']?>" selected> <?php echo $blog['font']?> </option>
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
                        <!--colore-->
                        <div class="col-8">
                        <div class="form-group">
                            <label for="background">Scegli il colore di sfondo</label>
                            <input type="color" id="background" name="mod_background" value="<?php echo $blog['background']?>">
                        </div>
                        </div>

                        <div class="form-group p-3">
                            <button type="submit" value="Modifica" class="btn btn-secondary" name="modifica_blog"> Modifica</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
</body>
<!--includo il footer-->
<?php include("footer.php"); ?>
</html>