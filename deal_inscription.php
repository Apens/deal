<?php
require_once __DIR__. '/include/init.php';

$errors = [];
$civilite = $nom = $prenom = $email
        = $telephone = $pseudo = $cp = ''
;

if (!empty($_POST)) { // le formulaire est envoyé
    // cf include/fonctions.php
    sanitizePost();
    // crée des variables à partir d'un tableau
    // $_POST['nom'] = 'Anest' crée $nom = 'Anest', etc.
    extract($_POST); 
    
    if (empty($_POST['civilite'])) {
        $errors[] = 'La civilité est obligatoire';
    }
    
    if (empty($_POST['nom'])) {
        $errors[] = 'Le nom est obligatoire';
    }
    
    if (empty($_POST['prenom'])) {
        $errors[] = 'Le prénom est obligatoire';
    }
    
    if (empty($_POST['email'])) {
        $errors[] = "L'email est obligatoire";
    // test de la validité de l'adresse mail :
    } elseif (!filter_var(
        $_POST['email'],
        FILTER_VALIDATE_EMAIL)
    ) {
        $errors[] = "L'email est invalide";
    // test de l'unicité de l'adresse mail :
    } else {
        // compte le nb d'utilisateur qui ont
        // l'email venant du formulaire
        $req = 'SELECT count(*) FROM membre'
            . ' WHERE email = ' . $pdo->quote($_POST['email'])
        ;
        // $pdo->query() envoie la requête à la bdd
        // et retourne un objet PDOStatement
        $stmt = $pdo->query($req);
        $nb = $stmt->fetchColumn();
        // si l'email existe déjà en bdd :
        if ($nb != 0) {
            $errors[] = 'Cet email existe déjà';
        }
    }
    
    if (empty($_POST['pseudo'])) {
        $errors[] = "Le pseudo est obligatoire";
    }
    
    if (empty($_POST['telephone'])) {
        $errors[] = 'Le numero de téléphone est obligatoire';
    } elseif (
    // strlen() compte le nb de caractères de la chaîne
    // ctype_digit() retourne false si la chaîne
    // ne contient pas que des nombres
        strlen($_POST['telephone']) != 10
        || !ctype_digit($_POST['telephone'])
    ) {
        $errors[] = 'Le numero est invalide';
    }
    
    
    if (empty($_POST['mdp'])) {
        $errors[] = 'Le mot de passe est obligatoire';
    // test du mdp sur l'expression régulière
    } elseif (!preg_match( 
        '/^[a-zA-Z0-9_-]{6,20}$/',
        $_POST['mdp'])) {
        $errors[] = 'Le mot de passe doit faire'
            . ' entre 6 et 20 caractères'
            . ' et ne contenir que des lettres, des chiffres'
            . ' ou les caractères _ et -'
        ;
    }
    
    if (empty($_POST['confirmation_mdp'])) {
        $errors[] = 'La confirmation du mot de passe'
            . ' est obligatoire'
        ;
    } elseif ($_POST['confirmation_mdp'] != $_POST['mdp']) {
        $errors[] = 'Le mot de passe et sa confirmation'
            . ' ne sont pas identiques'
        ;
    }
    
    // si tous les champs sont correctement remplis
    if (empty($errors)) {
        // encrypte le mdp avec l'algo bcrypt
        $encodedPassword = password_hash(
            $mdp,
            PASSWORD_BCRYPT
        );
        
        $req = <<<EOS
INSERT INTO membre(
    nom,
    prenom,
    email,
    mdp,
    civilite,
    pseudo,
    telephone
) VALUES (
    :nom,
    :prenom,
    :email,
    :mdp,
    :civilite,
    :pseudo,
    :telephone       
)
EOS;
        
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':mdp', $encodedPassword);
        $stmt->bindValue(':civilite', $civilite);
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->bindValue(':telephone', $telephone);
        $stmt->execute();
        // $success est utilisé dans le HTML
        // pour afficher le message de confirmation
        $success = true;
    } // fin if (empty($errors))
    setFlashMessage('Votre compte est créé');
        header('Location: deal_connexion.php');
        die;
} // fin de if (!empty($_POST))

include __DIR__.'/layout/top.php';
?>
    <h1>Inscription</h1>
    <?php
// syntaxe longue avec : ... endif;
// à l'intérieur du HTML au lieu des accolades
if (!empty($errors)) :
// le bloc div ne s'affiche que si
// l'on entre dans la condition
?>
        <div class="alert alert-danger">
            <strong>Le formulaire contient des erreurs</strong>
            <br>
            <?php
        // '<?=' est la notation courte pour
        // '<?php echo'
        // on affiche les valeurs des éléments du tableau
        // séparés par des <br>
        ?>
                <?= implode('<br>', $errors); ?>
        </div>
        <?php
endif;
if (isset($success)) :
?>
            <div class="alert alert-success">
                <strong>Votre compte est créé</strong>
            </div>
            <?php
endif;
?>
            <form method="post">
                <div class="form-group">
                    <label>Civilité</label>
                    <select name="civilite" class="form-control">
            <option value=""></option>
            <option value="M." <?php if ($civilite == 'M.') {echo 'selected';} ?>>M.</option>
            <option value="Mme" <?php if ($civilite == 'Mme') {echo 'selected';} ?>>Mme</option>
        </select>
                </div>
                <div class="form-group">
                    <label>Nom</label>
                    <input name="nom" type="text" value="<?= $nom; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input name="prenom" type="text" value="<?= $prenom; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="text" value="<?= $email; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Pseudo</label>
                    <input name="pseudo" type="text" value="<?= $pseudo; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>telephone</label>
                    <input name="telephone" type="text" value="<?= $telephone; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input name="mdp" type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirmation du mot de passe</label>
                    <input name="confirmation_mdp" type="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
        Enregistrer
    </button>
            </form>

            <?php
include __DIR__ . '/layout/bottom.php';
?>
