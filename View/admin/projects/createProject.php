<?php
session_start();

require_once '../../../service/db_connect.php';

define('UPLOAD_DIR', '/Media/uploads/');

// Initialiser la variable pour les langages
$displayLanguages = "";

if (isset($_SESSION['id_user']) && $_SESSION['role_id']) {

    // Préparation de la requête pour pouvoir afficher les langages dans le formulaire
    $request = 'SELECT id_language, name FROM languages';
    $stmt = $db_connexion->prepare($request);
    $stmt->execute();

    $displayLang = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($displayLang)) {
        foreach ($displayLang as $key => $value) {
            $displayLanguages .= '
            <label for="language_' . htmlspecialchars($value['id_language']) . '">' . htmlspecialchars($value['name']) . '</label>
            <input type="checkbox" id="language_' . htmlspecialchars($value['id_language']) . '" name="languages[]" value="' . htmlspecialchars($value['id_language']) . '">
            ';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Traitement des checkboxes
        $languages = isset($_POST['languages']) ? $_POST['languages'] : []; // Tableau des IDs des langages sélectionnés

        // Filtrage des autres entrées
        $_POST = filter_input_array(INPUT_POST, [
            'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'text' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'website' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
        ]);

        $title = $_POST['title'];
        $text = $_POST['text'];
        $website = $_POST['website'];

        // Traitement du fichier
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file'];
            $allowed_types = ["image/jpeg", "image/png"];

            if (in_array($file["type"], $allowed_types)) {
                $file_name = uniqid() . '-' . basename($file["name"]);
                $destination = $_SERVER['DOCUMENT_ROOT'] . UPLOAD_DIR . $file_name;

                if (move_uploaded_file($file["tmp_name"], $destination)) {
                    $image_path = UPLOAD_DIR . $file_name;
                } else {
                    echo "Erreur : Échec du téléchargement de l'image.";
                    $image_path = null;
                }
            } else {
                echo "Erreur : Veuillez sélectionner une image au format JPEG ou PNG.";
                $image_path = null;
            }
        } else {
            $image_path = null; // Aucun fichier ou erreur lors du téléchargement
        }

        if (!empty($title) && !empty($text) && $image_path) {

            // Insérer le projet dans la base de données
            $request = 'INSERT INTO projects (title, text, image_path, website) VALUES (:title, :text, :image_path, :website)';
            $statement = $db_connexion->prepare($request);
            $statement->bindParam(':title', $title, PDO::PARAM_STR);
            $statement->bindParam(':text', $text, PDO::PARAM_STR);
            $statement->bindParam(':image_path', $image_path, PDO::PARAM_STR);
            $statement->bindParam(':website', $website, PDO::PARAM_STR);
            $statement->execute();

            // Récupérer l'ID du projet inséré
            $project_id = $db_connexion->lastInsertId();

            // Insérer les langages associés
            foreach ($languages as $language_id) {
                $request = 'INSERT INTO project_language (id_project, id_language) VALUES (:project_id, :language_id)';
                $statement = $db_connexion->prepare($request);
                $statement->bindParam(':project_id', $project_id, PDO::PARAM_INT);
                $statement->bindParam(':language_id', $language_id, PDO::PARAM_INT);
                $statement->execute();
            }

            $_SESSION['successMessage'] = 'Votre projet a bien été crée.';
            // Redirection ou message de succès
            header('Location: ../administration/administration.php?name=projects');
            exit();
        } else {
            $_SESSION['errorMessage'] = 'Une erreur est survenue, veuillez réessayer.';
        }
    } 
} else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à cette page ou vous n\'etes pas connecté.';
    header('Location: ../../security/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page de création de projets.">
    <title>Création d'un Projet</title>
    <link rel="stylesheet" href="../../../Style/main.css">
    <link rel="stylesheet" href="../../../Style/admin/createProjects.css">
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
            <h2>Créer un Projet</h2>
            <label for="file">Sélectionnez une image :</label>
            <input type="file" name="file" id="file">
            <div id="languages-form">
                <?= $displayLanguages ?>
            </div>
            <input type="text" class="inputs" name="website" placeholder="Site Web">
            <input type="text" class="inputs" name="title" placeholder="Titre" required>
            <textarea class="inputs" name="text" required>Votre contenu</textarea>
            <input type="submit" id="submit" name="create" value="Créer">
        </form>
    </main>
    <footer>
        <?php include_once '../../components/_footer.php'; ?>
    </footer>
</body>

</html>