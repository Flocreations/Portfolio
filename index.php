<?php
session_start();

// Import BDD 
require_once './service/db_connect.php';

if ($_SESSION) {
    $userRole = $_SESSION['role_id'];
    $username = $_SESSION['username'];
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    //Filtrage des données reçues par le formulaire 
    $_POST = filter_input_array(INPUT_POST, [
        'lastName' => FILTER_SANITIZE_SPECIAL_CHARS,
        'firstName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'object' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'mail' => FILTER_SANITIZE_EMAIL,
        'msg' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    // Instanciation des variables avec les données filtrées 

    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $object = $_POST['object'];
    $mail = $_POST['mail'];
    $msg = $_POST['msg'];

    // Préparation de la requete pour l'envoi en base de donnée 

    $request = 'INSERT INTO message(lastName, firstName, object, mail, msg) VALUES (:lastName , :firstName , :object, :mail, :msg)';
    $stmt = $db_connexion->prepare($request);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':object', $object);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':msg', $msg);
    $stmt->execute();
}

// Requete pour l'affichage automatique des projets dans la partie réalisations
$request = 'SELECT project_id, image_path FROM projects ORDER BY project_id DESC LIMIT 8';
$statement = $db_connexion->prepare($request);
$statement->execute();

$result = $statement->fetchAll(PDO::FETCH_ASSOC);

// Préparation de la concaténation et de la structure html dans une variable avec le foreach 

