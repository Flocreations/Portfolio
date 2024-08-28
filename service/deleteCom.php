<?php
session_start();
require_once './db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1){

    //Filtrage de la variable super globale 
    filter_var($_GET['com']);

    // Récupération de l'id dans l'url et instanciation dans une variable 
    $idCom = $_GET['com'];

    //Préparation de la requete pour supprimer les commentaires 
    $request = 'DELETE FROM comments WHERE com_id = :id';
    $stmt = $db_connexion->prepare($request); 
    $stmt->bindParam(':id', $idCom, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['successMessage'] = 'Le commentaire a bien été supprimé.';
    header('Location: ../view/admin/administration/administration.php?name=comments');
}else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à cette page ou vous n\'etes pas connecté';
    header('Location: ../view/security/login.php');
}