<?php

session_start();

//Import BDD
require_once './db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1){
    
    //Filtrage la variable super globale
    filter_var($_GET['id']);

    // Récupération de l'id langage et instanciation dans une variable 
    $idLanguage = $_GET['id'];

    // Préparation de la requete pour la suppression du langage 
    $request = 'DELETE FROM languages WHERE id_language = :id';
    $statement = $db_connexion->prepare($request);
    $statement->bindParam(':id', $idLanguage, PDO::PARAM_INT);
    $statement->execute();

    $_SESSION['successMessage'] = 'Le langage a bien été supprimé.';
    header('Location: ../view/admin/administration/administration.php?name=languages');
    exit();
}else {
    header('Location: ../view/security/login.php');
    exit();
}

