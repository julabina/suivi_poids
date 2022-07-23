<?php $title = 'img'; ?>

<?php ob_start(); ?>

<header>
    <div class="header">
        <a href="/suivi_poids/"><h1>TITRE DU SITE</h1></a>
    </div>
</header>

<main class="bfp">

    <a href="/suivi_poids/dashboard" class="backToHome backToHome--withHeader">< retour</a>

    <h2>Calculer votre indice de masse graisseuse (IMG).</h2>

    <?php if(isset($_SESSION['name']) && isset($_SESSION['user']) && isset($_SESSION['userId']) && isset($_SESSION['size']) && (isset($_SESSION['sexe']) && ($_SESSION['sexe'] === "man" || $_SESSION['sexe'] === "woman")) && isset($_SESSION['auth']) && $_SESSION['auth'] === true): ?>
        <p class="bfp__subTitle">Votre IMG est de</p>
        <h3><?= $imgInfos['img']; ?>%</h3>

        <p>Avec votre IMG, vous etes/avez (selon Deurenberg)</p>
        <h4><?php 
            if($imgInfos['is_man'] === 0) {
                if($imgInfos['img'] < 25) {
                    echo "Trop maigre";
                } elseif($imgInfos['img'] < 30 && $imgInfos['img'] > 24) {
                    echo "Normal";
                } elseif($imgInfos['img'] > 29) {
                    echo "Trop de graisse";
                }
            } elseif($imgInfos['is_man'] === 1) {
                if($imgInfos['img'] < 15) {
                    echo "Trop maigre";
                } elseif($imgInfos['img'] < 20 && $imgInfos['img'] > 14) {
                    echo "Normal";
                } elseif($imgInfos['img'] > 19) {
                    echo "Trop de graisse";
                }
            }
            ?></h4>
    <?php endif; ?>

</main>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php'); ?>