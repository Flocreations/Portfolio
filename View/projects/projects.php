<?php
session_start();
//Import BDD 
require_once "../../service/db_connect.php";

//Preparation requete pour afficher tout les projects de la BDD 
$request = 'SELECT project_id, title, text, image_path, website FROM projects';
$statement = $db_connexion->prepare($request);
$statement->execute();

$result = $statement->fetchAll(PDO::FETCH_ASSOC);

//Preparation de l'affichage des données sur le DOM 
if (!empty($result)) {
    $projectCards = '';
    foreach ($result as $key => $value) {
        $projectCards .= '<div class="project-card">
            <div class="image-container">
                <img src="' . htmlspecialchars($value['image_path']) . '" alt="Image du projet 1" class="project-image">
            </div>
            <h2 class="project-title">' . htmlspecialchars($value['title']) . '</h2>
            <p class="project-content">
                ' . htmlspecialchars(substr($value['text'], 0, 20)) . '...
            </p>
            <div class="btn-container">
            <a href="./displayProjects.php?id=' . htmlspecialchars($value['project_id']) . '" class="btn-show">Afficher</a>
            </div>
        </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets</title>
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/_projectCard.css">
    <link rel="stylesheet" href="../../Style/_nav.css">
    <link rel="stylesheet" href="../../Style/_footer.css">
    <link rel="stylesheet" href="../../Style/projects/projects.css">
    <link rel="stylesheet" href="../../Style/_burger.css">
</head>

<body>
    <header>
        <?php
        include_once '../components/_nav.php';
        include_once '../components/_burger.php';
        ?>
    </header>
    <main class="projects-container">
        <?= $projectCards ?>
    </main>
    <footer class="footer">
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>