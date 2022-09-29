$(document).ready(function(){
    //se l'utente clicca sul bottone per eliminare aggiungere il commento
    $(document).on('click', '#addcomments', function(){
        //recupero l'id del post, l'id dell'utente e il commento
        var post_id = $(this).data('id');
        var user_id = $(this).data('user');
        var message = $("#message").val();
        if(message.length > 100){
            //se è troppo lungo stampo un errore
            $("#errorcomment").html("Il commento è troppo lungo");
            //altrimenti uso ajax per inserire il commento
        } else if(message != ''){
            $.ajax({
                url: "comments.php",
                method: "POST",
                data: {
                    post_id: post_id,
                    user_id: user_id,
                    message: message,
                },
                success: function(data){
					location.reload();
				}

            });
        }
        else {
            //se è vuoto stampo un errore
            $("#errorcomment").html("Il commento non può essere vuoto");
        }
    });
});