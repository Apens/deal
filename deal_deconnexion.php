<?php
require_once __DIR__ . '/include/init.php';

// supprime l'entrée membre dans le tableau $_SESSION
unset($_SESSION['membre']);

// $_SERVER['HTTP_REFERER'] : fait reference à la page de laquelle on vient
$redirect = (!empty($_SERVER['HTTP_REFERER']))
    ? $_SERVER['HTTP_REFERER']
    : 'index.php'
;

header('Location: ' . $redirect);
die;
