<?php
session_start();

// Import de la BDD
require_once './db_connect.php';

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    // Récupération de l'id projet et filtrage
    $id = filter_var($_GET['id_project'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($id)) {
        // Préparation de la requête pour récupérer le chemin de l'image du projet
        $request = 'SELECT image_path FROM projects WHERE project_id = :project_id';
        $statement = $db_connexion->prepare($request);
        $statement->bindParam(':project_id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Stocker le chemin de l'image actuelle
            $imagePath = $result['image_path'];
            $imageFullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;

            // Supprimer le fichier image s'il existe
            if (!empty($imagePath) && file_exists($imageFullPath)) {
                unlink($imageFullPath);
            }
            
            // Préparation de la requete de suppression des langages liés aux projets 
            $deleteLang = 'DELETE FROM project_language WHERE id_project = :project_id';
            $deleteStmt = $db_connexion->prepare($deleteLang);
            $deleteStmt->bindParam(':project_id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            // Préparation de la requête de suppression du projet
            $deleteRequest = 'DELETE FROM projects WHERE project_id = :project_id';
            $deleteStatement = $db_connexion->prepare($deleteRequest);
            $deleteStatement->bindParam(':project_id', $id, PDO::PARAM_INT);
            $deleteStatement->execute();

            $_SESSION['successMessage'] = 'Votre projet a bien été supprimé.';
            header('Location: ../view/admin/administration/administration.php?name=projects');
            exit(); 
        } else {
            $_SESSION['errorMessage'] = 'Projet non trouvé.';
        }
    } else {
        $_SESSION['errorMessage'] = 'Erreur lors de la récupération du projet.';
    }
} else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à cette page ou vous n\'etes pas connecté';
    header('Location: ../view/security/login.php');
    exit();
}

