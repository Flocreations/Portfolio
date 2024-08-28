<?php
session_start();
// Import de la connexion à la BDD 
require_once '../../service/db_connect.php';

// Vérification pour savoir si un utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer le user_id de la connexion (session)
$id = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le mot de passe actuel hashé de l'utilisateur
    $query = $db_connexion->prepare("SELECT passwd FROM users WHERE id_user = :id_user");
    $query->bindParam(':id_user', $id, PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch(PDO::FETCH_ASSOC);

    // Filtrage de la donnée reçue du formulaire POST
    $_POST = filter_input_array(INPUT_POST, [
        'password' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    // Récupération du mot de passe du formulaire
    $password = $_POST['password'];

    try {
        // Vérification du mot de passe
        if (password_verify($password, $data['passwd'])) {
            // Requête pour supprimer les informations utilisateur
            $deleteUserInfo = $db_connexion->prepare("DELETE FROM infouser WHERE id_user = :id_user");
            $deleteUserInfo->bindParam(':id_user', $id, PDO::PARAM_INT);
            $deleteUserInfo->execute();

            // Requête pour supprimer le compte
            $deleteAccount = $db_connexion->prepare("DELETE FROM users WHERE id_user = :id_user");
            $deleteAccount->bindParam(':id_user', $id, PDO::PARAM_INT);
            $deleteAccount->execute();

            // Stopper la session utilisateur
            session_destroy();

            // Page de confirmation de suppression de compte
            header('Location: ../../../../index.php');
            exit();
        } else {
            $message = "Le mot de passe est incorrect. Veuillez réessayer.";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression du compte : " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page pour confirmer la suppression de votre compte utilisateur.">
    <title>Suppression de Compte</title>
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../Style/users/editProfil.css">
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
            <h2>Suppression de votre compte</h2>

            <?php
            if (isset($message)) {
                echo '<div class="error-message"><?= htmlspecialchars($message); ?></div>';
            }
            ?>

            <p id="alertMessage">Pour confirmer la suppression de votre compte, veuillez renseigner le mot de passe de votre compte utilisateur :</p>

            <label for="password"></label>
            <input type="password" name="password" id="password" class="inputs" placeholder="Mot de passe" required>

            <input type="submit" value="Supprimer mon compte" id="submit">
        </form>
    </main>
    <footer class="footer">
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>