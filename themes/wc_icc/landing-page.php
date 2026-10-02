<?php

use App\Conn\Read;

$Read ??= new Read();

$Read->exeRead(DB_LANDING_PAGES, 'WHERE page_name = :nm', "nm={$URL[0]}");
if (!$Read->getResult()) {
	require REQUIRE_PATH . '/404.php';

	return;
}

extract($Read->getResult()[0]);
?>
<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700" rel="stylesheet">
<link href="<?= INCLUDE_PATH; ?>/lp/css/style.min.css" rel="stylesheet">
<script defer src="<?= INCLUDE_PATH; ?>/lp/js/lp.js"></script>
<style>#conversion-modal .modal-content section div.actions button.call_button, #fixed-form #conversion .actions .call_button {
        background-color: <?= $page_btn_bg_color ?>;
        color: <?= $page_btn_text_color ?>;
    }
    #conversion-modal .modal-content section div.actions button.call_button:hover, #fixed-form #conversion .actions .call_button:hover {
        background-color: <?= $page_btn_bg_color_hover ?>;
        border-bottom: 3px solid<?= $page_btn_bg_color ?>;
    }</style>
<body style="background: <?= $page_bg_color ?>">
<div class="color-hack"></div>
<div id="logo">
	<div class="container">
		<div class="l-box">
			<a href="<?=BASE?>" title="Voltar para Home" target="_self"><img src="<?= INCLUDE_PATH; ?>/images/iccserra-logo-h.png" alt="<?= SITE_ADDR_NAME; ?> - logotipo"></a>
		</div>
	</div>
</div>
<section id="header-and-image"
         style="background: <?= $page_box_color ?> url(<?= BASE . '/uploads/landingpages/' . $page_cover; ?>) no-repeat center center ;">
	<div class="container">
		<div class="pure-u-1">
			<div class="l-box">
				<header>
					<?= $page_content; ?>
				</header>
				<figure>
					<?= $page_mockup ? "<img src= " . BASE . "/uploads/landingpages/{$page_mockup} alt='{$page_title}' title='{$page_title}' >" : ''; ?>
				</figure>
			</div>
		</div>
	</div>
</section>
<section id="fixed-form">
	<div id="conversion">
		<header>
			<h3><?= $form_title; ?></h3>
		</header>
		<section>
			<form class="j_landing_page form_capitalize" novalidate method="post" action="" enctype="multipart/form-data">
				<div class="callback_return"></div>
				<input type="hidden" name="callback" value="Lp"/>
				<input type="hidden" name="callback_action" value="manage"/>
				<input type="hidden" name="page_destino" value="<?= $page_destino; ?>"/>
				<input type="hidden" name="page_mockup" value="<?= $page_mockup; ?>"/>
				<input type="hidden" name="lead_conversion" value="<?= $page_title; ?>"/>

				<div id="error-container">
					<p>Preencha corretamente os campos marcados (*).</p>
				</div>
				<div class="field">
					<label>Nome</label>
					<input type="text"
					       name="lead_name"
					       value=""
					       class="form-control required" autofocus
					       required>
				</div>
				<div class="field">
					<label>Email</label>
					<input type="email"
					       name="lead_email"
					       value=""
					       class="form-control required" required>
				</div>
				<div class="field">
					<label>Cidade</label>
					<input type="text"
					       name="lead_city"
					       value=""
					       class="form-control  required"
					       required>
				</div>
				<div class="field">
					<label>Profissão</label>
					<input type="text"
					       name="lead_job_title"
					       value=""
					       class="form-control  required"
					       required>
				</div>

				<!-- security start -->
				<div class="field sum">
					<label>Realize a Soma</label>
					<input type="text" name="senderHuman" id="senderHuman" class="input-md input-rounded form-control"
					       placeholder="" required>
					<input type="hidden" name="checkHuman_a" id="checkHuman_a">
					<input type="hidden" name="checkHuman_b" id="checkHuman_b">
				</div>
				<div class="clear" id="sugestao">
					<label for="privacy">
						<input type="checkbox" value="1" id="privacy" name="privacy" required>
						<span><b class="text-red font_medium">*</b> Consinto com a <a rel="shadowbox"
						                                                                          href="<?= BASE;
						                                                                          ?>/politicas.html"
						                                                                          title="Ler a Política de Privacidade da ICC SERRA">Política de Privacidade</a>. <small> Ao enviar meus dados, eu autorizo o uso dos mesmos para receber informações sobre o Banco do Povo.</small></span>
					</label>
				</div>
				<!-- security end -->
				<div class="actions">
					<button type="submit" class="call_button">
						<span class="icon icon-download"></span>
						<img class="form_load none" style="margin: 0 5px -4px 0; height: 20px; width: auto"
						     alt="Enviando Requisição!" title="Enviando Requisição!"
						     src="<?= INCLUDE_PATH; ?>/lp/images/load_w.gif"/>
						<?= $page_btn_text; ?>
					</button>
				</div>
				<p class="notice">Prometemos não utilizar suas informações de contato para enviar qualquer tipo de
					SPAM.</p>
			</form>
		</section>
	</div>
</section>
<section id="content">
	<div class="container pure-g">
		<div id="first-text-block" class="pure-u-1 pure-u-md-1-2">
			<div class="l-box">
				<?= $page_coluna1; ?>
			</div>
		</div>

		<div id="second-text-block" class="pure-u-1 pure-u-md-1-2">
			<div class="l-box">
				<?= $page_coluna2; ?>
			</div>
		</div>
	</div>
</section>
</body>
<script defer src="<?= BASE ?>/_cdn/formattext.js"></script>
