<?php
    if ($_SESSION) {
        $userRole = $_SESSION['role_id'];
        $username = $_SESSION['username'];
    }
?>
<div id="barreLaterale">
    <ul class="menu">
        <button class="closeButton closeIcon material-icons">FERMER</button>
        <li><a class="menuItem" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#apropos">A propos</a></li>
        <li><a class="menuItem" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#competences">Competences</a></li>
        <li><a class="menuItem" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#mesréalisations">Réalisations</a></li>
        <li><a class="menuItem" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#contact">Contact</a></li>
        <?php
                if (!empty($userRole)) {
                    if ($userRole === 2) {
                        echo '
                        <li class="dropdown">
                            <h4 class="dropdown-toggle"  '. htmlspecialchars($username) .'</h4>
                                <li><a href="/view/users/profil.php">Profil</a></li>
                                <li><a href="/service/logout.php">Déconnexion</a></li>
                        </li>';
                    } else {
                        echo '
                        <li class="dropdown">
                            <h4 class="dropdown-toggle"' . htmlspecialchars($username) . '</a>
                        
                                <li><a href="/View/admin/administration/administration.php">Outils</a></li>
                                <li><a href="/view/users/profil.php">Profil</a></li>
                                <li><a href="/service/logout.php">Déconnexion</a></li>
                        </li>';
                    }
                } else {
                    echo '<li><a class="btnContact" href="./View/security/login.php">Connexion</a></li>';
                }
        ?>
    </ul>
    <button class="hamburger">
        <!-- Icône du menu -->
        <img src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/Media/images/icons8-menu-26.png" alt="icone du menu burger">
    </button>
</div>