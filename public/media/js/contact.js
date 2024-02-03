/* Script pour appeler la page contact depuis le bouton 
contactez moi de la page d'acceuil */
const btn = document.getElementById("btn-info");

function goContact ()
{
    window.location ="contact";
}
       
btn.addEventListener("click", (e) =>
{
    e.preventDefault();
    goContact(); 
});