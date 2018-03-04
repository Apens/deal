<?php
require_once __DIR__. '/include/init.php';
include __DIR__.'/layout/top.php';

// on verifie que l'internaute soit connecté. S'il ne l'est pas on met en place une redirection vers la page de connexion
if (!isUserConnected()) {

	header('location:deal_connexion.php'); 
	exit();
}

var_dump($_SESSION);
?>
    <h1>Mon profil</h1>

    <?php
if (isUserAdmin()): 
?>
        <h2>Bien joué mec t'es admin</h2>
        <?php
endif
?>

        <div class="row">
<div class="col-md-12">
            <div class="col-md-2">
                <figure>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQg6eLe4t1uNJd0wzW6SMNgi7wuSBUTjCsLIJokF7_wwxy7ZOW9" height="175px" width="175px" alt="">
                </figure>
            </div>
            <div class="col-md-2 ">
                <h2>
                    <?= getUserFullName() ?> 
                </h2>
                <p> M@il :  <?= $_SESSION['membre']['email'] ?> </p>
                <p> tel: <?= $_SESSION['membre']['telephone'] ?></p>
                <p>Membre depuis le <?= $_SESSION['membre']['date_enregistrement'] ?></p>
                <p></p>
            </div>
</div>
            <div class="row">
                
            </div>
        </div>


        <?php
include __DIR__ . '/layout/bottom.php';
?>
