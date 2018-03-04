<?php
require_once __DIR__ . '/../include/init.php';
adminSecurity();

$req = 'SELECT photo FROM annonce WHERE id = '
    . (int)$_GET['id']
;

$stmt = $pdo->query($req);
$photo = $stmt->fetchColumn();

// si le produit a une photo, on la supprime
if (!empty($photo)) {
    unlink(PHOTO_DIR . $photo);
}

$req = 'DELETE FROM annonce'
    . ' WHERE id = ' . (int)$_GET['id']
;

$pdo->exec($req);

setFlashMessage('L\'annonce est supprimé');
header('Location: gestion_annonces.php');
die;