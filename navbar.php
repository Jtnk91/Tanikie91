<?php
$links = array(
    'index.php' => array(
        'title' => 'Accueil',
        'logged' => true
    ),
    'inscription.php' => array(
        'title' => 'Inscription',
        'logged' => false,
    ),
    'connexion.php' => array(
        'title' => 'Connexion',
        'logged' => false
    ),
    'logout.php' => array(
        'title' => 'Déconnexion',
        'logged' => true
    ),
);
?>
<div class="sticky">
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <?php foreach($links as $link => $data) : ?>
                <?php if($data['logged'] == isset($_SESSION['auth'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link ?>"><?= $data['title'] ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>