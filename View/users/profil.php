<?php
session_start();

// Importer BDD : 
require_once '../../service/db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id = filter_var($_SESSION['id_user'], FILTER_SANITIZE_NUMBER_INT);

    // Préparation de la requête pour récupérer les données utilisateur
    $request = 'SELECT users.username, users.path_img, infouser.lastName, infouser.firstName, infouser.mail FROM users
                LEFT JOIN infouser ON infouser.id_user = users.id_user
                WHERE users.id_user = :id';
    $statement = $db_connexion->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Définir le répertoire pour l'image
    define('UPLOAD_DIR_PROFILE', '/Media/uploads/profile/');

    // Déterminer le chemin de l'image de prévisualisation
    if (empty($result['path_img'])) {
        // Image par défaut
        $fileDir = '/Media/images/defaultProfil.jpg ';
    } else {
        // Image de profil
        $fileName = $result['path_img'];
        $fileDir = UPLOAD_DIR_PROFILE . $fileName;
    }
} else {
    header('Location: ../security/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="#">
    <title>Profil</title>
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/users/profil.css">
    <link rel="stylesheet" href="../../Style/_nav.css">
    <link rel="stylesheet" href="../../Style/_burger.css">
    <link rel="stylesheet" href="../../Style/_footer.css">
</head>

<body>
    <header>
        <?php 
            include_once '../components/_nav.php'; 
            include_once '../components/_burger.php';
        ?>
    </header>
    <main>
        <div class="profile-container">
            <div class="profile-card">
                <!-- Conteneur pour la photo de profil -->
                <div class="profile-image">
                    <img src="<?= $fileDir ?>" alt="Photo de profil">
                </div>
                <h1>Profil de <?= htmlspecialchars($result['username']) ?> </h1>
                <div class="profile-info">
                    <p><strong>Pseudo:</strong> <span id="username"><?= htmlspecialchars($result['username']) ?></span></p>
                    <p><strong>Nom:</strong> <span id="lastname"><?= htmlspecialchars($result['lastName']) ?></span></p>
                    <p><strong>Prénom:</strong> <span id="firstname"><?= htmlspecialchars($result['firstName']) ?></span></p>
                    <p><strong>Email:</strong> <span id="email"><?= htmlspecialchars($result['mail']) ?></span></p>
                </div>
                <div class="profile-actions">
                    <a href="./uploadProfil.php">Modifier photo de profil</a>
                    <a href="./editProfil.php">Modifier mes informations</a>
                    <a href="./updatePasswd.php">Modifier mon mot de passe</a>
                    <a href="./deleteAccount.php">Supprimer mon compte</a>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer">
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>