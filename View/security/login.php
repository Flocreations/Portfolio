<?php

session_start();

// Import bdd
require_once '../../service/db_connect.php';

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_PASSWORD_COMPLEXITY = 'Le mot de passe doit contenir au moins 8 caractères, 1 majuscule et un caractère spécial';

// Création d'un tableau qui récupérera les erreurs possibles 

$errors = [
    'username' => '',
    'password' => ''
];

$message = '';

// Traitement des données si la méthode du formulaire soumis est bien POST 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, [
        'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'password' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    // Initialisation des variables qui vont recevoir les datas des champ du formulaire 

    $username = $_POST['username'];
    $passwd = $_POST['password'];

    // Remplissage du tableau des erreurs si erreurs il y a 

    if (!$username) {
        $errors['username'] = ERROR_REQUIRED;
    }

    if (!$passwd) {
        $errors['password'] = ERROR_REQUIRED;
    } elseif (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', $passwd)) {
        $errors['password'] = ERROR_PASSWORD_COMPLEXITY;
    }

    // Executer une requete SELECT

    if (empty($errors['username']) && empty($errors['password'])) {

        // Verifier que l'utilisateur existe  en BDD (avec SELECT)

        $sql = 'SELECT id_user,username, passwd, role_id  FROM users WHERE username = :username';

        $statement = $db_connexion->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch();

        $_SESSION['username'] = $result['username'];
        $_SESSION['id_user'] = $result['id_user'];
        $_SESSION['role_id'] = $result['role_id'];

        if (!$result) {
            $errors['username'] = 'Cet utilisateur n\'existe pas. Veuillez réessayer.';
            $message = $errors['username'];
        } else {
            if (password_verify($passwd, $result['passwd'])) {
                if ($result['role_id'] === 1) {
                    header('Location: ../../index.php');
                } else {
                    header('Location: ../../index.php');
                }
            } else {
                $_SESSION['errorMessage'] = 'Mot de passe incorrect, veuillez ressaisir votre mot de passe ou le réinitialiser.';
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <meta name="description" content="Page de connexion pour se connecter avec votre compte et commenter les projets.">
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/security/login.css">
    <link rel="stylesheet" href="../../Style/_nav.css">
    <link rel="stylesheet" href="../../Style/admin/components/_form.css">
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
            <h2>Connexion</h2>
            <label for="username"></label>
            <input type="text" name="username" placeholder="Nom d'utilisateur" class="inputs" required>
            <label for="password"></label>
            <input type="password" name="password" placeholder="Mot de passe" pattern="/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/" class="inputs" required>
            <input type="submit" id="submit" value="Valider">
            <p>Vous n'avez pas de compte ? <a href="./register.php">Inscription</a></p>
            <!--TODO: Faire une page qui permettra a l'utilisateur de reinitialiser le mot de passe -->
            <p>Vous avez oublié votre mot de passe ? <a href="#">Reinitialisez-le</a></p>
        </form>
    </main>
    <footer>
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>