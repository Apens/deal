<?php
require_once __DIR__ . '/../include/init.php';
adminSecurity();

// vérifier si la catégorie contient des produits
// si oui, ne pas faire la suppression et
// rediriger avec un message d'erreur
$req = 'SELECT count(*) FROM annonce'
    . ' WHERE categorie_id = ' . (int)$_GET['id']
;
$stmt = $pdo->query($req);
$nbAnnonce = $stmt->fetchColumn();

if ($nbAnnonce == 0) {
    $req = 'DELETE FROM categorie'
        . ' WHERE id = ' . (int)$_GET['id']
    ;

    $pdo->exec($req);

    setFlashMessage('La catégorie est supprimée');
} else {
    $message = 'La catégorie ne peut pas être'
        . ' supprimée car elle contient des produits'
    ;
    setFlashMessage($message, 'error');
}

header('Location: gestion_categories.php');
die;
