<?php

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    // Requete pour afficher tout les langages disponibles 
    $request = 'SELECT id_language, name FROM languages';
    $statement = $db_connexion->prepare($request);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($result)) {
        $languages = '';
        foreach ($result as $key => $value) {
            $languages .= '<tr>
        <td>' . htmlspecialchars($value['id_language']) . '</td>
        <td>' . htmlspecialchars($value['name']) . '</td>
        <td>
            <a href="../languages/modifyLanguage.php?id=' . htmlspecialchars($value['id_language']) . '" class="btn-edit" ">Modifier</a>
            <a href="../../../service/deleteLanguage.php?id=' . htmlspecialchars($value['id_language']) . '" class="btn-delete" ">Supprimer</a>
        </td>
        </tr>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page pour gérer les différents langages.">
    <title>Langages</title>
    <link rel="stylesheet" href="../../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../../Style/admin/components/_table.css">
    <link rel="stylesheet" href="../../../Style/_burger.css">
</head>

<body>
    <header>
        <?php
        include_once '../../components/_nav.php';
        ?>
    </header>
    <main>
        <h1>Gestion des Langages</h1>
        <?php
        if (isset($_SESSION['errorMessage'])) {
            echo '<p id=errorContainer>' . htmlspecialchars($_SESSION['errorMessage']) . '</p>';
            unset($_SESSION['errorMessage']);
        } elseif (isset($_SESSION['successMessage'])) {
            echo '<p id=successContainer>' . htmlspecialchars($_SESSION['successMessage']) . '</p>';
            unset($_SESSION['successMessage']);
        }
        ?>
        <form action="../../../service/createLanguage.php" method="POST">
            <h2>Ajouter un langage</h2>
            <input type="text" name="language" class="inputs" placeholder="Saisir un nouveau langage" required>
            <input type="submit" value="Valider" id="submit">
        </form>
        <table class="tableContainer">
            <tr>
                <th>Identifiant</th>
                <th>Langage</th>
                <th>Actions</th>
            </tr>
            <?php
            if (!empty($languages)) {
                echo $languages;
            } else {
                $languages = '';
            }
            ?>
        </table>
    </main>
    <script src="../../../Javascript/burgerMenu.js"></script>
</body>

</html>