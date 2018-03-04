<?php
require_once __DIR__. '/include/init.php';

$email = '';
$errors = [];

if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);
    
    if (empty($_POST['email'])) {
        $errors[] = "L'email est obligatoire";
    }
    
    if (empty($_POST['mdp'])) {
        $errors[] = 'Le mot de passe est obligatoire';
    }
    
    if (empty($errors)) {
        $req = 'SELECT * FROM membre'
            . ' WHERE email = ' . $pdo->quote($email)
        ;
        $stmt = $pdo->query($req);
        $membre = $stmt->fetch();
        
        // s'il y a un utilisateur en bdd avec cet email
        if (!empty($membre)) {
            // vérification du mdp
            if (password_verify($mdp, $membre['mdp'])) {
                // connecter un utilisateur
                // c'est l'enregistrer dans la session
                $_SESSION['membre'] = $membre;
                // redirection vers l'accueil
                header('Location: index.php');
                die; // stoppe l'exécution du script
            }
        }
        
        $errors[] = 'Identifiant ou mot de passe incorrect';
    }
}

include __DIR__.'/layout/top.php';
?>
    <h1>Connexion</h1>

    <?php
displayFlashMessage();
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
                <label>Email</label>
                <input name="email" type="text" value="<?= $email; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input name="mdp" type="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">
        Valider
    </button>
        </form>

        <?php
include __DIR__ . '/layout/bottom.php';
?>
