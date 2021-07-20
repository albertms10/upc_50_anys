$(".ui.form")
    .form({
        fields: {
            email: {
                identifier: "username",
                rules: [
                    {
                        type: "empty",
                        prompt: "Si us plau, introduïu un nom d’usuari."
                    }
                ]
            },
            password: {
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

let usernameInput = document.getElementById("username");
if (usernameInput.value.length === 0) {
    usernameInput.focus();
} else {
    document.getElementById("contrasenya").focus();
}
