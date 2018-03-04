<?php
require_once __DIR__. '/../include/init.php';
// on appel la fonction qui verifie si le membre est admin
adminSecurity();

$titre = $keywords = '';
// Avant d'enregistrer les informations du formulaire dans la bdd, on effectue la verification des champs, a savoir si champ !null
// dans le cas ou le champ est null, alors on renvoie un message d'erreur
$error = [];

if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);
    // test des valeurs du formulaire
    if (empty($_POST['keywords'])) {
        $errors[] = 'Les mots-clés sont obligatoire';
    }
    if (empty($_POST['titre'])) {
        $errors[] = 'Le titre est obligatoire';
    } else {
        $req = 'SELECT count(*) FROM categorie'
            . ' WHERE titre = ' . $pdo->quote($_POST['titre'])
        ;
        
        // en modification, on exclut de la requête
        // la catégorie que l'on est en train de modifier
        if (isset($_GET['id'])) {
            $req .= ' AND id != ' . (int)$_GET['id'];
        }
        
        $stmt = $pdo->query($req);
        $nb = $stmt->fetchColumn();
        
        if ($nb != 0) {
            $errors[] = 'Cette catégorie existe déjà';
        }
    }
    // fin des tests
    
    // si le formulaire est bien rempli
    // on enregistre en bdd
    if (empty($errors)) {
        if (isset($_GET['id'])) { 
            // modification
            $req = '
            UPDATE categorie SET titre = :titre, mots_cles= :keywords 
            WHERE id = :id'
            ;
            $stmt = $pdo->prepare($req);
            $stmt->bindValue(':titre', $titre);
            $stmt->bindValue(':id', $_GET['id']);
            $stmt->bindValue(':keywords', $keywords);
            $stmt->execute();
        } else { // création
            $req = 'INSERT INTO categorie(titre, mots_cles) VALUES (:titre, :keywords)';
            $stmt = $pdo->prepare($req);
            $stmt->bindValue(':titre', $titre);
            $stmt->bindValue(':keywords', $keywords);
            $stmt->execute();
        }
// enregistrement du message de confirmation
        // en session, puis redirection vers la liste
        setFlashMessage('La catégorie est enregistrée');
       header('Location: gestion_categories.php');
       die;
    }
} elseif (isset($_GET['id'])) {
    // s'il y a une id dans l'url
    // et qu'il n'y a pas de retour de formulaire
    // on va chercher la catégorie en bdd
    $req = 'SELECT * FROM categorie'
        . ' WHERE id=' . (int)$_GET['id']
    ;
    $stmt = $pdo->query($req);
    $categorie = $stmt->fetch();
    
    $titre = $categorie['titre'];
} 
include __DIR__.'/../layout/top.php';
?>
<h1>Edition des catégories</h1>
<?php
if (!empty($errors)) :
?>
    <div class="alert alert-danger">
        <strong>Le formulaire contient des erreurs</strong>
        <br>
        <?= implode('<br>', $errors); ?>
    </div>
<?php
endif;
?>
<form method="post">
    <div class="form-group">
        <label>Nom</label>
        <input type="text" class="form-control"
            name="titre" value="<?= $titre; ?>">
        <label>Mots-clés</label>
        <input type="text" class="form-control"
            name="keywords" value="<?= $keywords; ?>">
        
    </div>
    <button type="submit" class="btn btn-primary">
        Enregistrer
    </button>
    <a href="gestion_categories.php" class="btn btn-default">
        Retour
    </a>
</form>
<?php
include __DIR__ . '/../layout/bottom.php';
?>
