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

        <div class="row" style="background-color: #0000ff">

            <div class="col-md-2">
                <figure>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQg6eLe4t1uNJd0wzW6SMNgi7wuSBUTjCsLIJokF7_wwxy7ZOW9" height="175px" width="175px" alt="">
                </figure>
            </div>
            <div class="col-md-2">
                <h2>
                    <?= getUserFullName() ?>
                </h2>
            </div>
            <div class="col-md-1 col-md-offset-2 text-center">
                <p><span class="glyphicon glyphicon-tag"></span> Deal</p>
            </div>
            <div class="col-md-2 text-center">
                <p><span class="glyphicon glyphicon-home"></span> Deal-shop</p>
            </div>
            <div class="col-md-1 text-center">
                <p><span class="glyphicon glyphicon-envelope"></span> Contact</p>
            </div>
            <div class="row">
                    <div class="col-md-7 ">
                        (Shonash Ravine) (Doc and Marty are standing at the very edge of the tracks that end just at the Ravine. They walk back towards their horses.) Well, Doc, we can scratch that idea. I mean, we can't wait around a year and a half for this thing to get finished. Marty...it's perfect! You're just not thinking fourth dimensionally! Right, right. I have a real problem with that.
                    </div>
                </div>
        </div>


        <?php
include __DIR__ . '/layout/bottom.php';
?>
