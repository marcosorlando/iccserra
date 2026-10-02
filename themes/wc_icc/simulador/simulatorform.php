<form class="j_formsubmit form_capitalize" id="simulador-credito" novalidate method="post"
      enctype="multipart/form-data">

	<input type="hidden" name="callback" value="Simulador"/>
	<input type="hidden" name="callback_action" value="simular"/>

	<div class="label_50">
		<label>
			<span>*E-mail:</span>
			<input type="email" name="email" value="<?= isset($email) ? $email : ''; ?>"
			       placeholder="Informe seu E-mail:" required/>
		</label>
		<label>
			<span>*Selecione seu enquadramento:</span>
			<select id="cb_enquadramento" name="cb_enquadramento" required="required">
				<option value="" disabled selected>Escolha a aqui!</option>
				<option value="mei" <?= isset($cb_enquadramento) && $cb_enquadramento == 'mei' ? 'selected' : '';
                ?>>Microempreendedor
					Indivídual -
					MEI
				</option>
				<option value="pj" <?= isset($cb_enquadramento) && $cb_enquadramento == 'pj' ? 'selected' : '';
                ?>>Microempresário
				</option>
			</select>
		</label>
	</div>

	<div class="label_50">
		<label>
			<span>*Nome:</span>
			<input type="text" name="nome" value="<?= empty($nome) ? '' : $nome; ?>" placeholder="Informe seu Nome:"
			       required/>
		</label>
		<label>
			<span>*Telefone:</span>
			<input type="text" class="formPhone" name="telefone" value="<?= empty($telefone) ? '' : $telefone; ?>"
			       placeholder="Informe seu Telefone:" required/>
		</label>
	</div>

	<div class="label_50">
		<label>
			<span>*Cidade:</span>
			<input type="text" name="cidade" value="<?= empty($cidade) ? '' : $cidade; ?>"
			       placeholder="Informe sua cidade:" required/>
		</label>
		<label>
			<span>*Estado:</span>
			<select name="estado" id="estado">
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
	<div class="label_50">
		<label>
			<span>*Tipo de Capital:</span>
			<select id="modalidade" name="modalidade" onChange="showDiv();" required="required">
				<option value="" disabled selected>Escolha a finalidade</option>
				<option value="giro">Capital de Giro</option>
				<option value="fixo">Capital Fixo</option>
			</select>
		</label>

		<label>
			<span>*Linha de Crédito:</span>
			<select name="tipo_credito" id="tipo_credito" onChange="showDiv();" required="required">
				<option value="" disabled selected>Escolha a Modalidade</option>
				<option value="microcredito">Microcrédito Produtivo</option>
				<option value="mulher">Microcrédito Mulher</option>
				<option id="reforma" value="reforma">Crédito para Reforma</option>
				<option id="agricola" value="agricola">Crédito Agricola</option>
				<!--<option id="pessoal" value="pessoal">Crédito Pessoal</option>-->
			</select>

		</label>
	</div>
	<div class="label_50">
		<label>
			<span>*Valor em R$:</span>
			<input name="valor" id="valor" type="number" min="600" max="30000" maxlength="5" required>
		</label>

		<label id="n_parcelas" class="invisivel">
			<span>*Número de Parcelas:</span>
			<select name="parcelas" id="parcelas" required="required"> </select>
		</label>
	</div>
	<!--end #n_parcelas -->
	<div class="clear"></div>
	<!-- security start -->
	<div class="capcha">
		<span>Realize a Soma * antes de enviar sua Simulação</span>
		<input type="text" name="senderHuman" id="senderHuman" class="input-md input-rounded form-control"
		       placeholder="" required>
		<input type="hidden" name="checkHuman_a" id="checkHuman_a">
		<input type="hidden" name="checkHuman_b" id="checkHuman_b">
	</div>
	<!-- security end -->
	<div class="col-xl-12" id="denuncia">
		<div class="">
			<label for="privacy" class="label_check">
				<input type="checkbox" id="privacy" name="privacy" required>
				<span><b class="text-red" style="font-size: 1.2em">*</b>Consinto com a <a rel="shadowbox" href="<?=
                        BASE; ?>/politicas.html" title="Ler a Política de Privacidade da ICC SERRA">Política de
						Privacidade.</a> <small>Ao enviar meus dados, eu autorizo o uso dos mesmos para receber o
						retornoda minha simulação.</small></span>
			</label>
		</div>
	</div>
	<div class="clear"></div>
	<div class="btn_simulator">
		<p id="p_msg" style="font-size: 14px; margin:10px auto;" class="invisivel"></p>
		<button class="btn btn_red" style="margin-top: 30px;">
			<i class="icon-calculator"></i> <img class="form_load" style="margin-left: 10px;"
			                                     src="<?= BASE; ?>/themes/wc_icc/images/icons/load_w.gif"
			                                     alt="Aguarde, enviando simulação!"
			                                     title="Aguarde, enviando simulação!"/> REALIZAR SIMULAÇÃO
		</button>
	</div>

	<h5 style="font-size: 0.95em; text-align: center;">CASO TENHA ALGUMA DÚVIDA SOBRE O SIMULADOR LIGUE<b> (54)
			3217-8787</b> QUE AJUDAREMOS.</h5>

	<h6 style="font-size: 0.95em; text-align: center;"><b>HORÁRIO DE ATENDIMENTO:</b> DE SEGUNDA À SEXTA-FEIRA DAS 9:00H
		AS 17:00H</h6>

</form>
