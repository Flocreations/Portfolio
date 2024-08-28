<?php

session_start();

//Import de la BDD
require_once '../../service/db_connect.php';

// Creation de constantes pour les erreurs 

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_PASSWORD_COMPLEXITY = 'Le mot de passe fournit doit comporter au minimum 8 caractères, 1 majuscule et un caractère spécial.';

// Creation d'un tableau qui recevra les erreurs possibles 

$errors = [
    'lastName' => '',
    'firstName' => '',
    'mail' => '',
    'username' => '',
    'passwd' => ''


];

$messageConfirm = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $filters = filter_input_array(INPUT_POST, [
        'lastName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'firstName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'mail' => FILTER_SANITIZE_EMAIL,
        'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'passwd' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    // Initialisation des variables qui vont recevoir les datas des champ du formulaire 

    $lastName = $filters['lastName'];
    $firstName = $filters['firstName'];
    $mail = $filters['mail'];
    $username = $filters['username'];
    $passwd = $filters['passwd'];

    // Remplissage du tableau qui concerne les erreurs possibles 
    if (!$lastName) {
        $errors['lastName'] = ERROR_REQUIRED;
    }
    if (!$firstName) {
        $errors['firstName'] = ERROR_REQUIRED;
    }
    if (!$mail) {
        $errors['mail'] = ERROR_REQUIRED;
    }
    if (!$username) {
        $errors['username'] = ERROR_REQUIRED;
    }

    if (!$passwd) {
        $errors['passwd'] = ERROR_REQUIRED;
    } elseif (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', $passwd)) {
        $errors['passwd'] = ERROR_PASSWORD_COMPLEXITY;
    }

    // Executer une requete INSERT_INTO
    // Vérification si aucunes erreurs est détecter dans le tableau errors

    if (empty($errors['lastName']) && empty($errors['firstName']) && empty($errors['mail']) && empty($errors['username']) && empty($errors['passwd'])) {

        // Verifier que l'utilisateur n'existe pas en BDD (avec SELECT)

        $sql = 'SELECT username from users
            WHERE username = :username
        ';

        if (isset($db_connexion)) {
            $db_statement = $db_connexion->prepare($sql);
        }

        $db_statement->execute(
            array(
                ':username' => $username
            )
        );

        // L'éxécution de la requete va retourner une valeur. Si celle ci est <= 0 , alors on traite la requete (INSERT_INTO)

        $nb = $db_statement->rowCount();

        // Si le $nb est inférieur ou égal à O alors aucun utilisateur à été trouvé donc on applique la condition

        if ($nb <= 0) {

            $passwdHashed = password_hash($passwd, PASSWORD_DEFAULT);

            $rqt = 'INSERT INTO users (username, passwd)
                VALUES (:username, :passwd)';
            $db_statement = $db_connexion->prepare($rqt);
            $db_statement->bindParam(':username', $username);
            $db_statement->bindParam(':passwd', $passwdHashed);
            $db_statement->execute();

            $id_user = $db_connexion->lastInsertId();

            $request = 'INSERT INTO infouser (id_user, lastName, firstName, mail)
                VALUES (:id_user, :lastName, :firstName, :mail)';
            $db_statement = $db_connexion->prepare($request);
            $db_statement->bindParam(':id_user', $id_user);
            $db_statement->bindParam(':lastName', $lastName);
            $db_statement->bindParam(':firstName', $firstName);
            $db_statement->bindParam(':mail', $mail);
            $db_statement->execute();

            $successMessage = 'Votre compte a bien été enregistré. Vous pouvez désormais vous connecter, rendez vous sur la page de connexion.';
        } else {
            $errorMessage = 'Ce compte existe déja.';
        }
    } else {
        if (!$username || !$lastName || !$firstName || !$mail) {
            $errorMessage = ERROR_REQUIRED;
        } elseif (!empty($errors['passwd']) && $errors['passwd'] === ERROR_PASSWORD_COMPLEXITY) {
            $errorMessage = ERROR_PASSWORD_COMPLEXITY;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FV | S'inscrire</title>
    <meta name="description" content="Page d'inscription afin de créer un compte utilisateur.">
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/security/register.css">
    <link rel="stylesheet" href="../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../Style/_nav.css">
    <link rel="stylesheet" href="../../Style/_burger.css">
    <link rel="stylesheet" href="../../Style/_footer.css">
</head>

<body>
    <header>
        <?php include_once '../components/_nav.php'; ?>
        <?php include_once '../components/_burger.php'; ?>
    </header>
    <main>
        <?php
        if (isset($errorMessage)) {
            echo '<p id=errorContainer>' . htmlspecialchars($errorMessage) . '</p>';
            unset($errorMessage);
        } elseif (isset($successMessage)) {
            echo '<p id=successContainer>' . htmlspecialchars($successMessage) . '</p>';
            unset($successMessage);
        }
        ?>
        <form action="#" method="POST">
            <h2>Inscription</h2>
            <label for="lastName"></label>
            <input type="text" name="lastName" placeholder="Nom" class="inputs" required>
            <label for="firstName"></label>
            <input type="text" name="firstName" placeholder="Prenom" class="inputs" required>
            <label for="mail"></label>
            <input type="email" name="mail" placeholder="Email" class="inputs" required>
            <label for="username"></label>
            <input type="text" name="username" placeholder="Pseudo" class="inputs" required>
            <label for="passwd"></label>
            <input type="password" name="passwd" placeholder="Mot de passe" class="inputs">
            <div class="form-group">
                <label for="acceptTerms">
                    <input type="checkbox" id="acceptTerms" name="acceptTerms">
                    J'accepte les <a href="./View/security/conditionsGenerales.php">termes et conditions</a>
                </label>
            </div>
            <div>
                <input type="submit" id="submit" value="Valider">
            </div>
        </form>
    </main>
    <footer>
        <?php include_once '../components/_footer.php'; ?>
    </footer>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>