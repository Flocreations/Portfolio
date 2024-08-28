<?php
if (isset($_SESSION['id_user']) && $_SESSION['role_id'] === 1) {

    // Préparation de la requete pour récupérer les messages dans la base de donnée 
    $request = 'SELECT id_message, firstName, lastName, object, mail, msg FROM message';
    $stmt = $db_connexion->prepare($request);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $messages = "";

    if (!empty($result)) {
        foreach ($result as $key => $value) {
            $messages .= '
                            <div class="message">
                            <div class="name">
                                <p>' . htmlspecialchars($value['firstName']) . '</p>
                                <p>' . htmlspecialchars($value['lastName']) . '</p>
                            </div>
                            <p>Objet:  ' . htmlspecialchars($value['object']) . '</p>
                            <p>Email:  ' . htmlspecialchars($value['mail']) . '</p>
                            <p id="msg">
                                ' . htmlspecialchars($value['msg']) . '
                            </p>
                            <div class="card-actions">
                                <a href="mailto:vanlangendonck.florent@gmail.com" class="btn-edit">Répondre</a>
                                <a href="../../../service/deleteMessage.php?msg=' . htmlspecialchars($value['id_message']) . '" class="btn-delete">Supprimer</a>
                            </div>
                            </div>
                         ';
        }
    }
} else {
    $_SESSION['errorMessage'] = 'Vous n\'etes pas autorisé à accéder à cette page ou vous n\'etes pas connecté.';
    header('Location: ../../security/login.php');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ici vous pouvez gérer vos messages envoyé via le formulaire de contact.">
    <title>Messagerie</title>
    <link rel="stylesheet" href="../../../Style/admin/message.css">
</head>

<body>
    <header>
        <?php include_once '../../components/_nav.php'; ?>
    </header>
    <main>
        <h1>Messagerie</h1>
        <?php
        if (isset($_SESSION['errorMessage'])) {
            echo '<p id=errorContainer>' . htmlspecialchars($_SESSION['errorMessage']) . '</p>';
            unset($_SESSION['errorMessage']);
        } elseif (isset($_SESSION['successMessage'])) {
            echo '<p id=successContainer>' . htmlspecialchars($_SESSION['successMessage']) . '</p>';
            unset($_SESSION['successMessage']);
        }

        if (!empty($messages)) {
            echo $messages;
        } else {
            $messages = "";
        }
        ?>
    </main>
</body>

</html>