<form name="form_contact" class="j_formsubmit padding-top-0" action="" method="post" enctype="multipart/form-data">

	<input type="hidden" name="callback" class="callback" value="Contact"/>
	<input type="hidden" name="callback_action" class="callback_action" value="partner"/>

	<div class="container flex">
		<label class="grow2">
			<span><b class="font_red">*</b>Nome:</span>
			<input type="text" placeholder="Seu Nome" name="name" required/>
		</label>

		<label class="grow0">
			<span><b class="font_red">*</b>CPF:</span>
			<input type="text" class="formCpf" placeholder="Infome seu CPF" name="cpf" required/>
		</label>
	</div>

	<div class="container flex">
		<label>
			<span><b class="font_red">*</b>E-mail:</span>
			<input type="text" placeholder="E-mail" name="email" required/>
		</label>

		<label>
			<span><b class="font_red">*</b>Telefone/WhatsApp:</span>
			<input type="text" class="formPhone" placeholder="WhatsApp" name="telephone" required/>
		</label>
	</div>

	<div class="container flex">
		<label class="grow0">
			<span><b class="font_red">*</b><span id="doc">CNPJ:</span></span>
			<input type="text" class="formCnpj wc_getCnpj" placeholder="Infome seu CNPJ" name="cnpj" maxlength="18"
			       required/>
		</label>
		<label class="grow2">
			<span><b class="font_red">*</b>Nome da Empresa:</span>
			<input type="text" placeholder="Informe a empresa" name="company" class="wc_nome" required/>
		</label>

		<label class="grow0" id="cep">
			<span><b class="font_red">*</b>CEP:</span>
			<input type="text" class="formCep wc_getCep wc_cep" placeholder="Infome seu CEP" name="cep" required/>
		</label>
	</div>

	<div class="container flex">
		<label class="grow2">
			<span><b class="font_red">*</b>Endereço:</span>
			<input type="text" placeholder="Qual o seu endereço?" name="address" class="wc_logradouro" required/>
		</label>

		<label class="grow0">
			<span><b class="font_red">*</b>Nº:</span>
			<input type="number" min="1" placeholder="Qual o número?" name="number" class="wc_numero" required/>
		</label>

		<label class="grow0">
			<span><b class="font_red">*</b>Complemento:</span>
			<input type="text" placeholder="Ex. Térreo, Sala, Apto, Casa" name="complement" class="wc_complemento"
			       required/>
		</label>
	</div>

	<div class="container flex">
		<label class="grow2">
			<span><b class="font_red">*</b>Bairro:</span>
			<input type="text" placeholder="Qual o seu bairro?" name="neigthborhood" class="wc_bairro" required/>
		</label>

		<label class="grow2">
			<span><b class="font_red">*</b>Cidade:</span>
			<input type="text" placeholder="Qual a cidade?" name="city" class="wc_municipio" required/>
		</label>

		<label class="grow0">
			<span><b class="font_red">*</b>UF: (Sigla)</span>
			<input type="text" placeholder="Qual o estado?" maxlength="2" name="state" class="wc_uf" required/>
		</label>
	</div>

	<input type="hidden" name="atividade_principal" class="wc_atividade_principal" value="Não informado"/>
	<input type="hidden" name="atividades_secundarias" class="wc_atividades_secundarias" value="Não informado"/>
	<input type="hidden" name="abertura" class="wc_abertura" value="Não informado"/>
	<input type="hidden" name="data_situacao" class="wc_data_situacao" value="Não informado"/>
	<input type="hidden" name="telefone" class="wc_telefone" value="Não informado"/>
	<input type="hidden" name="qsa" class="wc_qsa" value="Não informado"/>
	<input type="hidden" name="situacao" class="wc_situacao" value="Não informado"/>
	<input type="hidden" name="capital_social" class="wc_capital_social" value="Não informado"/>


	<label>
		<span><b class="font_red">*</b>Informe porque quer ser nosso Parceiro:</span>
		<textarea name="message" rows="5" placeholder="Deixe sua mensagem:" required></textarea>
	</label>


	<!-- security start -->
	<div class="clear_" id="denuncia">
		<label for="privacy" class="label_check">
			<input type="checkbox" id="privacy" name="privacy" required>
			<span><b class="text-red" style="font-size: 1.2em">*</b>Consinto com a<a rel="shadowbox" href="<?= BASE;
			?>/politicas.html" title="Ler a Política de Privacidade da ICC SERRA">Política de Privacidade</a>.
				<small>Ao enviar meus dados, eu autorizo o uso dos mesmos para receber o retorno da minha solicitação.</small></span>
		</label>
	</div>
	<div class="capcha">
		<span>Realize a Soma * antes de enviar sua Mensagem</span>
		<input type="text" name="senderHuman" id="senderHuman" class="input-md input-rounded form-control"
		       placeholder="" required>
		<input type="hidden" name="checkHuman_a" id="checkHuman_a">
		<input type="hidden" name="checkHuman_b" id="checkHuman_b">
	</div>
	<!-- security end -->

	<div class="center-col">
		<button class="btn btn_red center-col margin-15px-top">Enviar Solicitação!
			<img width="30px" height="30px" class="form_load ds_none" src="<?= BASE; ?>/admin/_img/load_w.gif"/>
		</button>
	</div>

</form>
