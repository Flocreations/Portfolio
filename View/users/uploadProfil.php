<?php
session_start();

// Importer BDD :
require_once '../../service/db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id = filter_var($_SESSION['id_user'], FILTER_SANITIZE_NUMBER_INT);

    // Définir le répertoire d'upload
    define('UPLOAD_DIR_PROFILE', '/Media/uploads/profile/');

    // Préparer la requête pour récupérer le chemin de l'image actuelle
    $selectRequest = 'SELECT path_img FROM users WHERE id_user = :id';
    $selectStatement = $db_connexion->prepare($selectRequest);
    $selectStatement->bindParam(':id', $id, PDO::PARAM_INT);
    $selectStatement->execute();
    $result = $selectStatement->fetch(PDO::FETCH_ASSOC);

    // Chemin de l'ancienne image
    $oldFileName = $result['path_img'];
    $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . UPLOAD_DIR_PROFILE;

    if (isset($_FILES['profileImg'])) {
        $fileTmpPath = $_FILES['profileImg']['tmp_name'];
        $fileName = $_FILES['profileImg']['name'];
        $fileSize = $_FILES['profileImg']['size'];
        $fileType = $_FILES['profileImg']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Définir les extensions de fichiers autorisées
        $allowedExts = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedExts)) {
            // Vérification supplémentaire que le fichier est bien une image
            if (getimagesize($fileTmpPath) !== false) {
                // Le fichier est bien une image

                // Générer un nom de fichier unique
                $uniqueFileName = uniqid() . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $uniqueFileName;

                // Vérifiez que le répertoire de destination existe et a les bonnes permissions
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true); // Crée le répertoire si nécessaire
                }

                // Déplacer le fichier téléchargé dans le dossier de destination
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Supprimer l'ancienne image si elle existe
                    if ($oldFileName) {
                        $oldFilePath = $uploadFileDir . $oldFileName;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath); // Supprime le fichier ancien
                        }
                    }

                    // Préparer la requête pour mettre à jour le chemin de l'image dans la base de données
                    $updateRequest = 'UPDATE users SET path_img = :path_img WHERE id_user = :id';
                    $updateStatement = $db_connexion->prepare($updateRequest);
                    $updateStatement->bindParam(':path_img', $uniqueFileName, PDO::PARAM_STR); // Enregistrer le nom du fichier uniquement
                    $updateStatement->bindParam(':id', $id, PDO::PARAM_INT);

                    if ($updateStatement->execute()) {
                        // Redirection vers la page de profil après la mise à jour
                        header('Location: ./profil.php');
                        exit();
                    } else {
                        echo 'Erreur lors de la mise à jour du chemin de l\'image dans la base de données.';
                    }
                } else {
                    echo 'Erreur lors du déplacement du fichier.';
                }
            } else {
                echo 'Le fichier téléchargé n\'est pas une image valide.';
            }
        } else {
            echo 'Extension de fichier non autorisée. Les extensions autorisées sont: jpg, jpeg, png.';
        }
    }
} else {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gérez et modifiez votre profil utilisateur. Téléchargez une nouvelle photo de profil ou la mettre à jour">
    <title>Modifier photo de profil</title>
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../Style/users/uploadProfil.css">
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
        <form action="#" method="post" enctype="multipart/form-data">
            <h2>Ajouter une photo de profil</h2>
            <img id="imagePreview" src="/Media/images/defaultProfil.jpg" class="image-preview" alt="Prévisualisation de l'image">
            <label for="profileImg"></label>
            <input type="file" class="inputs" name="profileImg" accept="image/*">
            <input type="submit" value="Télécharger" id="submit">
        </form>
    </main>
    <footer class="footer">
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/uploadProfile.js"></script>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>
