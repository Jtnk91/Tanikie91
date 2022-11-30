<?php
session_start();

const ENV = "dev"; // dev or prod

// log everything in dev mode
if (ENV === "dev") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

const BDD_USER = "sc3antoineli_tanikie_user";
const BDD_PASSWD = "antoineXtanikie"; // TODO: use env variable
const BDD_SERVER = "localhost";
const BDD_BASE = "sc3antoineli_tanikie";

$dsn = "mysql:host=" . BDD_SERVER . ";dbname=" . BDD_BASE . ";charset=utf8";

try {
    $pdo = new PDO($dsn, BDD_USER, BDD_PASSWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // object mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}