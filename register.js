$(document).ready(function () {
    //utilizzo il validate() di jquery per validare il form
    $("form").validate({
        errorElement: "b",
        rules: {
            nome: "required",
            cognome: "required",
            username: {
                required: true,
                minlength: 5,
                maxlength: 15
            },
            password: {
                required: true,
            },
            confirm_password: {
                required: true,
                equalTo: "#password",
            },
            id_card: {
                required:true,
                minlength: 9,
                maxlength: 9,
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                digits: true,
            }

        },
        messages: {
            nome: "Il nome è obbligatorio",
            cognome: "Il cognome è obbligatorio",
            password: {
                required: "La password è obbligatoria",
                minlength: "La password deve essere lunga almeno 6 caratteri",

            },
            confirm_password: {
                required: "La conferma della password è obbligatoria",
                equalTo: "Le 2 password non coincidono",
            },
            id_card: {
                required: "Il numero del documento è obbligatorio",
                minlength: "Il numero del documento è troppo corto",
                maxlength: "Il numero del documento è troppo lungo",
            },
            email: {
                required: "L'email è obbligatorio",
                email: "Inserisci un'email valida",
            },
            phone: {
                required: "Il numero di telefono è obbligatorio",
                rangelength: "La lunghezza del numero non è giusta",
                digits: "Inserisci solo numeri",
            },
            username: {
                required: "Il nome utente è obbligatorio",
                minlength: "Il nome utente è troppo corto (almeno 5 caratteri)",
                maxlength: "Il nome utente è troppo lungo",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }

    });
});