if (!empty($result)) {
    $cards = '';
    foreach ($result as $key => $value) {
        $cards .= '<div class="cards">
                    <img src="' . $value['image_path'] . '">
                  </div>';
    }
} else {
    $cards = "";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez mon portfolio de développeur web et mobile, spécialisé dans la création de sites web dynamiques. Explorez mes projets, compétences en développement front-end et back-end">

    <title>Portfolio|FV|</title>

    <link rel="stylesheet" href="./Style/main.css">
    <link rel="stylesheet" href="./Style/index/accueil.css">
    <link rel="stylesheet" href="./Style/index/aPropos.css">
    <link rel="stylesheet" href="./Style/index/competences.css">
    <link rel="stylesheet" href="./Style/index/realisations.css">
    <link rel="stylesheet" href="./Style/index/contact.css">
    <link rel="stylesheet" href="./Style/_nav.css">
    <link rel="stylesheet" href="./Style/_burger.css">
    <link rel="stylesheet" href="./Style/_footer.css">

    <script src="./Javascript/canvasAccueil.js" defer></script>
    <script src="./Javascript/burgerMenu.js" defer></script>
</head>

<body onload="onload()">
    <header>
        <?php
        // Import composants html
        include_once './view/components/_nav.php';
        include_once './view/components/_burger.php';
        ?>

    </header>
    <main>
        <section id="home">
            <canvas id="canvasHeader"></canvas>
            <div id="startsentence">
                <p id="hello"></p>
            </div>
        </section>
        <div id="cv">
            <div class="cadreBtn">
                <a id="btn-index" href="./Media/docs/CV.pdf" download="pour télécharger le CV ">Télécharger CV</a>
            </div>
            <div class="cadreBtn">
                <a id="btn-index" href="./View/cv/cv.php">CV Online</a>
            </div>
        </div>
        <section id="aPropos">
            <div id="titreAbout">
                <h3 id="titreapropos">A PROPOS</h3>
                <img id="fleche" src="media/images/fleche.svg" alt="fleche qui pointe vers le paragraphe de description">
            </div>
            <div id="presentation">
                <p id="hello">Bonjour,</p>
                <p id="intro">Je m’appelle Florent , j’ai 29 ans.</p>
                <p id="paragraphe">Issu d’une formation professionnelle en électrotechnique, autant dire que je ne suis pas du tout dans le domaine de ce qui est maintenant mon métier. J’ai également été téléconseillé pendant 10 ans dans un service technique, où j’ai pu résoudre des milliers de problèmes logiciels sur des smartphones, des tablettes et des ordinateurs d’une marque reconnue mondialement. C’est après cette expérience plutot conluante que j’ai décidé de me réorienter en tant que <strong>développeur Web et Web Mobile</strong>. Ce choix n’est pas anodin dans ma carrière, mais simplement la suite logique des choses, ayant toujours eu de l’intérêt pour le numérique. J’aime <strong>mon métier</strong>, participer et m’investir dans des <strong>projets</strong> aussi divers que variés. Et pourquoi pas <strong>VOTRE projet</strong>?
                </p>
            </div>
            <div id="gridImg">
                <div id="box1"></div>
                <div id="box2">
                    <p id="jaime"> J'AIME ♡ </p>
                </div>
                <div id="box3"></div>
                <div id="box4"></div>
                <div id="box5"></div>
                <div id="box6">
                    <p id="photographie"> LA PHOTO </p>
                </div>
                <div id="box7"></div>
                <div id="box8"></div>
                <div id="box9">
                    <p id="voyage"> VOYAGER </p>
                </div>
                <div id="box10"></div>
                <div id="box11"></div>
                <div id="box12">
                    <p id="rando"> LA RANDO </p>
                </div>
                <div id="box13"></div>
                <div id="box14"></div>
            </div>
        </section>
        <section id="competences">
            <p id="competencestitle">MES COMPETENCES</p>
            <h3 class="titleSectionCompetences" id="titleDivLangages">LANGAGES</h3>
            <div id="divLangages">
                <img class="imgLangages" src="media/images/html-5 (1).png" alt="html 5 icon">
                <img class="imgLangages" src="media/images/css-3.png" alt="css icon">
                <img class="imgLangages" src="media/images/js.png" alt="javascrip icon">
                <img class="imgLangages" src="media/images/php.png" alt="php icon">
                <img class="imgLangages" src="media/images/serveur-sql.png" alt="Sql icon">
            </div>

            <h3 class="titleSectionCompetences" id="titleDivLangages2">PLATEFORMES ET FRAMEWORKS</h3>
            <div id="divLangages2">
                <img class="imgLangages" src="media/images/icons8-vue-js-128.png" alt="VueJS icon">
                <img class="imgLangages" src="media/images/icons8-symfony-128.png" alt="Symfony icon">
                <img class="imgLangages" src="media/images/icons8-wordpress-128.png" alt="Wordpress icon">
                <img class="imgLangages" src="media/images/icons8-mysql-128.png" alt="MySQL icon">
            </div>
            <h3 class="titleSectionCompetences" id="titleDivLangages3">AUTRES COMPETENCES</h3>
            <div id="divLangages3">
                <img class="imgLangages" src="media/images/linux.png" alt="Linux icon">
                <img class="imgLangages" src="media/images/github.png" alt="Github icon">
                <img class="imgLangages" src="media/images/icons8-figma-128.png" alt="Figma icon">
                <img class="imgLangages" src="media/images/icons8-adobe-illustrator-128.png" alt="Adobe Illustrator icon">
                <img class="imgLangages" src="media/images/icons8-photoshop-128.png" alt="Adobe Photoshop icon">
            </div>
        </section>
        <section id="mesréalisations">
            <div>
                <p id="mesrealisationstitle">MES REALISATIONS</p>
            </div>
            <div id="netfolio">
                <div id="casebarrenav">
                    <p id="folioflux">FOLIOFLUX</p>
                    <div id="navdroite">
                        <?php if ($username === null) {
                            echo '<a id="btn-index" href="./View/login.php">Connexion</a>';
                        } else {
                            echo '<a id="btn-index" href="./View/users/profil.php">' . $username . '</a>';
                        } ?></a>
                    </div>

                </div>
                <div>
                    <p id="top3"> TOP 3</p>
                    <div id="menutop">
                        <div id="numvignettes1">
                            <p class="numtop">1</p>
                        </div>
                        <div id="numvignettes2">
                            <p class="numtop">2</p>
                        </div>
                        <div id="numvignettes3">
                            <p class="numtop">3</p>
                        </div>
                    </div>
                    <p class="titreprojet">Mes Projets</p>
                    <div id="cardsContainer">
                        <?= $cards ?>
                    </div>
                    <a id="btn-index" href="./View/projects/projects.php">Voir tous les projets</a>
                </div>
            </div>
        </section>
        <section id="contact">
            <div id="contact-container">
                <form action="#" id="formulaire" method="POST">
                    <h3 id="monContact">CONTACT</h3>
                    <input type="text" name="lastName" class="input" placeholder="Nom">
                    <input type="text" name="firstName" class="input" placeholder="Prénom">
                    <input type="text" name="mail" class="input" placeholder="Email">

                    <select name="object" id="objetForm" required>
                        <option value="">Sélectionnez l'objet du mail</option>
                        <option value="Proposition d'emploi">Proposition d'emploi</option>
                        <option value="Proposition de projet">Proposition de projet</option>
                        <option value="Autres">Autres</option>
                    </select>
                    <textarea name="msg" placeholder="Votre message ici"></textarea>
                    <div class="form-group">
                        <label for="acceptTerms">
                            <input type="checkbox" id="acceptTerms" name="acceptTerms">
                            J'accepte les <a href="./View/security/conditionsGenerales.php">termes et conditions</a>
                        </label>
                    </div>
                    <input type="submit" id="btnEnvoyer" value="Envoyer">
                </form>
                <div id="cercleOu">
                    <p>OU</p>
                </div>
                <div id="infoContact">
                    <p>flo.creations.dev@gmail.com</p>
                    <p>Horraires : <br>Lundi au Vendredi de 8h00 à 19h00</p>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer">
        <?php include_once './view/components/_footer.php'; ?>
    </footer>
</body>

</html>