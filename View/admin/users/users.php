<?php

if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    // Préparation et execution de la requete (READ)

    $request = 'SELECT users.id_user, users.username, users.role_id, infouser.lastName, infouser.firstName, infouser.mail from users
                LEFT JOIN infouser ON users.id_user = infouser.id_user';
    $statement = $db_connexion->prepare($request);
    $statement->execute();

    // Je stocke les données récupérées dans une variable 
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Je prépare mon affichage dans le DOM avec une boucle foreach pour parcourir mon tableau clé => valeur
    $users = '';
    if (!empty($result)) {
        foreach ($result as $key => $value) {
            $users .= ' <tr>
        <td>' . htmlspecialchars($value['username']) . '</td>
        <td>' . htmlspecialchars($value['lastName']) . '</td>
        <td>' . htmlspecialchars($value['firstName']) . '</td>
        <td>' . htmlspecialchars($value['mail']) . '</td>
        <td>
            <a href="../../../service/deleteUser.php?id=' . htmlspecialchars($value['id_user']) . '" class="btn-delete" ">Supprimer</a>
        </td>
        </tr>';
        }
    }
} else {
    header('Location: ../../security/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="../../Style/admin/components/_form.css">
</head>

<body>
    <header>
        <?php include_once '../../components/_nav.php'; ?>
    </header>
    <main>
        <h1>Gestion des Utilisateurs</h1>

        <!-- Formulaire pour créer un nouvel utilisateur -->
        <form action="../../../service/createUser.php" method="POST">
            <h2>Créer un Utilisateur</h2>
            <input type="text" class="inputs" name="username" placeholder="Pseudo" required>
            <input type="password" class="inputs" name="passwd" placeholder="Mot de passe" required>
            <input type="text" class="inputs" name="lastName" placeholder="Nom" required>
            <input type="text" class="inputs" name="firstName" placeholder="Prenom" required>
            <input type="email" class="inputs" name="mail" placeholder="mail" required>
            <input type="submit" id="submit" name="create" value="Valider">
        </form>
        <?php
        if (isset($_SESSION['errorMessage'])) {
            echo '<p id=errorContainer>' . htmlspecialchars($_SESSION['errorMessage']) . '</p>';
            unset($_SESSION['errorMessage']);
        }elseif (isset($_SESSION['successMessage'])) {
            echo '<p id=successContainer>' . htmlspecialchars($_SESSION['successMessage']) . '</p>';
            unset($_SESSION['successMessage']);
        } 
        ?>
        <!-- Liste des utilisateurs -->
        <table class="tableContainer">
            <tr>
                <th>Pseudo</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <tbody class="action-cell">
                <?= $users ?>
            </tbody>
        </table>
    </main>
</body>

</html>