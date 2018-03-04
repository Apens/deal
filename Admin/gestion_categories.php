<?php
require_once __DIR__. '/../include/init.php';
// on appel la fonction qui verifie si le membre est admin
adminSecurity();

// nous listons dans une tableau toute les catégories de la BDD
 $stmt = $pdo->query('SELECT * FROM categorie');
$categories = $stmt->fetchAll();

include __DIR__.'/../layout/top.php';
?>
<h1>Gestion categories</h1>
<?php
displayFlashMessage();
?>
<p>
    <a href="gestion_categories-edit.php">Ajouter une catégorie</a>
</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Nom</th>
        <th>Mots clés</th>
        <th width="200px"></th>
    </tr>
<?php
    foreach ($categories as $categorie) :
?>
<tr>
            <td><?= $categorie['id']; ?></td>
            <td><?= $categorie['titre']; ?></td>
            <td><?= $categorie['mots_cles']; ?></td>
            
            <td>
                <a href="gestion_categories-edit.php?id=<?= $categorie['id']; ?>"
                   class="btn btn-primary">
                    Modifier
                </a>
                <a href="gestion_categorie-delete.php?id=<?= $categorie['id']; ?>"
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
