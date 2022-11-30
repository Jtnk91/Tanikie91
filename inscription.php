<?php
require_once 'includes/init.php';
global $pdo;

if(isset($_SESSION['auth'])) {
    header('Location: index.php');
    exit;
}

if(isset($_POST['register'])) {
    // on récupère les champs
    $lastname = $_POST['lastname'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    $errors = array();

    // lastname format check (min 2 chars, max 50 chars, letters only)
    if(!preg_match('/^[a-zA-Z]{2,50}$/', $lastname)) {
        $errors['lastname'] = 'Le nom doit comporter entre 2 et 50 caractères et ne doit contenir que des lettres.';
    }

    // firstname format check (min 2 chars, max 50 chars, letters only)
    if(!preg_match('/^[a-zA-Z]{2,50}$/', $firstname)) {
        $errors['firstname'] = 'Le prénom doit comporter entre 2 et 50 caractères et ne doit contenir que des lettres.';
    }

    // username format check (min 6 chars, max 30 chars, letters and numbers only)
    if(!preg_match('/^[a-zA-Z0-9]{6,30}$/', $username)) {
        $errors['username'] = 'Le nom d\'utilisateur doit comporter entre 6 et 30 caractères et ne doit contenir que des lettres et des chiffres.';
    }

    // email format check
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'adresse email n\'est pas valide.';
    }

    // password format check (at least 10 chars, at least one lowercase and one digit)
    if(!preg_match('/^(?=.*[a-z])(?=.*[0-9]).{10,}$/', $password)) {
        $errors['password'] = 'Le mot de passe doit comporter au moins 10 caractères et doit contenir au moins une lettre minuscule et un chiffre.';
    }

    // password confirmation check
    if($password !== $password_confirm) {
        $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
    }

    // availability check
    $req = $pdo->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $req->execute(array(
        'username' => $username,
        'email' => $email
    ));

    if($req->rowCount() > 0) {
        $errors[] = 'Un utilisateur avec ce nom d\'utilisateur ou cette adresse email existe déjà.';
    }

    // if no errors, insert user in database
    if(empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // generate a random token
        $token = bin2hex(random_bytes(32));

        $req = $pdo->prepare('INSERT INTO users (name, prenom, username, email, password, confirmation_token) VALUES (:name, :prenom, :username, :email, :password, :confirmation_token)');
        $req->execute(array(
            'name' => $lastname,
            'prenom' => $firstname,
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password,
            'confirmation_token' => $token
        ));

        // send email
        $to = $email;
        $subject = 'Confirmation de votre compte';
        $url = 'http://tanikie.sc3antoineli.universe.wf/activate.php?token=' . $token;
        $message = 'Bonjour ' . $firstname . ' ' . $lastname . ', merci de vous être inscrit sur notre site. Pour confirmer votre compte, veuillez cliquer sur le lien suivant : ' . $url;

        mail($to, $subject, $message);

        // redirect to connexion page
        header('Location: connexion.php?register=success');
        exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <?php require_once('includes/partials/layout/head.php'); ?>
</head>
<body>
<?php require_once('includes/partials/layout/navbar.php'); ?>

<h1>Inscription</h1>

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
    <input type="text" name="lastname" placeholder="Nom" /><br />
    <input type="text" name="firstname" placeholder="Prénom" /><br />
    <input type="text" name="username" placeholder="Nom d'utilisateur" /><br />
    <input type="email" name="email" placeholder="Email" /><br /><br />
    <input type="password" name="password" placeholder="Mot de passe" /><br />
    <input type="password" name="password_confirm" placeholder="Répétez le mot de passe" /><br />

    <input type="submit" name="register" value="S'inscrire">
</form>

<?php require_once('includes/partials/dependencies/footer.php'); ?>
</body>
</html>