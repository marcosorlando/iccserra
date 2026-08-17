<?php

use App\Conn\Read;

// Pega os valores dos cookies definidos anteriormente pelo formulário do simulador.
$nome = filter_input(INPUT_COOKIE, 'nome') ?? '';
$email = filter_input(INPUT_COOKIE, 'email') ?? '';
$telefone = filter_input(INPUT_COOKIE, 'telefone') ?? '';
$cidade = filter_input(INPUT_COOKIE, 'cidade') ?? '';
$cb_enquadramento = filter_input(INPUT_COOKIE, 'cb_enquadramento') ?? '';

$Read ??= new Read();

$Read->exeRead(DB_PAGES, 'WHERE page_name = :nm AND page_status = 1', "nm={$URL[0]}");
if (!$Read->getResult()) {
	require REQUIRE_PATH . '/404.php';

	return;
}

extract($Read->getResult()[0]);
?>

<div class="container post_single">
	<div class="content">
		<div class="left_content">
			<div class="post_content">
				<header>
					<h1 class="title center"><?= $page_subtitle; ?></h1>
				</header>

				<div class="htmlchars">
					<?php
					require_once REQUIRE_PATH . '/simulador/simulatorform.php'; ?>
					<div id="print" class="retorno_simulacao"></div>
				</div>
				<div class="clear"></div>

				<?php
				$WC_TITLE_LINK = "Precisando de Microcrédito para seu negócio, faça uma simulação na ICC Serra";
				$WC_SHARE_HASH = "IccSerra";
				$WC_SHARE_LINK = BASE . "/simulador";
				require './_cdn/widgets/share/share.wc.php';
				?>
				<div class="clear"></div>
				<section class="wc_line">
					<?php
					require REQUIRE_PATH . '/inc/linha_microcredito_produtivo.php'; ?>
					<?php
					require REQUIRE_PATH . '/inc/linha_microcredito_mulher.php'; ?>
					<?php
					require REQUIRE_PATH . '/inc/linha_reforma.php'; ?>
					<?php
					require REQUIRE_PATH . '/inc/linha_agricola.php'; ?>
				</section>
			</div>
		</div><?php
		require REQUIRE_PATH . '/inc/sidebar_lines.php'; ?>
		<div class="clear"></div>
	</div>
</div>
<script src="<?= INCLUDE_PATH; ?>/simulador/funcoes.js"></script>
<script src="<?= BASE; ?>/_cdn/formattext.js"></script>
<script src="<?= INCLUDE_PATH; ?>/lp/js/lp.js"></script>
