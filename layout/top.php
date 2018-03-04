<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boutique</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        .navbar-inverse {
            margin-bottom: 0;
        }

    </style>
</head>

<body>
    <?php
        if (isUserAdmin()) :
        ?>
        <nav class="navbar navbar-inverse">
            <div class="container">
                <a class="navbar-brand">
                        Admin
                    </a>
                <ul class="nav navbar-nav">
                    <li>
                        <a href="<?= RACINE_WEB; ?>admin/gestion_membres.php">
                                Gestion membres
                            </a>
                    </li>
                    <li>
                        <a href="<?= RACINE_WEB; ?>admin/gestion_categories.php">
                                Gestion catégories
                            </a>
                    </li>
                    <li>
                        <a href="<?= RACINE_WEB; ?>admin/gestion_annonces.php">
                                Gestion annonces
                            </a>
                    </li>
                    <li>
                        <a href="<?= RACINE_WEB; ?>admin/gestion_commentaires.php">
                                Gestion commentaires
                            </a>
                    </li>
                    <li>
                        <a href="<?= RACINE_WEB; ?>admin/gestion_notes.php">
                                Gestion notes
                            </a>
                    </li>
                    <li>
                        <a href="<?= RACINE_WEB; ?>admin/gestion_statistique.php">
                                Gestion statistique
                            </a>
                    </li>

                </ul>
            </div>
        </nav>
        <?php
        endif;
        ?>
            <nav class="navbar navbar-default">
                <div class="container">
                    <a class="navbar-brand" href="<?= RACINE_WEB; ?>index.php">
                        <b>Deal</b>
                </a>
                    <?php
                include __DIR__ . '/menu-categories.php';
                ?>
                        <ul class="nav navbar-nav navbar-right">
                            <?php
                    if (isUserConnected()) :
                    ?>
                                <li>
                                    <a href="<?= RACINE_WEB; ?>deal_depot_annonce.php">
                                Voir les Deal{s}
                            </a>
                                </li>
                                <li>
                                    <a href="<?= RACINE_WEB; ?>deal_depot_annonce.php">
                                Proposer un Deal
                            </a>
                                </li>
                                <li>
                                    <a href="<?= RACINE_WEB; ?>deal_monprofil.php"><span class="glyphicon glyphicon-user"></span>
                                        <?= getUserPseudo(); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= RACINE_WEB; ?>deal_deconnexion.php">
                                Déconnexion
                            </a>
                                </li>
                                <?php
                    else :
                    ?>
                                    <li>
                                        <a href="<?= RACINE_WEB; ?>deal_depot_annonce.php">
                                Proposer un Deal
                            </a>
                                    </li>
                                    <li>
                                        <a href="<?= RACINE_WEB; ?>deal_inscription.php">
                                Inscription
                            </a>
                                    </li>
                                    <li>
                                        <a href="<?= RACINE_WEB; ?>deal_connexion.php">
                                Connexion
                            </a>
                                    </li>
                                    <?php
                    endif;
                    ?>

                        </ul>
                </div>
            </nav>
            <div class="container">
