<?php 

session_start(); 

//Import de la BDD 
require_once './db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1){

    // Filtrage de la variable super globale 
    filter_var($_GET['msg']);

    // Récupération de l'id du message situé dans l'URL et stockage dans une variable 
    $idMsg = $_GET['msg']; 

    // Préparation de la requete pour la suppression du message 
    $deleteMsg = 'DELETE FROM message WHERE id_message = :id'; 
    $stmt = $db_connexion->prepare($deleteMsg); 
    $stmt->bindParam(':id', $idMsg, PDO::PARAM_INT);
    $stmt->execute(); 

    $_SESSION['successMessage'] = 'Ce message a bien été supprimé.';
    header('Location:  ../view/admin/administration/administration.php?name=message');
    exit();
}else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à cette page ou vous n\'etes pas connecté.';
    header('Location: ../view/security/login.php');
    exit();
}