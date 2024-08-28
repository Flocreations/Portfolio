<?php

session_start();

//Import Bdd
require_once '../../../service/db_connect.php';


// Vérification de la connexion et du role de l'utilisateur pour qu'il soit égal à 'Admin'
if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {
} else {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="../../../Style/main.css">
    <link rel="stylesheet" href="../../../Style/admin/admin.css">
    <link rel="stylesheet" href="../../../Style/admin/components/_form.css">
    <link rel="stylesheet" href="../../../Style/admin/components/_table.css">
    <link rel="stylesheet" href="../../../Style/admin/components/_cards.css">
    <link rel="stylesheet" href="../../../Style/_nav.css">
    <link rel="stylesheet" href="../../../Style/_burger.css">
</head>

<body>
    <header>
        <?php
        include_once '../../components/_burger.php';
        ?>
    </header>
    <div class="wrapper">
        <div class="sidebar">
            <h3>MES OUTILS</h3>
            <ul>
                <li><a href="./administration.php?name=users" name="users">Utilisateurs</a></li>
                <li><a href="./administration.php?name=projects" name="projects">Projets</a></li>
                <li><a href="./administration.php?name=comments" name="comments">Commentaires</a></li>
                <li><a href="./administration.php?name=languages" name="languages">Langages</a></li>
                <li><a href="./administration.php?name=message" name="message">Messagerie</a></li>
            </ul>
        </div>
        <div class="container">
            <?php
            $name = isset($_GET['name']) ? $_GET['name'] : '';
            // $_GET['name'] = '';
            if ($name === "users") {
                include_once '../users/users.php';
            } elseif ($name === "projects") {
                include_once '../projects/projects.php';
            } elseif ($name === "comments") {
                include_once '../comments/comments.php';
            } elseif ($name === "languages") {
                include_once '../languages/languages.php';
            } elseif ($name === "message") {
                include_once '../message/message.php';
            } else {
                include_once '../users/users.php';
            }
            ?>
        </div>
    </div>
    <script src="../../../Javascript/burgerMenu.js"></script>
</body>

</html>