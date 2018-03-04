<?php
require_once __DIR__. '/include/init.php';
//adminSecurity();
isUserConnected();
$titre = $shortdesc = $longdesc = $prix = $pays = $ville = $adresse = $cp =$membre = $categorie = $photoActuelle = '';
$errors = [];

if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);
    
    if (empty($_POST['titre'])) {
        $errors[] = 'Le titre est obligatoire';
    }
    
    if (empty($_POST['description_courte'])) {
        $errors[] = 'La description est obligatoire';
    }
    
    if (empty($_POST['description_longue'])) {
        $errors[] = 'La description est obligatoire';
    }
    
    if (empty($_POST['prix'])) {
        $errors[] = 'Le prix est obligatoire';
    }
    
    if (empty($_POST['pays'])) {
        $errors[] = 'Le pays est obligatoire';
    }
    
     if (empty($_POST['ville'])) {
        $errors[] = 'La ville est obligatoire';
    }
    
    if (empty($_POST['adresse'])) {
        $errors[] = 'L\'adresse est obligatoire';
    }
    
    if (empty($_POST['code_postal'])) {
        $errors[] = 'Le code postal est obligatoire';
    }
    
    if (empty($_POST['categorie'])) {
        $errors[] = 'La catégorie est obligatoire';
    }
    
    if (!empty($_FILES['photo']['tmp_name'])) {
        if ($_FILES['photo']['size'] > 1000000) {
            $errors[] = 'La photo ne doit pas faire'
                . ' plus de 1Mo'
            ;
        }
        
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif'
        ];
        
        if (!in_array($_FILES['photo']['type'], $allowedMimeTypes)) {
            $errors[] = 'La photo doit être une image'
                . ' GIF, JPG ou PNG'
            ;
        }
    }
    
    if (empty($errors)) {
        // traitement de l'image
        if (!empty($_FILES['photo']['tmp_name'])) {
            // on retrouve l'extension du fichier à partir
            // de son nom original
            $dotPosition = strrpos($_FILES['photo']['name'], '.');
            $extension = substr($_FILES['photo']['name'], $dotPosition);
            $nomFichier = substr(md5(time()), 0, 10) . $extension;
            
            // en modification, si le produit avait
            // déjà une photo, on la supprime
            if (!empty($photoActuelle)) {
                unlink(PHOTO_DIR . $photoActuelle);
            }
            
            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                PHOTO_DIR . $nomFichier
            );
            
        } else {
            $nomFichier = $photoActuelle;
        }
        
        if (isset($_GET['id'])) {
            $req = <<<EOS
UPDATE produit SET
    titre = :titre,
    description_courte = :description_courte,
    description_longue = :description_longue,
    prix = :prix,
    photo = :photo,
    pays = :pays,
    ville = :ville,
    adresse = :adresse,
    code_postal = :code_postal,
    categorie_id = :categorie,
    membre_id = :membre
WHERE id = :id
EOS;
            $stmt = $pdo->prepare($req);
            $stmt->bindValue(':titre', $_POST['titre']);
            $stmt->bindValue(':description_courte', $_POST['description_courte']);
            $stmt->bindValue(':description_longue', $_POST['description_longue']);
            $stmt->bindValue(':prix', $_POST['prix']);
            $stmt->bindValue(':pays', $_POST['pays']);
            $stmt->bindValue(':ville', $_POST['ville']);
            $stmt->bindValue(':adresse', $_POST['adresse']);
            $stmt->bindValue(':code_postal', $_POST['code_postal']);
            $stmt->bindValue(':categorie', $_POST['categorie_id']);
            $stmt->bindValue(':membre', $_POST['membre_id']);
            $stmt->bindValue(':id', $_GET['id']);
            
            if (!empty($nomFichier)) {
                $stmt->bindValue(':photo', $nomFichier);
            } else {
                $stmt->bindValue(':photo', null, PDO::PARAM_NULL);
            }
            
            $stmt->execute();
        } else {
            // syntaxe heredoc
            $req = <<<EOS
INSERT INTO annonce(
    titre,
    description_courte,
    description_longue,
    prix,
    pays,
    ville,
    adresse,
    code_postal,
    categorie_id,
    membre_id,
    photo
) VALUES (
    :titre,
    :description_courte,
    :description_longue,
    :prix,
    :pays,
    :ville,
    :adresse,
    :code_postal,
    :categorie,
    :membre,
    :photo
)
EOS;
            $stmt = $pdo->prepare($req);
            $stmt->bindValue(':titre', $_POST['titre']);
            $stmt->bindValue(':description_courte', $_POST['description_courte']);
            $stmt->bindValue(':description_longue', $_POST['description_longue']);
            $stmt->bindValue(':prix', $_POST['prix']);
            $stmt->bindValue(':pays', $_POST['pays']);
            $stmt->bindValue(':ville', $_POST['ville']);
            $stmt->bindValue(':adresse', $_POST['adresse']);
            $stmt->bindValue(':code_postal', $_POST['code_postal']);
            $stmt->bindValue(':categorie', $_POST['categorie']);
            $stmt->bindValue(':membre', $_SESSION['membre']['id']);

            
            if (!empty($nomFichier)) {
                $stmt->bindValue(':photo', $nomFichier);
            } else {
                $stmt->bindValue(':photo', null, PDO::PARAM_NULL);
            }
            
            $stmt->execute();
        }
        
 setFlashMessage('Le produit est enregistré'); header('Location: index.php'); die;        
    }
} elseif (isset($_GET['id'])) {
    $req = 'SELECT * FROM produit WHERE id='
        . (int)$_GET['id']
    ;
    $stmt = $pdo->query($req);
    $annonce = $stmt->fetch();
    
    extract($annonce);
    $categorie = $annonce['categorie_id'];
    $photoActuelle = $annonce['photo'];
}

