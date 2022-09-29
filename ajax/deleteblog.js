$(document).ready(function(){
    //l'utente clicca sul bottone cancella
    $(document).on('click', '#deleteblog', function(){
        //recupero l'id del blog dall'attributo value del bottone
        var id = $(this).val();
        //se l'utente conferma di voler eliminare il blog, parte la chiamata ad ajax
        if(confirm("Sei sicuro di voler eliminare il blog?")) {
            $.ajax({
                type: 'POST',
                url: 'deleteblog.php',
                data: {
                    id: id
                },
                success: function(data){
                    //rimuovo il div di quel blog
                    $('#blog_' + id).remove();
                    if($("#totblog").length > 0) {
						var currentCount = parseInt($("#totblog").text());
						var newCount = currentCount - 1;
						$("#totblog").text(newCount);
					}
                }
            });
        }
    })
})