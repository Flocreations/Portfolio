<?php
session_start();

// Import de la BDD
require_once '../../../service/db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id']) {

    // Récupération de l'id projet contenu dans l'URL et filtrage
    $id = filter_var($_GET['id_project'], FILTER_SANITIZE_NUMBER_INT);

    // Préparation de la requête pour récupérer les informations du projet
    $request = 'SELECT project_id, title, text, image_path, website FROM projects WHERE project_id = :id';
    $statement = $db_connexion->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Condition si $result['website'] est null

    $result['website'] = $result['website'] !== null ? $result['website'] : '';

    // Stocker le chemin de l'image actuelle
    $currentImg = $result['image_path'];
    $currentImgPath = $_SERVER['DOCUMENT_ROOT'] . $currentImg;

    // Récupérer les langages disponibles
    $requestLangs = 'SELECT id_language, name FROM languages';
    $stmtLangs = $db_connexion->prepare($requestLangs);
    $stmtLangs->execute();
    $languages = $stmtLangs->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les langages associés au projet
    $requestProjLangs = 'SELECT id_language FROM project_language WHERE id_project = :id';
    $stmtProjLangs = $db_connexion->prepare($requestProjLangs);
    $stmtProjLangs->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtProjLangs->execute();
    $selectedLanguage = $stmtProjLangs->fetchAll(PDO::FETCH_COLUMN, 0);

    // Préparation de la liste des langages pour le formulaire
    $displayLanguages = "";
    foreach ($languages as $key => $language) {
        $displayLanguages .= '
             <label for="language_' . htmlspecialchars($language['id_language']) . '">' . htmlspecialchars($language['name']) . '</label>
             <input type="checkbox" id="language_' . htmlspecialchars($language['id_language']) . '" name="languages[]" value="' . htmlspecialchars($language['id_language']) . '">
        ';
    }

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        // Traitement des checkboxes
        $newLanguages = isset($_POST['languages']) ? $_POST['languages'] : []; // Tableau des IDs des langages sélectionnés
        // Filtrage des données reçues par le formulaire
        $_POST = filter_input_array(INPUT_POST, [
            'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'text' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'website' => FILTER_SANITIZE_SPECIAL_CHARS
        ]);

        // Stockage des données dans des variables
        $title = $_POST['title'] ?? '';
        $text = $_POST['text'] ?? '';
        $website = $_POST['website'] ?? '';
        $newImage = $_FILES['file'] ?? null;

        if (!empty($title) && !empty($text)) {
            // Si une nouvelle image est uploadée
            if ($newImage && $newImage['error'] == 0) {
                define('UPLOAD_DIR', '/Media/uploads/');
                $allowed_types = ["image/jpeg", "image/png"];

                if (in_array($newImage["type"], $allowed_types)) {
                    // Supprimer l'ancienne image du dossier si elle existe
                    if (!empty($currentImg) && file_exists($currentImgPath)) {
                        unlink($currentImgPath);
                    }

                    $file_name = uniqid() . '-' . basename($newImage["name"]);
                    // Déplacer la nouvelle image dans le dossier de destination
                    $newImagePath = $_SERVER['DOCUMENT_ROOT'] . UPLOAD_DIR . $file_name;
                    if (move_uploaded_file($newImage["tmp_name"], $newImagePath)) {
                        // Mettre à jour le chemin de l'image dans la base de données
                        $imagePath = UPLOAD_DIR . $file_name;
                    } else {
                        $_SESSION['errorMessage'] = 'Erreur : Impossible de télécharger l\'image.';
                        $imagePath = $currentImg; // Conserver l'ancienne image en cas d'erreur
                    }
                } else {
                    $_SESSION['errorMessage'] = 'Erreur : Veuillez sélectionner une image au format JPEG ou PNG';
                    $imagePath = $currentImg; // Conserver l'ancienne image en cas d'erreur
                }
            } else {
                // Si aucune nouvelle image n'est uploadée, garder l'ancienne
                $imagePath = $currentImg;
            }

            // Préparation de la requête pour la mise à jour des données
            $request2 = 'UPDATE projects SET title = :title, text = :text, image_path = :image_path, website = :website WHERE project_id = :project_id';
            $stmt = $db_connexion->prepare($request2);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':text', $text, PDO::PARAM_STR);
            $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(':website', $website, PDO::PARAM_STR);
            $stmt->bindParam(':project_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // **Étape 1 : Supprimer les langages actuels associés au projet**
            $deleteLangRequest = 'DELETE FROM project_language WHERE id_project = :project_id';
            $deleteStmt = $db_connexion->prepare($deleteLangRequest);
            $deleteStmt->bindParam(':project_id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // **Étape 2 : Insérer les nouveaux langages associés**
            $insertLangRequest = 'INSERT INTO project_language (id_project, id_language) VALUES (:project_id, :language_id)';
            $insertStmt = $db_connexion->prepare($insertLangRequest);

            foreach ($newLanguages as $language_id) {
                $insertStmt->bindParam(':project_id', $id, PDO::PARAM_INT);
                $insertStmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
                $insertStmt->execute();
            }


            $stmt->execute();
            //Redirection après mise à jour
            $_SESSION['successMessage'] = 'Votre projet a bien été mis à jour.';
            header('Location: ../administration/administration.php?name=projects');
            exit();
        } else {
            $_SESSION['errorMessage'] = 'Il semblerait que des champs du formulaire aient été laissés vides.';
        }
    }
} else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à la page ou vous n\'etes pas connecté.';
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
    <title>Modification Projet</title>
    <link rel="stylesheet" href="../../../Style/main.css">
    <link rel="stylesheet" href="../../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../../Style/_nav.css">
    <link rel="stylesheet" href="../../../Style/_burger.css">
    <link rel="stylesheet" href="../../../Style/_footer.css">
    <link rel="stylesheet" href="../../../Style/admin/modifyProject.css">
</head>

<body>
    <header>
        <?php 
        include_once '../../components/_nav.php';
        include_once '../../components/_burger.php';
        ?>
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
        <form action="#" method="POST" enctype="multipart/form-data">
            <h2>Modifier un projet</h2>
            <label for="file">Sélectionnez une nouvelle image :</label>
            <input type="file" name="file" id="file">

            <input type="text" class="inputs" name="title" value="<?= htmlspecialchars($result['title'])?>" required>
            <div id= "languages-form">
                <?= $displayLanguages ?>
            </div>
            <input type="text" class="inputs" name="website" value="<?= htmlspecialchars($result['website'])?>">
            <textarea class="inputs" name="text" required><?= htmlspecialchars($result['text']) ?></textarea>

            <input type="submit" id="submit" name="create" value="Mettre à jour">
        </form>
    </main>
    <footer>
        <?php include_once '../../components/_footer.php'; ?>
    </footer>
</body>

</html>