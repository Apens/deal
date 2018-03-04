<?php
// nettoie une valeur
function sanitizeValue(&$value)
{
    $value = trim(strip_tags($value));
    // trim() supprime les espaces en début et fin de chaîne
    // strip_tags() supprime les balises HTML
}

// nettoie un tableau
function sanitizeArray(array &$array)
{
    // applique la fonction sanitizeValue()
    // à toutes les valeurs du tableau
    array_walk($array, 'sanitizeValue');
}

// nettoie le tableau $_POST
function sanitizePost()
{
    sanitizeArray($_POST);
}

function isUserConnected()
{
    return isset($_SESSION['membre']);
}

function getUserFullName()
{
    if (isUserConnected()) {
        return $_SESSION['membre']['prenom']
            . ' ' . $_SESSION['membre']['nom']
        ;
    }
    
    return '';
}

function getUserPseudo()
{
    if (isUserConnected()) {
        return $_SESSION['membre']['pseudo']
                ;
    }
    
    return '';
}

function isUserAdmin()
{
    return isUserConnected()
        && $_SESSION['membre']['role'] == 'admin';
}

function adminSecurity()
{
    // si on n'est pas admin
    if (!isUserAdmin()) {
        // si on n'est pas connecté
        if (!isUserConnected()) {
            // redirection vers la page de connexion
            $url = RACINE_WEB . 'connexion.php';
            header('Location: ' . $url);
        // si on est connecté mais avec le role membre
        } else {
            // on interdit l'accès à la page
            header('HTTP/1.1 403 Forbidden');
            echo "Vous n'avez pas le droit"
                . "d'accéder à cette page"
            ;
        }
        
        die;
    }
}

// enregistre un message dans la session
function setFlashMessage($message, $type = 'success')
{
    $_SESSION['flashMessage'] = [
        'message' => $message,
        'type' => $type
    ];
}

// affiche le message enregistré en session
// puis le supprime
function displayFlashMessage()
{
    if (isset($_SESSION['flashMessage'])) {
        $message = $_SESSION['flashMessage']['message'];
        $type = ($_SESSION['flashMessage']['type'] == 'error')
            ? 'danger' // pour la classe alert-danger du bootstrap
            : $_SESSION['flashMessage']['type']
        ;
        
        echo '<div class="alert alert-' . $type . '">'
            . "<strong>$message</strong>"
            . '</div>'
        ;
        
        unset($_SESSION['flashMessage']);
    }
}
function ajoutPanier(array $produit, $quantite){
    // on initialise le panier s'il n'existe pas encore
    if (!isset($_SESSION['panier'])){
        $_SESSION['panier'] = [];
    }
    // si le produit n'est pas encore dans le panier
    if (!isset($_SESSION['panier'][$produit['id']])){
        $_SESSION['panier'][$produit['id']] =[
          'nom' => $produit['nom'],  
          'prix' => $produit['prix'],  
          'quantite' => $quantite,  
        ];
    } else {
        $_SESSION['panier'][$produit['id']]['quantite'] += $quantite;
    }
}

function getTotalPanier()
{
    $total = 0;
        if(isset($_SESSION['panier'])){
            foreach ($_SESSION['panier'] as $produit){
            $total += $produit['panier']* $produit['quantite'];
            }
        }
    return $total;
}
/* fonction permettant l'actualisation du panier*/
function modifierQtePanier($idProduit, $quantite)
{
    if (isset($_SESSION['panier'][$idProduit])){
        if ($quantite != 0 ){
            //MAJ de la quantité pour le produit
            $_SESSION['panier'][$idProduit]['quantite'] = $quantite;
        }else {
            //suppression du produit dans le panier
            unset($_SESSION['panier'][$idProduit]);
        }
    }
}
