<?php
require_once('connexion.php');

function redirectTo($location) {
    header("Location: $location");
    exit();
}

function isLogged() {
    if (!isset($_SESSION['token']) || !isTokenValid($_SESSION['token'])) {
        redirectTo('/');
    }
}