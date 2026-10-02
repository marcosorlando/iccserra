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
				<h1>
					<strong><?= $page_title; ?></strong>
				</h1>
				<p>Escolha uma das opções abaixo: (Denúncia, Reclamação ou Sugestão)</p>
			</header>

			<div class="row ouvidoria">

				<div class="ouvidoria-options">
					<a class="service-link wc_goto" href="#denuncia">
						<div id="icb-01" class="icon_box_01 text-center">
							<img class="svc-icon" src="<?= INCLUDE_PATH ?>/images/ouvidoria/denuncia.png"
							     alt="Denúncia">
							<span></span>
							<h3>Denúncia</h3>
						</div>
					</a>
				</div>

				<div class="ouvidoria-options">
					<a class="service-link" href="#reclamacao">
						<div id="icb-02" class="icon_box_01 text-center">
							<img class="svc-icon" src="<?= INCLUDE_PATH ?>/images/ouvidoria/reclamacao.png"
							     alt="Denúncia">
							<span></span>
							<h3>Reclamação</h3>
						</div>
					</a>
				</div>

				<div class="ouvidoria-options">
					<a class="service-link" href="#sugestao">
						<div id="icb-03" class="icon_box_01 text-center">
							<img class="svc-icon" src="<?= INCLUDE_PATH ?>/images/ouvidoria/sugestao.png"
							     alt="Denúncia">
							<span></span>
							<h3>Sugestão</h3>
						</div>
					</a>
				</div>
			</div>

			<div class="htmlchars destiny">

				<form name="form_denuncia" id="denuncia" class="invisivel animated fadeIn j_formsubmit form_capitalize"
				      method="post"
				      enctype="multipart/form-data">
					<input class="callback" type="hidden" name="callback" value="Ouvidoria">
					<input class="callback-action" type="hidden" name="callback_action" value="manager">

					<div class="form-title icb-01">
						<img class="svc-icon" src="<?= INCLUDE_PATH ?>/images/ouvidoria/denuncia.png"
						     alt="Denúncia">
						<h3 class="text-center">Para fazer sua denúncia preencha o formulário</small>
						</h3>
					</div>

					<div class="callback_return trigger_ajax col-md-12"></div>

					<div class="label_50">
						<label>
							<span>Nome:</span>
							<input type="text" name="first_name" id="first_name"
							       placeholder="NOME (Opcional)"/>
						</label>
						<label>
							<span>Sobrenome:</span>
							<input type="text" name="last_name" id="last_name"
							       placeholder="SOBRENOME (Opcional)"/>
						</label>
					</div>
					<div class="label_50">
						<label>
							<span><b class="font_red">*</b> E-mail:</span>
							<input class="required" type="email" name="email" id="email"
							       placeholder="E-MAIL DE CONTATO" required/>
						</label>
						<label>
							<span><b class="font_red">*</b> Denuncia:</span>
							<select name="sector" id="sector" required>
								<option selected value="DENÚNCIA">DENÚNCIA</option>
							</select>
						</label>
					</div>

					<div class="clear">
						<label><br/>
							<span><b class="font_red">*</b> Descreva sua denúncia:</span>
							<textarea class="required" rows="6" name="complaint"
							          placeholder="Digite nesse espaço sua denúncia..." required></textarea>
						</label>
					</div>
					<!-- security start -->
					<div class="capcha">
						<span>Realize a soma <b class="font_red">*</b> antes de enviar sua denúncia</span>
						<input type="text" name="senderHuman" id="senderHuman"
						       class="input-md input-rounded form-control" placeholder="" required>
						<input type="hidden" name="checkHuman_a" id="checkHuman_a">
						<input type="hidden" name="checkHuman_b" id="checkHuman_b">
					</div>
					<!-- security end -->
					<div class="clear_">
						<label for="privacy" class="label_check">
							<input type="checkbox" id="privacy" name="privacy" required>
							<span>
								<b class="text-red" style="font-size: 1.2em">*</b>Consinto com a
								<a rel="shadowbox" href="<?= BASE; ?>/politicas.html" title="Ler a Política de
								Privacidade da ICC SERRA">Política de Privacidade</a>. <small>Ao enviar meus dados, eu
										autorizo o uso dos mesmos para receber o retorno
											da minha denúncia.</small>
							</span>
						</label>
					</div>
					<br/>

					<button class="btn btn_red" style="margin: 0 auto !important;" type="submit">
						<span class="icon-envelop"></span>
						<img class="form_load" src="<?= INCLUDE_PATH; ?>/images/icons/load_w.gif" alt="Aguarde,
						enviando denúncia!" title="Aguarde, enviando denúncia!"/> Enviar Denúncia!
					</button>
				</form>

				<form name="form_reclamacao" id="reclamacao" class="invisivel animated fadeIn j_formsubmit form_capitalize"
				      method="post"
				      enctype="multipart/form-data">
					<input class="callback" type="hidden" name="callback" value="Ouvidoria">
					<input class="callback-action" type="hidden" name="callback_action" value="manager">

					<div class="form-title icb-02">
						<img class="svc-icon" src="<?= INCLUDE_PATH ?>/images/ouvidoria/reclamacao.png"
						     alt="Reclamação">
						<h3 class="text-center">Para fazer sua reclamação preencha o formulário</small>
						</h3>
					</div>

					<div class="callback_return trigger_ajax col-md-12"></div>

					<div class="label_50">
						<label>
							<span>Nome:</span>
							<input type="text" name="first_name" id="first_name"
							       placeholder="NOME (Opcional)"/>
						</label>
						<label>
							<span>Sobrenome:</span>
							<input type="text" name="last_name" id="last_name"
							       placeholder="SOBRENOME (Opcional)"/>
						</label>
					</div>
					<div class="label_50">
						<label>
							<span><b class="font_red">*</b> E-mail:</span>
							<input class="required" type="email" name="email" id="email"
							       placeholder="E-MAIL DE CONTATO" required/>
						</label>
						<label>
							<span><b class="font_red">*</b> Denuncia:</span>
							<select name="sector" id="sector" required>
								<option selected value="RECLAMAÇÃO">RECLAMAÇÃO</option>
							</select>
						</label>
					</div>

					<div class="clear">
						<label><br/>
							<span><b class="font_red">*</b> Descreva sua reclamação:</span>
							<textarea class="required" rows="6" name="complaint"
							          placeholder="Digite nesse espaço sua reclamação..." required></textarea>
						</label>
					</div>
					<!-- security start -->
					<div class="capcha">
						<span>Realize a soma <b class="font_red">*</b> antes de enviar sua reclamação</span>
						<input type="text" name="senderHuman" id="senderHuman"
						       class="input-md input-rounded form-control" placeholder="" required>
						<input type="hidden" name="checkHuman_a" id="checkHuman_a">
						<input type="hidden" name="checkHuman_b" id="checkHuman_b">
					</div>
					<!-- security end -->
					<div class="clear_">
						<label for="privacy-r" class="label_check">
							<input type="checkbox" id="privacy-r" name="privacy" required>
							<span>
								<b class="text-red" style="font-size: 1.2em">*</b>Consinto com a
								<a rel="shadowbox" href="<?= BASE; ?>/politicas.html" title="Ler a Política de
								Privacidade da ICC SERRA">Política de Privacidade</a>. <small>Ao enviar meus dados, eu
										autorizo o uso dos mesmos para receber o retorno
											da minha reclamação.</small>
							</span>
						</label>
					</div>
					<br/>

					<button class="btn btn_yellow" style="margin: 0 auto !important;" type="submit">
						<span class="icon-envelop"></span>
						<img class="form_load" src="<?= INCLUDE_PATH; ?>/images/icons/load_w.gif" alt="Aguarde,
						enviando reclamação!" title="Aguarde, enviando reclamação!"/> Enviar Reclamação!
					</button>
				</form>

				<form name="form_sugestao" id="sugestao" class="invisivel animated fadeIn j_formsubmit form_capitalize" method="post"
				      enctype="multipart/form-data">
					<input class="callback" type="hidden" name="callback" value="Ouvidoria">
					<input class="callback-action" type="hidden" name="callback_action" value="manager">

					<div class="form-title icb-03">
						<img class="svc-icon" src="<?= INCLUDE_PATH ?>/images/ouvidoria/sugestao.png"
						     alt="Sugestão">
						<h3 class="text-center">Para fazer sua sugestão preencha o formulário</small>
						</h3>
					</div>

					<div class="callback_return trigger_ajax col-md-12"></div>

					<div class="label_50">
						<label>
							<span>Nome:</span>
							<input type="text" name="first_name" id="first_name"
							       placeholder="NOME (Opcional)"/>
						</label>
						<label>
							<span>Sobrenome:</span>
							<input type="text" name="last_name" id="last_name"
							       placeholder="SOBRENOME (Opcional)"/>
						</label>
					</div>
					<div class="label_50">
						<label>
							<span><b class="font_red">*</b> E-mail:</span>
							<input class="required" type="email" name="email" id="email"
							       placeholder="E-MAIL DE CONTATO" required/>
						</label>
						<label>
							<span><b class="font_red">*</b> Sugestão:</span>
							<select name="sector" id="sector" required>
								<option selected value="SUGESTÃO">SUGESTÃO</option>
							</select>
						</label>
					</div>

					<div class="clear">
						<label><br/>
							<span><b class="font_red">*</b> Descreva sua sugestão:</span>
							<textarea class="required" rows="6" name="complaint"
							          placeholder="Digite nesse espaço sua sugestão..." required></textarea>
						</label>
					</div>
					<!-- security start -->
					<div class="capcha">
						<span>Realize a soma <b class="font_red">*</b> antes de enviar sua sugestão</span>
						<input type="text" name="senderHuman" id="senderHuman"
						       class="input-md input-rounded form-control" placeholder="" required>
						<input type="hidden" name="checkHuman_a" id="checkHuman_a">
						<input type="hidden" name="checkHuman_b" id="checkHuman_b">
					</div>
					<!-- security end -->
					<div class="clear_">
						<label for="privacy-s" class="label_check">
							<input type="checkbox" id="privacy-s" name="privacy" required>
							<span>
								<b class="text-red" style="font-size: 1.2em">*</b>Consinto com a
								<a rel="shadowbox" href="<?= BASE; ?>/politicas.html" title="Ler a Política de
								Privacidade da ICC SERRA">Política de Privacidade</a>. <small>Ao enviar meus dados, eu
										autorizo o uso dos mesmos para receber o retorno
											da minha sugestão.</small>
							</span>
						</label>
					</div>
					<br/>

					<button class="btn btn_green" style="margin: 0 auto !important;" type="submit">
						<span class="icon-envelop"></span>
						<img class="form_load" src="<?= INCLUDE_PATH; ?>/images/icons/load_w.gif" alt="Aguarde, enviando sugestão!" title="Aguarde, enviando sugestão!"/> Enviar Sugestão!
					</button>
				</form>

			</div>
			<div class="clear"></div>

		</div>
	</div>
</div>
<?php /* A soma antispam dos três formulários é gerada em js/scripts.js (Onda 4).
   O bloco inline que ficava aqui rodava antes do jQuery e só produzia "$ is not defined". */ ?>
<script defer src="<?= BASE;?>/_cdn/formattext.js"></script>
