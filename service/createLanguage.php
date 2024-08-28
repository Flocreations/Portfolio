<?php

session_start();

// Import BDD 
require_once './db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1){

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
            
        //Filtrer les données reçues par le form 

        $_POST = filter_input_array(INPUT_POST,[
            'language' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
        ]);

        //Récupération de la donnée dans une variable 
        $name = $_POST['language'];

        //Préparation de la requete create 
        $request = 'INSERT INTO languages (name) VALUES (:name)';
        $stmt = $db_connexion->prepare($request);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        
        $_SESSION['successMessage'] = 'Le langage a bien été ajouté.';
        header('Location: ../view/admin/administration/administration.php?name=languages');
        exit();
    }else{
        $_SESSION['errorMessage'] = 'Une erreur est survenue lors de l\'envoi du langage en base de donnée';
        header('Location: ../view/admin/administration/administration.php?name=languages');
        exit();
    }

}else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à cette page ou vous n\'etes pas connecté';
    header('Location: ../view/security/login.php');
    exit();
}