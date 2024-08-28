// uploadProfile.js
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.querySelector('input[name="profileImg"]');
    const preview = document.getElementById('imagePreview');

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Affiche l'image de prévisualisation
            };

            reader.readAsDataURL(file); // Lire le fichier comme une URL de données
        } else {
            preview.src = '';
            preview.style.display = 'none'; // Cacher l'image si aucun fichier n'est sélectionné
        }
    });
});