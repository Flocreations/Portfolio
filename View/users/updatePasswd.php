<?php
session_start();
// Import BDD :
require_once '../../service/db_connect.php';

if (isset($_SESSION['id_user'])) {
    // Stockage de l'id user dans une variable
    $id = $_SESSION['id_user'];

    // Vérification pour savoir si le formulaire POST a été envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $messagePasswordError = '';

        // Filter les données reçue par $_POST
        $_POST = filter_input_array(INPUT_POST, [
            'oldPasswd' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'newPasswd' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'confirmPasswd' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
        ]);

        // Instancier les données reçues dans des variables
        $oldPasswd = $_POST['oldPasswd'];
        $newPasswd = $_POST['newPasswd'];
        $confirmPasswd = $_POST['confirmPasswd'];

        // Expression régulière pour valider le mot de passe
        $passwordPattern = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';

        // Vérification de la correspondance des mots de passe et des critères de sécurité
        if (!preg_match($passwordPattern, $newPasswd)) {
            $messagePasswordError = "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre, un caractère spécial, et avoir au moins 8 caractères.";
        } elseif ($newPasswd !== $confirmPasswd) {
            $messagePasswordError = "Les deux mots de passe ne correspondent pas.";
        } else {
            // Récupérer le mot de passe avant modification (Mot de passe hashé)
            $query = $db_connexion->prepare("SELECT passwd FROM users WHERE id_user = :id_user;");
            $query->bindParam(':id_user', $id, PDO::PARAM_INT);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

            // Vérifier l'ancien mot de passe
            if (!password_verify($oldPasswd, $data['passwd'])) {
                $messagePasswordError = "Le mot de passe actuel est incorrect, veuillez réessayer.";
            } else {
                // Hasher le nouveau mot de passe pour la sécurité
                $newPasswdHashed = password_hash($newPasswd, PASSWORD_DEFAULT);

                // Mettre à jour le mot de passe dans la base de données
                $updateQuery = $db_connexion->prepare("UPDATE users SET passwd = :newPasswd WHERE id_user = :id_user;");
                $updateQuery->bindParam(':newPasswd', $newPasswdHashed, PDO::PARAM_STR);
                $updateQuery->bindParam(':id_user', $id, PDO::PARAM_INT);
                $updateQuery->execute();

                $message = "Mot de passe mis à jour avec succès.";
            }
        }
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
    <meta name="description" content="Page de modification du mot de passe pour améliorer la sécurité de votre compte.">
    <title>Modification du mot de passe</title>
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/users/editProfil.css">
    <link rel="stylesheet" href="../../Style/admin/components/_form.css">
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
        <form action="#" method="POST">
            <h2>Modifier le mot de passe</h2>

            <?php 
            if (!empty($messagePasswordError)){
                echo '<div class="error-message"><?= htmlspecialchars($messagePasswordError); ?></div>';
            }
            ?>

            <label for="oldPasswd">Mot de passe actuel :</label>
            <input type="password" class="inputs" name="oldPasswd" required>

            <label for="newPasswd">Nouveau mot de passe :</label>
            <input type="password" class="inputs" name="newPasswd" required>

            <label for="confirmPasswd">Confirmer le nouveau mot de passe :</label>
            <input type="password" class="inputs" name="confirmPasswd" required>

            <input type="submit" value="Valider" id="submit">
        </form>
    </main>
    <footer class="footer">
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>