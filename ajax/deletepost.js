$(document).ready(function(){
    $(document).on('click', '#deletepost', function(){
        //recupero l'id del post
        var id = $(this).val();
        //se l'utente conferma di voler eliminare il post, parte la chiamata ad ajax
        if(confirm("Sei sicuro di voler eliminare il post?")){
            $.ajax({
                url: "delete_post.php",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    //rimuovo il div che contiene il post eliminato
                    $('#post_' + id).remove();
                }
            });
        }
    });
});