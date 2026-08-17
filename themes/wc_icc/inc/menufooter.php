<ul class="main_nav_menu jwc_menu">
    <li><a title="<?= SITE_NAME; ?> | Home" href="<?= BASE;?>"><i style="font-size:1.5em; padding-left: 8px;" class="icon-home"></i></a></li>
    <li><a class="wc_goto" title="<?= SITE_NAME; ?> | Sobre" href="#a">Sobre</a></li>
    <li><a class="wc_goto" title="<?= SITE_NAME; ?> | Últimas" href="#b">Últimas</a></li>
    <li><a class="wc_goto" title="<?= SITE_NAME; ?> | Veja Mais" href="#c">Veja Mais</a></li>
	<li><a title="<?= SITE_NAME; ?> | Materiais" href="<?= BASE; ?>/materiais">Materiais</a></li>
	   <?php

    use App\Conn\Read;

    $Read ??= new Read();

    $Read->exeRead(DB_CATEGORIES, 'WHERE category_parent IS NULL ORDER BY category_name ASC');
    if ($Read->getResult()) {
        foreach ($Read->getResult() as $Cat) {
            echo "<li><a title=' " . SITE_NAME . " | {$Cat['category_title']}' href='" . BASE
                . "/artigos/{$Cat['category_name']}'>{$Cat['category_title']}</a></li>";
        }
    }

    $Read->fullRead(
        'SELECT page_title, page_name FROM ' . DB_PAGES
        . ' WHERE page_status = 1 AND page_menu <> 1 ORDER BY page_order ASC, page_name ASC'
    );
    if ($Read->getResult()) {
        foreach ($Read->getResult() as $Page) {
            echo "<li><a title='" . SITE_NAME . " | {$Page['page_title']}' href='" . BASE
                . "/{$Page['page_name']}'>{$Page['page_title']}</a></li>";
        }
    }
    ?>
</ul>