$req = 'SELECT * FROM categorie';
$stmt = $pdo->query($req);
$categories = $stmt->fetchAll();

include __DIR__ . '/layout/top.php';
?>
<h1>Propose ton Deal</h1>

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
<!--
L'attribut enctype est obligatoire pour un formulaire
qui contient un téléchargement de fichier
-->
<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>titre</label>
        <input type="text" class="form-control"
            name="titre" value="<?= $titre; ?>">
    </div>
    <div class="form-group">
        <label>Description courte</label>
        <input type="text" class="form-control"
            name="description_courte" value="<?= $shortdesc; ?>">
    </div>
    <div class="form-group">
        <label>Description longue</label>
        <textarea name="description_longue"
            class="form-control"><?= $longdesc; ?></textarea>
    </div>
    <div class="form-group">
        <label>Prix</label>
        <input type="text" class="form-control"
            name="prix" value="<?= $prix; ?>">
    </div>
    <div class="form-group">
        <label>Photo</label>
        <input type="file" name="photo">
    </div>
    <input type="hidden" name="photoActuelle"
        value="<?= $photoActuelle; ?>">
    <?php
    if (!empty($photoActuelle)) :
    ?>
        <p>
            <img src="<?= PHOTO_WEB . $photoActuelle; ?>"
                 height="150px">
        </p>
    <?php
    endif;
    ?>
    <div class="form-group">
        <label>Pays</label>
        <input type="text" class="form-control"
            name="pays" value="<?= $pays; ?>">
    </div>
    <div class="form-group">
        <label>Ville</label>
        <input type="text" class="form-control"
            name="ville" value="<?= $ville; ?>">
    </div>
    
    <div class="form-group">
        <label>Adresse</label>
        <textarea name="adresse"
            class="form-control"><?= $adresse; ?></textarea>
    </div>
    
    <div class="form-group">
        <label>CP</label>
        <input type="text" class="form-control"
            name="code_postal" value="<?= $cp; ?>">
    </div>
    
    <div class="form-group">
        <label>Catégorie</label>
        <select name="categorie" class="form-control">
            <option value=""></option>
            <?php
            foreach ($categories as $cat) :
                $selected = ($cat['id'] == $categorie)
                    ? 'selected'
                    : ''
                ;
            ?>
                <option value="<?= $cat['id']; ?>"
                    <?= $selected; ?>>
                    <b><?= $cat['titre']; ?></b>
                </option>
            <?php
            endforeach;
            ?>
        </select>
    </div>
    <?php
    ?>

    <button type="submit" class="btn btn-primary">
        Enregistrer
    </button>
    <a href="deal_mesannonces.php" class="btn btn-default">
        Retour
    </a>
</form>


<?php
include __DIR__ . '/layout/bottom.php';
?>
