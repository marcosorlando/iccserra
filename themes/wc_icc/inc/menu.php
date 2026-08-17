<ul class="main_nav_menu jwc_menu">
	<li><a title="<?= SITE_NAME; ?> | Home" href="<?= BASE; ?>"><i style="font-size:1.5em; padding-left: 8px;"
	                                                               class="icon-home"></i></a></li>
	<!--    <li><a class="wc_goto" title="<= SITE_NAME; ?> | Sobre" href="#a">Sobre</a></li>
		<li><a class="wc_goto" title="<= SITE_NAME; ?> | Últimas" href="#b">Últimas</a></li>
		<li><a class="wc_goto" title="<= SITE_NAME; ?> | Veja Mais" href="#c">Veja Mais</a></li>-->

	<?php

	use App\Conn\Read;

	$Read ??= new Read();

	echo "<li><a title=' " . SITE_NAME . " | Linhas de Crédito' href='" . BASE
		. "/artigos/linhas-de-credito'>Linhas de Crédito</a>";

	$Read->fullRead(
		'SELECT page_title, page_name FROM ' . DB_PAGES
		. ' WHERE page_status = 1 AND page_menu = 0 ORDER BY page_order ASC, page_name ASC'
	);
	if ($Read->getResult()) {
		foreach ($Read->getResult() as $Page) {
			echo "<li><a title='" . SITE_NAME . " | {$Page['page_title']}' href='" . BASE
				. "/{$Page['page_name']}'>{$Page['page_title']}</a></li>";
		}
	}

	echo "<li><a title=' " . SITE_NAME . " | Fale Conosco' href='" . BASE
		. "/fale-conosco'>Entre em Contato</a>";

	echo "<ul class='sub'>";
	echo "<li><a title='" . SITE_NAME . " | Fale Conosco' href='" . BASE
		. "/fale-conosco'>Fale conosco</a></li>";
	echo "<li><a title='" . SITE_NAME . " | Trabalhe Conosco' href='" . BASE
		. "/trabalhe-conosco'>Trabalhe conosco</a></li>";
	echo '</ul>';
	?>

	<li>
		<a class="icon-search text_color_wine" id="search-open" href="#fakelink"></a>
	</li>
</ul>
