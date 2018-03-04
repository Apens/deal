<?php
/* on initialise la sessions */
session_start();
/* La racine du site à partir du repertroire www */
define('RACINE_WEB', '/deal/');

// répertoire photo dans le système de fichiers
define('PHOTO_DIR', __DIR__ . '/../photo/');

// url relative du répertoire photo
define('PHOTO_WEB', RACINE_WEB . 'photo/');

define('PHOTO_DEFAULT', 'https://dummyimage.com/200x200/cccccc/ffffff&text=Pas+de+photo');

/* on initialise la connextion à la BDD */
require_once __DIR__ . '/connexion.php';
require_once __DIR__ . '/fonctions.php';
