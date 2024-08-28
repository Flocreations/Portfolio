<?php
session_start();
if ($_SESSION === null) {
    $_SESSION = '';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales</title>
    <link rel="stylesheet" href="../../Style/main.css">
    <link rel="stylesheet" href="../../Style/_nav.css">
    <link rel="stylesheet" href="../../Style/_burger.css">
    <link rel="stylesheet" href="../../Style/security/conditionsGenerales.css">
</head>

<body>
    <header>
        <?php
        include_once '../components/_nav.php';
        include_once '../components/_burger.php';
        ?>
    </header>
    <main>
        <div>
            <h5>1. Introduction</h5>
            <p>En utilisant nos services pour l'envoi de courriels, vous acceptez de respecter les présentes conditions générales d'utilisation. Ces conditions visent à garantir une utilisation conforme aux lois en vigueur et à maintenir un environnement sécurisé pour tous nos utilisateurs.</p>

            <h5>2. Acceptation des conditions</h5>
            <p>En envoyant des courriels via nos services, vous reconnaissez avoir lu, compris et accepté ces conditions générales. Si vous n'acceptez pas ces conditions, vous ne devez pas utiliser nos services d'envoi de courriels.
            </p>
            <h5>3. Utilisation autorisée</h5>
            <p>Vous vous engagez à utiliser nos services d'envoi de courriels uniquement à des fins légales et légitimes. Il est strictement interdit d'utiliser ces services pour :</p>

            <ul>
                <li>L'envoi de messages non sollicités (spamming)</li>
                <li>L'envoi de contenu diffamatoire, offensant, illégal ou violant les droits d'autrui</li>
                <li>La diffusion de logiciels malveillants, virus, ou toute autre forme de contenu nuisible</li>
                <li>Toute tentative de phishing ou de fraude</li>
            </ul>
            
            <h5>4. Politique de confidentialité</h5>
            <p>Nous respectons votre vie privée et prenons des mesures pour protéger vos données personnelles. En utilisant nos services, vous consentez à la collecte, l'utilisation et le stockage de vos données conformément à notre politique de confidentialité, disponible [lien vers la politique de confidentialité].
            </p>
            
            <h5>5. Responsabilités de l'utilisateur</h5>
            <p>L'utilisateur est entièrement responsable du contenu des courriels qu'il envoie via notre plateforme. Vous vous engagez à vérifier que les adresses e-mail de vos destinataires sont valides et que les destinataires ont donné leur consentement pour recevoir vos communications.
            </p>

            <h5>6. Limitation de responsabilité</h5>
            <p>Nous nous efforçons de maintenir un service fiable et sécurisé, mais nous ne pouvons être tenus responsables des interruptions de service, de la perte de données, ou des dommages résultant de l'utilisation de nos services, dans la mesure permise par la loi.</p>

            <h5>7. Suspension ou résiliation de l'accès</h5>
            <p>Nous nous réservons le droit de suspendre ou de résilier votre accès à nos services en cas de violation de ces conditions, sans préavis. En cas d'activité illégale présumée, nous nous réservons également le droit de prendre toutes les mesures légales appropriées.</p>

            <h5>8. Modifications des conditions générales</h5>
            <p>Nous pouvons modifier ces conditions générales à tout moment. Les modifications seront publiées sur notre site web et entreront en vigueur dès leur mise en ligne. Il est de votre responsabilité de consulter régulièrement les termes et conditions pour rester informé des mises à jour.
            </p>

            <h5>9. Lois applicables</h5>
            <p>Ces conditions générales sont régies par les lois en vigueur dans le pays où est établi notre siège social. En cas de litige, les parties s'engagent à rechercher une solution amiable avant de recourir aux tribunaux compétents.</p>

            <h5>10. Contact</h5>
            <p>Pour toute question relative à ces conditions générales, veuillez nous contacter à l'adresse suivante : vanlangendonck.florent@gmail.com.</p>

            <h5>Dernière mise à jour : 26/08/2024</h5>
        </div>
    </main>
    <script src="../../Javascript/burgerMenu.js"></script>
</body>

</html>