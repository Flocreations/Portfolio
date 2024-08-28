<?php
session_start();

// Import BDD: 
require_once '../../service/db_connect.php';

if (isset($_SESSION['id_user'])) {

    $id = $_SESSION['id_user'];

    // Récupération des données actuelles de l'utilisateur
    $selectRequest = 'SELECT users.username, infouser.lastName, infouser.firstName, infouser.mail 
                      FROM users 
                      LEFT JOIN infouser ON infouser.id_user = users.id_user
                      WHERE users.id_user = :id_user';
    $stmt = $db_connexion->prepare($selectRequest);
    $stmt->bindParam(':id_user', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        // Filtrage des données reçues par le formulaire 
        $_POST = filter_input_array(INPUT_POST, [
            'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'lastName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'firstName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'mail' => FILTER_VALIDATE_EMAIL
        ]);

        // Stockage des données dans des variables 
        $username = $_POST['username'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $mail = $_POST['mail'];

        if (!empty($username) && !empty($lastName) && !empty($firstName) && !empty($mail)) {

            // Préparation de la requête UPDATE pour la table users
            $request = 'UPDATE users SET username = :username WHERE id_user = :id_user';
            $statement = $db_connexion->prepare($request);
            $statement->bindParam(':username', $username);
            $statement->bindParam(':id_user', $id);
            $statement->execute();

            // Préparation de la requête UPDATE pour la table infouser
            $request2 = 'UPDATE infouser SET lastName = :lastName, firstName = :firstName, mail = :mail WHERE id_user = :id_user';
            $stmt = $db_connexion->prepare($request2);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':mail', $mail);
            $stmt->bindParam(':id_user', $id);
            $stmt->execute();

            header('Location: ./profil.php');
            exit();
        } else {
            $message = 'Veuillez bien remplir tous les champs disponibles et vérifier l\'email.';
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
    <meta name="description" content="Gérez et modifiez votre profil à votre convenance avec cette page.">
    <title>Modification du Profil</title>
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../Style/_nav.css">
    <link rel="stylesheet" href="../../Style/_burger.css">
    <link rel="stylesheet" href="../../Style/_footer.css">
    <link rel="stylesheet" href="../../Style/users/editProfil.css">
</head>

<body>
    <header>
        <?php
        include_once '../components/_nav.php';
        include_once '../components/_burger.php';
        ?>
    </header>
    <main>
        <form action="#" method="POST">
            <h2>Modifier mon profil</h2>
            <?php if (isset($message)) {
                echo '<p class="error-message"><?= htmlspecialchars($message) ?></p>';
            }
            ?>
            <input type="text" class="inputs" name="username" placeholder="Pseudo" value="<?= htmlspecialchars($user['username']) ?>" required>
            <input type="text" class="inputs" name="lastName" placeholder="Nom" value="<?= htmlspecialchars($user['lastName']) ?>" required>
            <input type="text" class="inputs" name="firstName" placeholder="Prénom" value="<?= htmlspecialchars($user['firstName']) ?>" required>
            <input type="email" class="inputs" name="mail" placeholder="Email" value="<?= htmlspecialchars($user['mail']) ?>" required>
            <input type="submit" id="submit" name="edit" value="Modifier">
        </form>
    </main>
    <footer class="footer">
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>