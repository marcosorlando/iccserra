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
			<header class="site_header">
				<h1>
					<strong><?= $page_title; ?></strong>
				</h1>
				<p><?= $page_subtitle; ?></p>
			</header>

			<div class="htmlchars">
				<form name="form_candidate" id="form_candidate" class="animated fadeIn j_formsubmit form_capitalize"
				      method="post" enctype="multipart/form-data">
					<input class="callback" type="hidden" name="callback" value="Contact">
					<input class="callback-action" type="hidden" name="callback_action" value="contact">

					<label>
						<span>Nome:</span>
						<input type="text" placeholder="Seu Nome" name="nome" required/>
					</label>
					<div class="label_50">
						<label>
							<span>E-mail:</span>
							<input type="text" placeholder="E-mail" name="email" required/>
						</label>
						<label>
							<span>Telefone/WhatsApp:</span>
							<input type="text" class="formPhone" placeholder="WhatsApp" name="telefone" required/>
						</label>
					</div>
					<div class="label_50">
						<label>
							<span>Cidade:</span>
							<input type="text" name="cidade" placeholder="Informe sua cidade:" required/>
						</label>
						<label>
							<span>Estado: (atendemos somente RS)</span>
							<select name="estado" id="estado" required>
								<option value="AC">AC</option>
								<option value="AL">AL</option>
								<option value="AP">AP</option>
								<option value="AM">AM</option>
								<option value="BA">BA</option>
								<option value="CE">CE</option>
								<option value="ES">ES</option>
								<option value="DF">DF</option>
								<option value="MA">MA</option>
								<option value="MT">MT</option>
								<option value="MS">MS</option>
								<option value="MG">MG</option>
								<option value="PA">PA</option>
								<option value="PB">PB</option>
								<option value="PR">PR</option>
								<option value="PE">PE</option>
								<option value="PI">PI</option>
								<option value="RJ">RJ</option>
								<option value="RN">RN</option>
								<option selected value="RS">RS</option>
								<option value="RO">RO</option>
								<option value="RR">RR</option>
								<option value="SC">SC</option>
								<option value="SP">SP</option>
								<option value="SE">SE</option>
								<option value="TO">TO</option>
							</select>
						</label>
					</div>

					<div class="clear">
						<label><br/>
							<span>Assunto:</span>
							<input type="text" placeholder="Qual o assunto do contato?" name="assunto" required/>
						</label>
						<label><br/>
							<span>Mensagem:</span>
							<textarea name="mensagem" rows="5" placeholder="Deixe sua mensagem:" required></textarea>
						</label>
					</div>

					<div class="clear" id="denuncia">
						<label for="privacy" class="label_check">
							<input type="checkbox" id="privacy" name="privacy" required>
							<span>
								<b class="text-red" style="font-size: 1.2em">*</b>Consinto com a
								<a rel="shadowbox" href="<?= BASE; ?>/politicas.html"
								   title="Ler a Política de Privacidade da ICC SERRA">Política de Privacidade.</a>
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

					<button class="btn btn_red" style="margin: 0 auto !important;" type="submit">
						<span class="icon-envelop"></span><img class="form_load invisivel" style="margin-left: 10px;"
						                                       src="<?= BASE; ?>/themes/wc_icc/images/icons/load_w.gif"
						                                       alt="Aguarde, enviando simulação!"
						                                       title="Aguarde, enviando simulação!"/> Enviar Contato!
					</button>
				</form>
			</div>
			<div class="clear"></div>

		</div>
	</div>
</div>
<script defer src="<?= BASE; ?>/_cdn/formattext.js"></script>
<script defer src="<?= INCLUDE_PATH; ?>/lp/js/lp.js"></script>
