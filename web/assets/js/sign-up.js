$(".ui.form")
    .form({
        fields: {
            nom: {
                identifier: "nom",
                rules: [
                    {
                        type: "empty",
                        prompt: "Si us plau, introduïu un el vostre nom."
                    }
                ]
            },
            username: {
                identifier: "username",
                rules: [
                    {
                        type: "empty",
                        prompt: "Si us plau, introduïu un nom d’usuari."
                    }
                ]
            },
            contrasenya: {
                identifier: "contrasenya",
                rules: [
                    {
                        type: "empty",
                        prompt: "Si us plau, introduïu una contrasenya."
                    },
                    {
                        type: "length[6]",
                        prompt: "La contrasenya ha de tenir un mínim de 6 caràcters."
                    }
                ]
            }
        }
    });

document.getElementById("nom").focus();
