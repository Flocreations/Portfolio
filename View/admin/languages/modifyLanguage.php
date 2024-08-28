<?php
session_start();

//Import BDD 
require_once '../../../service/db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    $idLanguage = $_GET['id'];
    //Préparation de la requete pour récupérer le nom du langage 
    $request = 'SELECT name FROM languages
                WHERE id_language = :id';
    $statement = $db_connexion->prepare($request);
    $statement->bindParam(':id', $idLanguage, PDO::PARAM_INT);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        //Filtrer la donnée reçue par le form 
        $_POST = filter_input_array(INPUT_POST, [
            'language' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
        ]);

        // Récupération de la donnée dans une variable 

        $name = $_POST['language'];
        if (!empty($name)) {

            $request = "UPDATE languages SET name = :name
                        WHERE id_language = :id";
            $stmt = $db_connexion->prepare($request);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $idLanguage, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['successMessage'] = 'La modification du langage a bien été prise en compte';
            header('Location: ../administration/administration.php?name=languages');
            exit();
        } else {
            $_SESSION['errorMessage'] = 'Une erreur est survenue';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Modifiez vos langages depuis cette page.">
    <title>Modification langage</title>
    <link rel="stylesheet" href="../../../Style/main.css">
    <link rel="stylesheet" href="../../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../../Style/_nav.css">
    <link rel="stylesheet" href="../../../Style/_burger.css">
    <link rel="stylesheet" href="../../../Style/_footer.css">
    <link rel="stylesheet" href="../../../Style/admin/modifyLanguage.css">
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
        <form action="#" method="POST">
            <h2>Modification Langage</h2>
            <input type="text" class="inputs" name="language" placeholder="Pseudo" value="<?= htmlspecialchars($result['name']) ?>" required>
            <input type="submit" id="submit" value="Modifier">
        </form>
    </main>
    <footer>
        <?php include_once '../../components/_footer.php'; ?>
    </footer>
</body>

</html>