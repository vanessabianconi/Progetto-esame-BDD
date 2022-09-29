<?php
if( isset($_SESSION['last_acted_on']) && (time() - $_SESSION['last_acted_on'] > 60*30) ){
    session_unset();     
    session_destroy();  
    session_start();
    header('Location: login.php');
}else{
    session_regenerate_id(true);
    $_SESSION['last_acted_on'] = time();
}
?>