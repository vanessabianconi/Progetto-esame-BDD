$(document).ready(function(){
    $(document).on('click', '#delete_coauthor', function(){
        //recupero l'ide del blog
        var id = $(this).data('id');
        //se l'utente conferma di voler eliminare il coautore, parte la chiamata ad ajax
        if(confirm("Sei sicuro di voler eliminare il coautore")){
            $.ajax({
                url: "delete_coautore.php",
                method: "POST",
                data: {
                    id:id
                },
                success: function(data){
                    //rimuovo l'id che contiene il nome del coautore
                    $("#coautore_").remove();
                }
            });
        }
    });
});