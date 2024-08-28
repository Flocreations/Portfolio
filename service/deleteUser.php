<?php
session_start();

// Import BDD 
require_once './db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1 ) {
    
    // Récupération du param d'URL et donc de l'id user
    $id = $_GET['id'];

    // Préparation de la requete de suppression des données (NE PAS OUBLIER POUR LA CONIRMATION)
    $request = 'DELETE FROM infouser
                WHERE id_user = :id';
    $statement = $db_connexion->prepare($request);
    $statement->bindParam(':id', $id);
    $statement->execute();

    // Deuxieme requete pour supprimer l'utilisateur une fois que ses données associées ont été supprimées
    $request2 = 'DELETE FROM users 
                WHERE id_user = :id';
    $stmt = $db_connexion->prepare($request2);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $_SESSION['successMessage'] = 'Utilisateur a bien été supprimé.';
    header('Location: ../view/admin/administration/administration.php');
}else{
    header('Location: ../../security/login.php');
}
?> 