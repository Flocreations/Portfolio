<?php
session_start();

// Import BDD: 
require_once './db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        // Filtrage des données reçues  
        $_POST = filter_input_array(INPUT_POST, [
            'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'passwd' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'lastName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'firstName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'mail' => FILTER_SANITIZE_EMAIL
        ]);

        // Je stocke les données reçues dans des variables 
        $username = $_POST['username'];
        $password = $_POST['passwd'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $mail = $_POST['mail'];

        // Hashage du mot de passe reçue avant de l'envoyer en BDD 

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        // Préparation de l'envoi des données en BDD en deux temps 

        if (!empty($username) && !empty($passwordHashed) && !empty($lastName) && !empty($firstName) && !empty($mail)) {
            // Première requete: 
            $request = 'INSERT INTO users (username, passwd) VALUES (:username, :passwd)';
            $statement = $db_connexion->prepare($request);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':passwd', $passwordHashed);
            $statement->execute();

            $id_user = $db_connexion->lastInsertId();

            // Deuxième requete pour les infos utilisateurs 
            $request2 = 'INSERT INTO infouser(id_user, lastName, firstName, mail) VALUES (:id_user, :lastName, :firstName, :mail)';
            $stmt = $db_connexion->prepare($request2);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam('firstName', $firstName, PDO::PARAM_STR); 
            $stmt->bindParam(':mail', $mail); 
            $stmt->execute();

            header('Location: ../view/admin/administration/administration.php');
            $_SESSION['successMessage'] = 'L\'utilisateur a bien été ajouté';

        }else{
            $_SESSION['errorMessage'] = 'Une erreur est survenue,veuillez bien renseigner tout les champs du formulaire.';
            header('Location: ../view/admin/administration/administration.php');
        }
    }else{
        $_SESSION['errorMessage'] = 'Une erreur est survenue lors de la soumission du formulaire';
        header('Location: ../view/admin/administration/administration.php');
    }
}else{
    header('Location: ../../security/login.php');
}
?>