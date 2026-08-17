<?php

use App\Conn\Read;
use App\Helpers\Check;

$Read ??= new Read();
?>
<aside class="main_sidebar">
    <form class="sidebar_search sidebar_widget" name="search" action="" method="post" enctype="multipart/form-data">
<input type="text" name="s" placeholder="Pesquisar Artigos:" required/><button class="btn btn_red icon-search icon-notext"></button>
    </form>
  <h1 class="title-hidden">Empréstimo</h1>
  
<article class="sidebar_most">
        <h1>Linhas de <b>Crédito</b></h1>
        <?php
        $Read->exeRead(
            DB_POSTS,
            'WHERE post_status = 1 AND post_date <= NOW() AND post_category = 2 '
            . 'ORDER BY post_views DESC, post_date DESC LIMIT 5'
        );
        if (!$Read->getResult()) {
            echo Check::erro('Ainda Não existe posts cadastrados. Favor volte mais tarde :)', E_USER_NOTICE);
        } else {
            foreach ($Read->getResult() as $Post) {
                ?><article class="sidebar_most_post">
                    <a title="Ler mais sobre <?= $Post['post_title']; ?>" href="<?= BASE; ?>/artigo/<?= $Post['post_name']; ?>">
                        <img title="<?= $Post['post_title']; ?>" alt="<?= $Post['post_title']; ?>" src="<?= BASE; ?>/tim.php?src=uploads/<?= $Post['post_cover']; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>"/>
                    </a>
                    <header>
                        <h1><a title="Ler mais sobre <?= $Post['post_title']; ?>" href="<?= BASE; ?>/artigo/<?= $Post['post_name']; ?>"><?= $Post['post_title']; ?></a></h1>
                    </header>
                </article><?php
            }
        }
        ?>
    </article>
  <article class="sidebar_social sidebar_widget">
        <header>
            <img alt="Siga <?= SITE_SOCIAL_NAME; ?> no Facebook" title="Siga <?= SITE_SOCIAL_NAME; ?> no Facebook" src="<?= INCLUDE_PATH; ?>/images/iccserra-logomarca.png"/>
            <h1><span><?= SITE_SOCIAL_NAME; ?></span><b>no Facebook</b></h1>
            <p>Siga no Facebook e acompanhe as novidades em primeira mão!</p>
        </header>

        <div class="fb-like" style="z-index: 9; max-width: 100%; overflow: hidden;" data-href="https://facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="false" data-width="240"></div>
    </article>
</aside>