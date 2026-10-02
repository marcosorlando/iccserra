<a class="skip_link" href="#conteudo">Pular para o conteúdo</a>
<header class="main_header" id="h">
	<div class="content">
		<?php
        // Na home o <h1> é o título do hero (inc/hero.php); nas demais páginas o nome do site segue como h1 oculto.
        $SiteTitleTag = (empty($URL[0]) || 'index' === $URL[0]) ? 'p' : 'h1';
        ?>
		<<?= $SiteTitleTag; ?> class="site_title"><?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?></<?= $SiteTitleTag; ?>>
		<a href="<?= BASE; ?>" title="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>">
			<img class="main_logo" src="<?= INCLUDE_PATH; ?>/images/iccserra-logo-h.png" width="300" height="60"
			     alt="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>" title="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>"/>
		</a>

		<button type="button" class="mobile_menu" aria-controls="main-nav" aria-expanded="false"
		        aria-label="Abrir menu"><span aria-hidden="true">&#9776;</span></button>

		<nav class="main_nav" id="main-nav" aria-label="Menu principal">
			<p class="site_title"><strong>Microcrédito </strong> - Crédito para Boas Ideias!</p>
			<?php
                require REQUIRE_PATH . '/inc/menu.php';

            // CTA fixa no lugar dos ícones de redes sociais (Onda 5). As redes seguem no rodapé.
            $HeaderWhatsMsg = urlencode(
                'Olá estou no site da ICC SERRA agora, e quero conversar com um Agente de Crédito '
                . "para ter mais informações sobre *MICROCRÉDITO*. \n\n *Aguardo um retorno em breve!*"
            );
            ?>
			<a class="nav_cta icon-whatsapp" href="https://wa.me/<?= SITE_ADDR_WHATS_TRIM; ?>?text=<?= $HeaderWhatsMsg; ?>"
			   target="_blank" rel="noopener" title="Converse com um Agente de Crédito pelo WhatsApp">Falar com um agente<span
						class="sr_only"> pelo WhatsApp (abre em nova aba)</span></a>

			<div class="navbar-search" id="navbar-search">
				<div class="container">
					<form class="search_form" name="searchTop" action="" method="post" enctype="multipart/form-data"
					      role="search">
						<div class="input-group">
							<label class="sr_only" for="search-input">Pesquisar no site</label>
							<input type="text" id="search-input" autocomplete="off" class="form-control"
							       name="s" placeholder="Digite uma palavra e após tecle ENTER" required/>
							<!--          <span class="input-group-btn">
										<button type="reset" class="btn search-close" id="search-close" >
										  <i>X</i>
										</button>
									  </span>-->
						</div>
					</form>
				</div>
			</div>
		</nav>
		<div class="clear"></div>
	</div>
</header>
