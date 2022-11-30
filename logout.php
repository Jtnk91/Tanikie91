<?php
session_start();
// destroy all sessions
session_destroy();
// redirect to login page
header('Location: connexion.php');
exit;