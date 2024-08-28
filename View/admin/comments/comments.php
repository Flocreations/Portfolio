<?php
if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    //Préparation de la requete pour afficher les commentaires 
    $request = 'SELECT comments.com_id, comments.project_id, comments.user_id, comments.com, comments.date, users.username, users.path_img FROM comments
                LEFT JOIN users ON users.id_user = comments.user_id';
    $stmt = $db_connexion->prepare($request);
    $stmt->execute();

    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $com = '';
    foreach ($comments as $key => $value) {
        $com .= '<div class="comment">
            <div class="comment-avatar">
                <img src="../../../Media/uploads/profile/' . htmlspecialchars($value['path_img']) . '" alt="Avatar">
            </div>
            <div class="comment-content">
                <h4>' . htmlspecialchars($value['username']) . '</h4>
                <p>' . htmlspecialchars($value['com']) . '</p>
                <span class="comment-date">' . htmlspecialchars($value['date']) . '</span>
            </div>
            <div id="btnContainer">
                <a href="../../../service/deleteCom.php?com=' . htmlspecialchars($value['com_id']) . '" class="btn-delete">Supprimer</a>
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
    <title>Gestion des commentaires</title>
    <link rel="stylesheet" href="../../../Style/admin/comments.css">
</head>

<body>
    <header>
        <?php include_once '../../components/_nav.php'; ?>
    </header>
    <main>
        <?php
        if (isset($_SESSION['errorMessage'])) {
            echo '<p id=errorContainer>' . htmlspecialchars($_SESSION['errorMessage']) . '</p>';
            unset($_SESSION['errorMessage']);
        } elseif (isset($_SESSION['successMessage'])) {
            echo '<p id=successContainer>' . htmlspecialchars($_SESSION['successMessage']) . '</p>';
            unset($_SESSION['successMessage']);
        }
        ?>
        <h1>Gestion des commentaires</h1>
        <?= $com ?>
    </main>
</body>

</html>