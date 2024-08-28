<?php
require_once '../../../service/db_connect.php';
if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    // Requete pour afficher tout les projets 

    $request = 'SELECT project_id, title, text, image_path from projects';
    $statement = $db_connexion->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Concaténation et préparation à l'affichage dans le DOM
    $cards = '';
    if (!empty($result)) {
        foreach ($result as $key => $value) {
            $cards .= '
                            <div class="card">
                                <img src="' . htmlspecialchars($value['image_path']) . '" alt="Image de l\'article" class="card-img">
                                <div class="card-content">
                                    <h2 class="card-title">' . htmlspecialchars($value['title']) . '</h2>
                                    <p class="card-text">' . htmlspecialchars(substr($value['text'], 0, 20)) . '...</p>
                                </div>
                                <div class="card-actions">
                                    <a href="../projects/modifyProject.php?id_project=' . htmlspecialchars($value['project_id']) . '" class="btn-edit">Modifier</a>
                                    <a href="../../../service/deleteProject.php?id_project=' . htmlspecialchars($value['project_id']) . '" class="btn-delete">Supprimer</a>
                                </div>
                            </div>
                        ';
        }
    }
} else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à cette page ou vous n\'etes pas connecté';
    header('Location: ../../security/login.php');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gérez vos projets, modifier et supprimer les au besoin.">
    <title>Gérer mes projets</title>
</head>

<body>
    <header>
        <?php include_once '../../components/_nav.php'; ?>
    </header>
    <main>
        <h1>Gestion des projets</h1>
        <?php
        if (isset($_SESSION['errorMessage'])) {
            echo '<p id=errorContainer>' . htmlspecialchars($_SESSION['errorMessage']) . '</p>';
            unset($_SESSION['errorMessage']);
        } elseif (isset($_SESSION['successMessage'])) {
            echo '<p id=successContainer>' . htmlspecialchars($_SESSION['successMessage']) . '</p>';
            unset($_SESSION['successMessage']);
        }
        ?>
        <a href="../projects/createProject.php" id="btn-create">Créer un nouveau Projet</a>
        <section id="sectionCard">
            <?php
            if (!empty($cards)) {
                echo $cards;
            }
            ?>
        </section>
    </main>
</body>

</html>