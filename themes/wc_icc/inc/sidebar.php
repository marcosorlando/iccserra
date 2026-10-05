<?php

    use App\Conn\Read;
    use App\Helpers\Check;

    $Read ??= new Read();
?>
<aside class="main_sidebar">
	<form class="sidebar_search sidebar_widget" name="search" action="" method="post" enctype="multipart/form-data">
		<input type="text" name="s" autocomplete="false" placeholder="Pesquisar Artigos:" required/>
		<button class="btn btn_red icon-search icon-notext"></button>
	</form>

	<nav class="sidebar_nav sidebar_widget">
		<h1>Ver <b>Categorias</b></h1>
        <?php
            $Read->exeRead(
                DB_CATEGORIES,
                'WHERE category_parent IS NULL AND category_id IN('
                . 'SELECT post_category FROM ' . DB_POSTS . ' WHERE post_status = 1 AND post_date <= NOW()'
                . ') ORDER BY category_title ASC'
            );
            if (!$Read->getResult()) {
                echo Check::erro('Ainda não existem sessões cadastradas!', E_USER_NOTICE);
            } else {
                $Sessions = $Read->getResult();
                echo '<ul>';
                foreach ($Sessions as $Ses) {
                    echo "<li><a title='artigos/{$Ses['category_name']}' href='"
                        . BASE . "/artigos/{$Ses['category_name']}'>&raquo; {$Ses['category_title']}</a></li>";
                    $Read->exeRead(
                        DB_CATEGORIES,
                        'WHERE category_parent = :pr AND category_id IN('
                        . 'SELECT post_category_parent FROM ' . DB_POSTS
                        . ' WHERE post_status = 1 AND post_date <= NOW()'
                        . ') ORDER BY category_title ASC',
                        "pr={$Ses['category_id']}"
                    );
                    if ($Read->getResult()) {
                        foreach ($Read->getResult() as $Cat) {
                            echo "<li><a title='artigos/{$Cat['category_name']}' href='"
                                . BASE . "/artigos/{$Cat['category_name']}'>&raquo;&raquo; {$Cat['category_title']}</a></li>";
                        }
                    }
                }

                echo '</ul>';
            }
        ?>
	</nav>
	<article class="sidebar_most">
		<h1>Mais <b>Vistos</b></h1>
        <?php
            $Read->exeRead(
                DB_POSTS,
                'WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_views DESC, post_date DESC LIMIT 5'
            );
            if (!$Read->getResult()) {
                echo Check::erro('Ainda Não existe posts cadastrados. Favor volte mais tarde :)', E_USER_NOTICE);
            } else {
                foreach ($Read->getResult() as $Post) {
                    ?>
					<article class="sidebar_most_post">
					<a title="Ler mais sobre <?= $Post['post_title']; ?>"
					   href="<?= BASE; ?>/artigo/<?= $Post['post_name']; ?>">
						<img title="<?= $Post['post_title']; ?>" alt="<?= $Post['post_title']; ?>"
						     src="<?= BASE; ?>/tim.php?src=uploads/<?= $Post['post_cover']; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>"/>
					</a>
					<header>
						<h1><a title="Ler mais sobre <?= $Post['post_title']; ?>"
						       href="<?= BASE; ?>/artigo/<?= $Post['post_name']; ?>"><?= $Post['post_title']; ?></a>
						</h1>
					</header>
					</article><?php
                }
            }
        ?>
	</article>
</aside>
