<?php 
    if ($_SESSION) {
        $userRole = $_SESSION['role_id'];
        $username = $_SESSION['username'];
    }
?>
<nav>
            <ul class="nav-links">
                <li><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#aPropos" id="about">A propos</a></li>
                <li class="center"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#competences">Compétences</a></li>
                <li class="upward"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#mesréalisations">Réalisations</a></li>
                <li class="forward"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php#contact">Contact</a></li>
                <?php
                if (!empty($userRole)) {
                    if ($userRole === 2) {
                        echo '
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">' . htmlspecialchars($username) . '</a>
                            <ul class="dropdown-menu">
                                <li><a href="/view/users/profil.php">Profil</a></li>
                                <li><a href="/service/logout.php">Déconnexion</a></li>
                            </ul>
                        </li>';
                    } else {
                        echo '
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">' . htmlspecialchars($username) . '</a>
                            <ul class="dropdown-menu">
                                <li><a href="/View/admin/administration/administration.php">Outils</a></li>
                                <li><a href="/view/users/profil.php">Profil</a></li>
                                <li><a href="/service/logout.php">Déconnexion</a></li>
                            </ul>
                        </li>';
                    }
                } else {
                    echo '<li><a href="/View/security/login.php">Connexion</a></li>';
                }
                ?>
            </ul>
        </nav>