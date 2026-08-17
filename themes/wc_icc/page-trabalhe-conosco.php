<?php

use App\Conn\Read;

$Read ??= new Read();

$Read->exeRead(DB_PAGES, 'WHERE page_name = :nm AND page_status = 1', "nm={$URL[0]}");
if (!$Read->getResult()) {
	require REQUIRE_PATH . '/404.php';

	return;
}

extract($Read->getResult()[0]);
?>

<div class="container page_single">
	<div class="content">
		<div class="page_content">
			<header class="site_header" style="margin-bottom: 0">
				<h1><b>PARA SE CANDIDATAR</b> A UMA VAGA</h1>
				<p>Preencha o Formulário
				</p>
			</header>

			<div class="htmlchars">

				<form id="curriculo_form" class="animated fadeIn form_capitalize" method="post"
				      action="javascript:void(0)" enctype="multipart/form-data">

					<input type="hidden" class="callback" name="callback" value="Contact">
					<input type="hidden" class="callback-action" name="callback_action" value="curriculum">

					<div class="callback_return"></div>

					<div class="label_50">
						<label for="">
							<span><b class="font_red">*</b> Digite seu nome completo</span>
							<input type="text" name="full_name" id="full_name"
							       placeholder="Nome completo" required/>
						</label>
						<label for="">
							<span><b class="font_red">*</b> Área de interesse</span>
							<select name="area" id="area" required>
								<option value="" selected disabled>Selecione a área:</option>
								<option value="Administrativa">Administrativa</option>
								<option value="Comercial">Comercial</option>
							</select>
						</label>
					</div>

					<div class="label_50">
						<label for="">
							<span><b class="font_red">*</b> Digite seu número de celular</span>
							<input class="formPhone" type="text" name="phone" id="phone"
							       placeholder="Número para contato" required/>
						</label>
						<label for="">
							<span><b class="font_red">*</b> Digite seu e-mail</span>
							<input class="required" type="email" name="email" id="email"
							       placeholder="Informe seu melhor e-mail" required/>
						</label>
					</div>

					<div class="clear">
						<label class="curriculum" for="cv_pdf" tabindex="5">
							<input type="file" name="cv_pdf" id="cv_pdf" required>
							<img src="<?= INCLUDE_PATH ?>/images/curriculo-pdf.svg" title="CLIQUE OU ARRASTE SEU
								CURRÍCULO DENTRO DA ÁREA PONTILHADA"
							     alt="CLIQUE OU ARRASTA DE CURRÍCULO DENTRO DA ÁREA PONTILHADA">
						</label>
					</div>

					<div class="clear_">
						<label for="privacy" class="label_check">
							<input type="checkbox" id="privacy" name="privacy" required>
							<span>
								<b class="text-red" style="font-size: 1.2em">*</b>Consinto com a
								<a rel="shadowbox" href="<?= BASE; ?>/politicas.html" title="Ler a Política de Privacidade da ICC SERRA">Política de Privacidade.</a>
							</span>
						</label>
					</div>
					<!-- security start -->
					<div class="capcha">
						<span>Realize a Soma * antes de enviar sua Mensagem</span>
						<input type="text" name="senderHuman" id="senderHuman"
						       class="input-md input-rounded form-control" placeholder="" required>
						<input type="hidden" name="checkHuman_a" id="checkHuman_a">
						<input type="hidden" name="checkHuman_b" id="checkHuman_b">
					</div>
					<!-- security end -->
					<br/>

					<button class="btn btn_red center">
						<img src="<?= INCLUDE_PATH; ?>/images/icons/load_w.gif" title="Enviando..."
						     class="none form_load">
						<i class="icon-envelop"></i> Enviar Currículo
					</button>

				</form>
			</div>
		</div>
	</div>

</div>
<script src="<?= INCLUDE_PATH;?>/lp/js/lp.js"></script>
<script src="<?= BASE;?>/_cdn/formattext.js"></script>
