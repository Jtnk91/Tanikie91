<?php
require_once 'includes/init.php';
global $pdo;

if(isset($_SESSION['auth'])) {
    header('Location: index.php');
    exit;
}

if(isset($_GET['token'])) {
    $req = $pdo->prepare('SELECT * FROM users WHERE confirmation_token = :confirmation_token AND enabled = 0');
    $req->execute(array(
        'confirmation_token' => $_GET['token']
    ));

    $user = $req->fetch();

    if($user) {
        $pdo->prepare('UPDATE users SET enabled = 1, confirmation_token = NULL WHERE id = :id')->execute(array(
            'id' => $user->id
        ));
        $_SESSION['auth'] = $user;
        header('Location: index.php?account=activated');
    } else {
        header('Location: connexion.php?error=invalidToken');
    }
} else {
    header('Location: connexion.php');
}