$(document).ready(function(){
    //totale like
    var punteggio = parseInt($("#punteggio").text());

    $(document).on('click', '.like', function(){
        //recupero l'id del post
        var id=$(this).val();
		var $this = $(this);
        //in base alla classe dell'elemento modifico il testo
		$this.toggleClass('like');
        if($this.hasClass('like')){
            $this.text('Mi piace');
        } else {
            $this.text('Non mi piace');
            $this.addClass("unlike"); 
        }
        //chiamata ad ajax per inserire il like ed aggiornare il punteggio
        $.ajax({
            url: "like.php",
            method: 'POST',
            data: {
                id: id,
                like: 1,
                punteggio: punteggio + 1,
            },
            success: function(response){
                //aggiorno il punteggio
                punteggio = punteggio + 1;
				$("#punteggio").html(punteggio);
            }
        });
    });

    $(document).on('click', '.unlike', function(){
        var id = $(this).val();
        var $this = $(this);
        $this.toggleClass('unlike');
        if($this.hasClass('unlike')){
            $this.text('Non mi piace');
        } else {
            $this.text('Mi piace');
			$this.addClass("like");
        }
        //chiamata ajax
        $.ajax({
            url: "like.php",
            method: 'POST',
            data: {
                id: id,
                like: 1,
                punteggio: punteggio - 1,
            },
            success: function(response){
                //in questo caso, il punteggio viene decrementato perchè l'utente toglie il like
                punteggio = punteggio - 1;
                $("#punteggio").html(punteggio);
            }
        });
    });
});