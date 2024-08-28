<?php
session_start();

//Import Bdd
require_once '../../service/db_connect.php';

$idProject = $_GET['id'];
$idUser = $_SESSION['id_user'];

if (isset($idProject)) {

    // Préparation de la requete pour afficher l'article sélectionné
    $request = 'SELECT title, text, image_path, website, cat_id FROM projects
                WHERE project_id = :id';
    $statement = $db_connexion->prepare($request);
    $statement->bindParam(':id', $idProject);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_SESSION['id_user'])) {

    //Filtrage des données reçues par le form
    $filter = filter_input_array(INPUT_POST, [
        'project_id' => FILTER_SANITIZE_NUMBER_INT,
        'user_id' => FILTER_SANITIZE_NUMBER_INT,
        'comment' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    //Récupération des données filtrées dans des variables 
    $projectId = $filter['project_id'];
    $userId = $filter['user_id'];
    $comment = $filter['comment'];


    // Préparation de la requete pour envoyer le commentaire en base de donnée
    $request = 'INSERT INTO comments (project_id, user_id, com) VALUES (:project_id, :user_id, :com)';
    $statement = $db_connexion->prepare($request);
    $statement->bindParam(':project_id', $projectId, PDO::PARAM_INT);
    $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $statement->bindParam(':com', $comment, PDO::PARAM_STR);
    $statement->execute();
}

// Requete pour afficher les commentaires dans le DOM 

$request = 'SELECT comments.com, comments.date, users.username, users.path_img FROM comments
            LEFT JOIN users ON users.id_user = comments.user_id
            WHERE comments.project_id = :idProject';
$statement = $db_connexion->prepare($request);
$statement->bindParam(':idProject', $idProject, PDO::PARAM_INT);
$statement->execute();

$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

$com = '';
foreach ($comments as $key => $value) {
    $com .= '<div class="comment">
            <div class="comment-avatar">
                <img src="../../Media/uploads/profile/' . $value['path_img'] . '" alt="Avatar">
            </div>
            <div class="comment-content">
                <h4>' . $value['username'] . '</h4>
                <p>' . $value['com'] . '</p>
                <span class="comment-date">' . $value['date'] . '</span>
            </div>
        </div>';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $result['title'] ?></title>
    <link rel="stylesheet" href="../../Style/_nav.css">
    <link rel="stylesheet" href="../../Style/projects/displayProjects.css">
    <link rel="stylesheet" href="../../Style/_footer.css">
</head>

<body>
    <header>
        <?php include_once '../components/_nav.php'; ?>
    </header>
    <main>
        <div class="project-card">
            <div class="image-container">
                <img src="<?= $result['image_path'] ?>" alt="Image du projet" class="project-image">
            </div>
            <h2 class="project-title"><?= $result['title'] ?></h2>
            <a href="<?= $result['website'] ?>" id="btn-website">Voir le projet</a>
            <p class="project-content">
                <?= $result['text'] ?>
            </p>
            <h4>Commentaires:</h4>
            <?php
            if ($_SESSION['id_user']) {
                echo '
                        <form action="#" method="POST">
                        <input type="hidden" value="' . $idProject . '" name="project_id">
                        <input type="hidden" value="' . $idUser . '" name="user_id">
                        <textarea placeholder="Laisser un commentaire..." name="comment"></textarea>
                        <input type="submit" id=submit value="Envoyer">
                        </form>';
            } else {
                $_SESSION['id_user'] = '';
                echo '<p>Pour laisser un commentaire, veuillez vous connecter à votre compte ou en créer un.</p>';
            }
            echo $com;
            ?>
        </div>';
    </main>
    <footer class="footer">
        <?php include_once '../components/_footer.php'; ?>
    </footer>
</body>

</html>