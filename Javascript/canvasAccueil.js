let media380 = window.matchMedia("(max-width: 380px)");


const canvas = document.getElementById('canvasHeader');
const ctx = canvas.getContext('2d');

const buttons = document.querySelectorAll(".btn");
console.log(buttons);
const slides = document.querySelectorAll(".slide");
console.log(slides);

//DESSIN VECTORIEL : 
function onload() {

// Assurez-vous que la résolution du dessin correspond à la taille réelle du canvas
canvas.width = canvas.offsetWidth;
canvas.height = canvas.offsetHeight;
// Définir le nombre de points
if (media380.matches){
    var numPoints = 80;
} else{
    var numPoints = 200;
}

// Créer un tableau pour contenir les points
let points = [];

// Remplir le tableau de points avec des points aléatoires dans le canevas
for (let i = 0; i < numPoints; i++) {
    points.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        vx: (Math.random() - 0.1) * 0.2, // Vitesse en x
        vy: (Math.random() - 0.1) * 0.2, // Vitesse en y
    });
}

// Dessiner des lignes entre chaque point qui est à une certaine distance des autres
ctx.strokeStyle = 'rgba(255, 255, 255,0.3)'; // Couleur des lignes : noir avec une transparence de 10%
ctx.lineWidth = 0.4; // Épaisseur des lignes : 0.5 pixel
for (let i = 0; i < numPoints; i++) {
    for (let j = i + 1; j < numPoints; j++) {
        const dx = points[i].x - points[j].x;
        const dy = points[i].y - points[j].y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < 100) { // Vous pouvez ajuster cette valeur selon vos besoins
            ctx.beginPath();
            ctx.moveTo(points[i].x, points[i].y);
            ctx.lineTo(points[j].x, points[j].y);
            ctx.stroke();
        }
    }
}

// Dessiner un petit cercle blanc à chaque point
ctx.fillStyle = 'rgba(255, 255, 255, 0.5)'; // Couleur de remplissage : blanc avec une transparence de 50%
for (let i = 0; i < numPoints; i++) {
    ctx.beginPath();
    ctx.arc(points[i].x, points[i].y, 1.5, 0, 2 * Math.PI); // Dessiner un cercle de rayon 1.5
    ctx.fill();
}

let mouseX = 0, mouseY = 0;
canvas.addEventListener('mousemove', function(event) {
    const rect = canvas.getBoundingClientRect();
    mouseX = event.clientX - rect.left;
    mouseY = event.clientY - rect.top;
});

function animate() {
    
    // Effacer le canevas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    // Ajouter un rayon pour la zone de la souris
    let mouseRadius = 100;

    // Modifier la partie du code qui déplace les points
    for (let i = 0; i < numPoints; i++) {
        const dx = points[i].x - mouseX;
        const dy = points[i].y - mouseY;
        const distance = Math.sqrt(dx * dx + dy * dy);

    // Si le point est à l'intérieur de la zone de la souris, le repousser
    if (distance < mouseRadius) {
        const angle = Math.atan2(dy, dx);
        points[i].x = mouseX + mouseRadius * Math.cos(angle);
        points[i].y = mouseY + mouseRadius * Math.sin(angle);
    }
    }
    // Dessiner un petit cercle blanc à chaque point
    ctx.fillStyle = 'rgba(255, 255, 255, 0.9)'; // Couleur de remplissage : blanc avec une transparence de 50%
    for (let i = 0; i < numPoints; i++) {
        ctx.beginPath();
        ctx.arc(points[i].x, points[i].y, 1.5, 0, 2 * Math.PI); // Dessiner un cercle de rayon 1.5
        ctx.fill();
    }

    // Déplacer chaque point
    for (let i = 0; i < numPoints; i++) {
        points[i].x += points[i].vx;
        points[i].y += points[i].vy;

        // Rebondir sur les bords du canevas
        if (points[i].x < 0 || points[i].x > canvas.width) points[i].vx = -points[i].vx;
        if (points[i].y < 0 || points[i].y > canvas.height) points[i].vy = -points[i].vy;
    }

    // Dessiner des lignes entre chaque point qui est à une certaine distance des autres
    for (let i = 0; i < numPoints; i++) {
        for (let j = i + 1; j < numPoints; j++) {
            const dx = points[i].x - points[j].x;
            const dy = points[i].y - points[j].y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 150) { // Vous pouvez ajuster cette valeur selon vos besoins
                ctx.beginPath();
                ctx.moveTo(points[i].x, points[i].y);
                ctx.lineTo(points[j].x, points[j].y);
                ctx.stroke();
            }
        }
    }
    // Demander la prochaine frame d'animation
    requestAnimationFrame(animate);
}

// Commencer l'animation
animate();
}

//FIN DESSIN VECTORIEL


let i = 0;
let j = 0;
let direction = 1;
let txtArray = ['Bonjour', 'Hi', 'Hello World,']; // Le tableau des textes à afficher
let titreH1 = 'Vous êtes à la recherche d\'un développeur web ?'; // Le nouveau texte à afficher
let speed = 800; // Vitesse de dactylographie en millisecondes
// document.getElementById("hello").style.fontSize = "90px";
function typeWriter() {
  if (i < txtArray.length) {
    if (direction === 1 && j <= txtArray[i].length) {
      // Ajouter un caractère
      document.getElementById("hello").innerHTML = txtArray[i].substring(0, j);
      j++;
    } else if (direction === -1 && j >= 0) {
      // Effacer un caractère
      document.getElementById("hello").innerHTML = txtArray[i].substring(0, j);
      j--;
    }

    // Si tout le mot a été affiché, commencer à effacer
    if (j > txtArray[i].length && i < txtArray.length - 1) {
      direction = -1;
      speed = 100; // Vitesse d'effacement en millisecondes
    }

    // Si tout le mot a été effacé, passer au mot suivant
    if (j < 0) {
      direction = 1;
      speed = 300; // Vitesse de dactylographie en millisecondes
      i++;
    }

    // Appeler typeWriter() pour le prochain caractère ou mot
    setTimeout(typeWriter, speed);
}
}
// Appeler la fonction
typeWriter();

