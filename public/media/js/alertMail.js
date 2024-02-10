const form = document.querySelector("form");
const btnCancel = document.getElementById("btn-retour");
            
function sendEmail()
{
    // Récupération des champs rempli par l'utilisateur.
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const sujet = document.getElementById('subject').value;
    const message = document.getElementById('message').value;

    // verifier si les champs ne sont pas vides.
    if((name == "" || email == "" || sujet == "" || message == "")) 
    {
        Swal.fire({
            title: "Erreur",
            text: "Un ou plusieurs champs sont vides",
            icon: "error"
        });
    }
    else
    
    // Sinon on envoi le message "avec success".
    {
        /* ici on peu inserer une fonction pour envoyer réellement un email suivi du message. */
        Swal.fire({
            title: "Message envoyé",
            text: "Merci de m'avoir contacté, je vous recontacterai au plus vite sur l'adresse email renseignée.",
            icon: "success"
        });

        // ici un delais de 5 secondes afin de recharger la page d'acceuil.
        setTimeout( () => {
        window.location ="homepage";  
        }, 5000);
    }
}

// action dés que submit est invoqué.
form.addEventListener("submit", (e) =>
{
    e.preventDefault();
    sendEmail(); 
});

function goHome () {
    window.location ="homepage";
};

/* Lors du clic sur le bouton avec id "btnCancel" */
btnCancel.onclick = function () {
    goHome();
};