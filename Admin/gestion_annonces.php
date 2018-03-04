<?php
// lister les produit dans un tableau HTML
// sans tenir compte de la photo
// sans afficher la description
// avec le nom de la catégorie et pas son id
require_once __DIR__ . '/../include/init.php';
adminSecurity();

$req = 'SELECT a.*, c.titre AS titre_categorie'
    . ' FROM annonce a'
    . ' JOIN categorie c ON a.categorie_id = c.id'
;

$stmt = $pdo->query($req);
$annonces = $stmt->fetchAll();

include __DIR__ . '/../layout/top.php';
?>
<h1>Gestion annonces</h1>
<?php
displayFlashMessage();
?>
<!--
<p>
    <a href="gestion_annonces-edit.php">Ajouter une annonce</a>
</p>
-->

<table class="table">
    <tr>
        <th>Id</th>
        <th>Nom</th>
        <th>description courte</th>
        <th>description longue</th>
        <th>Prix</th>
        <th>Photo</th>
        <th>Pays</th>
        <th>Ville</th>
        <th>Adresse</th>
        <th>Code postal</th>
        <th>Membre</th>
        <th>Catégorie</th>
        <th>Deposé le</th>
        <th width="200px"></th>
    </tr>
    <?php
    foreach ($annonces as $annonce) :
    ?>
        <tr>
            <td><?= $annonce['id']; ?></td>
            <td><?= $annonce['titre']; ?></td>
            <td><?= $annonce['description_courte']; ?></td>
            <td><?= $annonce['description_longue']; ?></td>
            <td><?= number_format($annonce['prix'], 2, ',', ' '); ?> €</td>
            <td> 
            <figure  >
                <img src="<?= PHOTO_WEB .$annonce['photo']; ?>" width=" 50px" height="50px">
            </figure>
            </td>
            <td><?= $annonce['pays']; ?></td>
            <td><?= $annonce['ville']; ?></td>
            <td><?= $annonce['adresse']; ?></td>
            <td><?= $annonce['code_postal']; ?></td>
            <td><?= $annonce['membre_id']; ?></td>
            <td><?= $annonce['titre_categorie']; ?></td>
            <td><?= $annonce['date_enregistrement']; ?></td>
            <td>
                <a href="gestion_annonces-edit.php?id=<?= $annonce['id']; ?>"
                   class="btn btn-primary">
                    Modifier
                </a>
                <a href="gestion_annonces-delete.php?id=<?= $annonce['id']; ?>"
                   class="btn btn-danger">
                    Supprimer
                </a>
            </td>
        </tr>
    <?php
    endforeach;
    ?>
</table>

<?php
include __DIR__ . '/../layout/bottom.php';
?>