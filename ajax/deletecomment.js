$(document).ready(function(){
    $(document).on('click', '#deletecomment', function(){
        //recupero l'id del commento
        var id = $(this).data('id');
        //se l'utente conferma di voler eliminare il commento, parte la chiamata ad ajax
        if(confirm("Sei sicuro di voler eliminare il commento?")) {
            $.ajax({
                url: "deleteComment.php",
                method: "POST",
                data: {
                    comment_id: id
                },
                success: function(data){
                    //rimuovo il div che contiene il commento eliminato
                    $("#comment_"+id).remove();
                }
            });
        }
    });
});