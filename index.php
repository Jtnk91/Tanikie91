<?php
require_once 'includes/init.php';

if(!isset($_SESSION['auth'])) {
    header('Location: connexion.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <?php require_once('includes/partials/layout/head.php'); ?>
</head>
<body>
<?php require_once('includes/partials/layout/navbar.php'); ?>

<h1>look the movie</h1>

<?php
if(isset($_GET['account']) && $_GET['account'] == 'activated') {
    echo '<div class="alert alert-success" role="alert">Votre compte a bien été activé !</div>';
}
?>

<form id="searchForm">
    <input type="text" placeholder="Entrez le titre d'un film" id="searchInput"><br>
    <input type="submit" value="Rechercher">
</form>
<div class="result-container">
    <ul id="result"></ul>
</div>

<?php require_once('includes/partials/dependencies/footer.php'); ?>
</body>
</html>