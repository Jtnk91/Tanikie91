<?php
require_once 'includes/init.php';
global $pdo;

if(isset($_SESSION['auth'])) {
    header('Location: index.php');
    exit;
}

if(isset($_POST['login'])) {
    $login = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $req = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $req->execute(array(
        'username' => $login
    ));

    $user = $req->fetch();

    if($user && password_verify($password, $user->password)) {
        if($user->enabled == 1) {
            $_SESSION['auth'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Votre compte n'est pas activé, veuillez vérifier vos mails.";
        }
    } else {
        $errors[] = 'Identifiants incorrects.';
    }
}

if(isset($_GET['error']) && $_GET['error'] == 'invalidToken') {
    $errors[] = 'Ce token n\'est pas valide.';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <?php require_once('includes/partials/layout/head.php'); ?>
</head>
<body>
<?php require_once('includes/partials/layout/navbar.php'); ?>

<h1>Connexion</h1>

<?php
if(isset($_GET['register']) && $_GET['register'] == 'success') {
    echo '<div class="alert alert-success" role="alert">Inscription réussie ! Activez votre compte via le lien envoyé par email</div>';
}
?>

<?php
if(!empty($errors)) {
    echo '<div class="alert alert-danger" role="alert">';
    echo 'Des erreurs ont été détectées : <br />';
    foreach($errors as $error) {
        echo $error . '<br />';
    }
    echo '</div>';
}
?>

<form action="#" method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur" /><br />
    <input type="password" name="password" placeholder="Mot de passe" /><br />
    <input type="submit" name="login" value="Se connecter">
</form>

<div style="margin-top: 50px;">
    <a href="inscription.php">Pas encore de compte ? Inscrivez-vous !</a>
</div>

<?php require_once('includes/partials/dependencies/footer.php'); ?>
</body>
</html>