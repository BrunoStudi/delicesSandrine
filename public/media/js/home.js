const img = document.getElementById('imgLogo');
const text = document.getElementById('titreText');

function homepage () {
    window.location = "homepage";
}

/* Lors du clic sur le logo ou le titre du site */
img.onclick = function () {
    homepage();
}
text.onclick = function () {
    homepage();
}
   