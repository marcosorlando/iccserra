<?php

use App\Conn\Read;
use App\Models\Crm;

$AdminLevel = LEVEL_WC_CRM;
if (!APP_CRM || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

$ClientId = (int)filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Read->exeRead(DB_CRM_CLIENTS, 'WHERE client_id = :id', 'id=' . $ClientId);
$Client = $Read->getResult()[0] ?? null;
if (!$Client) {
    $_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou abrir um cliente que não existe ou que foi removido recentemente!";
    header('Location: dashboard.php?wc=crm/home');

    exit;
}

$Read->fullRead('SELECT * FROM ' . DB_CRM_SIMULATIONS . ' WHERE client_id = :id ORDER BY sim_created DESC, sim_id DESC', 'id=' . $ClientId);
$Simulations = $Read->getResult() ?? [];
$LastSim = $Simulations[0] ?? null;

$Read->fullRead('SELECT stage_id, stage_name FROM ' . DB_CRM_STAGES . ' ORDER BY stage_order ASC, stage_id ASC');
$Stages = $Read->getResult() ?? [];

$Read->fullRead('SELECT * FROM ' . DB_CRM_TEMPLATES . ' WHERE template_status = 1 ORDER BY template_type ASC, template_title ASC');
$Templates = ['email' => [], 'whatsapp' => []];
foreach ($Read->getResult() ?? [] as $Template) {
    $Templates[$Template['template_type']][] = $Template;
}

$Crm = new Crm();
$Html = static fn(?string $v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$ClientName = $Client['client_name'] ?: $Client['client_email'];
$WhatsPhone = preg_replace('/\D+/', '', (string)$Client['client_phone']);
$WhatsPhone = ('' !== $WhatsPhone && !str_starts_with($WhatsPhone, '55') ? '55' . $WhatsPhone : $WhatsPhone);
?>
<link rel="stylesheet" href="_siswc/crm/crm.css?v=1"/>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-user-check"><?= $Html($ClientName); ?></h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="Funil de atendimento" href="dashboard.php?wc=crm/home">CRM do Simulador</a>
			<span class="crumb">/</span>
			Atendimento
		</p>
	</div>

	<div class="dashboard_header_search">
		<a title="Voltar ao funil" href="dashboard.php?wc=crm/home" class="btn btn_blue icon-filter">Funil</a>
	</div>
</header>

<div class="dashboard_content crm_client">
	<div class="box box70">

		<!-- SIMULAÇÕES -->
		<div class="panel_header default">
			<h2 class="icon-calculator">Simulações (<?= count($Simulations); ?>)</h2>
		</div>
		<div class="panel">
            <?php if (!$Simulations) { ?>
				<p class="crm_empty">Este cliente ainda não possui simulações registradas.</p>
            <?php } else { ?>
				<div class="crm_table_wrap">
					<table class="crm_table">
						<thead>
						<tr>
							<th>Data</th>
							<th>Linha de crédito</th>
							<th>Capital</th>
							<th>Valor</th>
							<th>Parcelas</th>
							<th>TAC</th>
						</tr>
						</thead>
						<tbody>
                        <?php foreach ($Simulations as $Sim) { ?>
							<tr>
								<td><?= date('d/m/Y H\hi', strtotime((string)$Sim['sim_created'])); ?></td>
								<td><?= $Html(Crm::creditLine((string)$Sim['sim_credit_line'])); ?></td>
								<td><?= $Html(Crm::capital((string)$Sim['sim_capital'])); ?></td>
								<td><b><?= Crm::money((float)$Sim['sim_amount']); ?></b></td>
								<td><?= (int)$Sim['sim_installments']; ?>x de <?= Crm::money((float)$Sim['sim_installment_value']); ?></td>
								<td><?= Crm::money((float)$Sim['sim_tac']); ?></td>
							</tr>
                        <?php } ?>
						</tbody>
					</table>
				</div>
            <?php } ?>
		</div>

		<!-- CONTATO -->
		<div class="panel_header default">
			<h2 class="icon-bubbles4">Entrar em contato</h2>
		</div>
		<div class="panel">
			<div class="crm_tabs">
				<a class="crm_tab crm_tab_active icon-whatsapp" href="#crm_whats">WhatsApp</a>
				<a class="crm_tab icon-envelop" href="#crm_email">E-mail</a>
			</div>

			<div class="crm_tab_target crm_tab_active" id="crm_whats">
                <?php if ('' === $WhatsPhone) { ?>
					<p class="crm_empty">Cadastre um telefone para este cliente para usar o WhatsApp.</p>
                <?php } else { ?>
					<form class="ajax_off j_crm_whats" action="" method="post" data-phone="<?= $Html($WhatsPhone); ?>" data-client="<?= $ClientId; ?>">
						<label class="label">
							<span class="legend">Mensagem pré-definida:</span>
							<select class="j_crm_template" data-target="whats">
								<option value="">Escrever mensagem livre</option>
                                <?php foreach ($Templates['whatsapp'] as $Template) { ?>
									<option data-body="<?= $Html(Crm::render($Template['template_body'], $Client, $LastSim)); ?>"><?= $Html($Template['template_title']); ?></option>
                                <?php } ?>
							</select>
						</label>
						<label class="label">
							<span class="legend">Mensagem: <small>(você pode editar antes de enviar)</small></span>
							<textarea name="message" rows="5" class="j_crm_whats_body" placeholder="Escreva a mensagem:"></textarea>
						</label>
						<div class="crm_actions">
							<button class="btn btn_green icon-whatsapp">Abrir no WhatsApp</button>
							<span class="crm_phone icon-phone"><?= $Html($Client['client_phone']); ?></span>
						</div>
					</form>
                <?php } ?>
			</div>

			<div class="crm_tab_target" id="crm_email">
				<form name="crm_email" action="" method="post">
					<input type="hidden" name="callback" value="Crm"/>
					<input type="hidden" name="callback_action" value="email_send"/>
					<input type="hidden" name="client_id" value="<?= $ClientId; ?>"/>
					<label class="label">
						<span class="legend">Mensagem pré-definida:</span>
						<select class="j_crm_template" data-target="email">
							<option value="">Escrever e-mail livre</option>
                            <?php foreach ($Templates['email'] as $Template) { ?>
								<option data-subject="<?= $Html(Crm::render((string)$Template['template_subject'], $Client, $LastSim)); ?>"
								        data-body="<?= $Html(Crm::render($Template['template_body'], $Client, $LastSim)); ?>"><?= $Html($Template['template_title']); ?></option>
                            <?php } ?>
						</select>
					</label>
					<label class="label">
						<span class="legend">Para:</span>
						<input type="text" value="<?= $Html($Client['client_name'] . ' <' . $Client['client_email'] . '>'); ?>" disabled/>
					</label>
					<label class="label">
						<span class="legend">Assunto:</span>
						<input type="text" name="email_subject" class="j_crm_email_subject" placeholder="Assunto do e-mail:" required/>
					</label>
					<label class="label">
						<span class="legend">Mensagem:</span>
						<textarea name="email_body" rows="10" class="j_crm_email_body" placeholder="Escreva o e-mail:" required></textarea>
					</label>
					<div class="crm_actions">
						<button class="btn btn_blue icon-envelop">Enviar e-mail</button>
						<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
					</div>
				</form>
			</div>
		</div>

		<!-- HISTÓRICO -->
		<div class="panel_header default">
			<h2 class="icon-clock">Histórico do atendimento</h2>
		</div>
		<div class="panel">
			<form name="crm_note" action="" method="post" class="crm_note">
				<input type="hidden" name="callback" value="Crm"/>
				<input type="hidden" name="callback_action" value="note_add"/>
				<input type="hidden" name="client_id" value="<?= $ClientId; ?>"/>
				<textarea name="history_desc" rows="2" class="wc_value" placeholder="Registrar uma anotação (ligação, retorno, pendência...):"></textarea>
				<div class="crm_actions">
					<button class="btn btn_green icon-pencil2">Anotar</button>
					<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
				</div>
			</form>
			<div class="crm_history j_crm_history"><?= $Crm->historyHtml($ClientId); ?></div>
		</div>
	</div>

	<div class="box box30">
		<!-- FUNIL -->
		<div class="panel_header default">
			<h2 class="icon-filter">Funil</h2>
		</div>
		<div class="panel">
			<form name="crm_pipeline" action="" method="post">
				<input type="hidden" name="callback" value="Crm"/>
				<input type="hidden" name="callback_action" value="client_pipeline"/>
				<input type="hidden" name="client_id" value="<?= $ClientId; ?>"/>
				<label class="label">
					<span class="legend">Etapa:</span>
					<select name="stage_id">
                        <?php foreach ($Stages as $Stage) { ?>
							<option value="<?= $Stage['stage_id']; ?>" <?= (int)$Client['stage_id'] === (int)$Stage['stage_id'] ? 'selected' : ''; ?>><?= $Html($Stage['stage_name']); ?></option>
                        <?php } ?>
					</select>
				</label>
				<label class="label">
					<span class="legend">Status:</span>
					<select name="client_status">
                        <?php foreach (Crm::STATUS as $Key => $Label) { ?>
							<option value="<?= $Key; ?>" <?= (int)$Client['client_status'] === $Key ? 'selected' : ''; ?>><?= $Label; ?></option>
                        <?php } ?>
					</select>
				</label>
				<div class="crm_actions">
					<button class="btn btn_green icon-share">Atualizar</button>
					<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
				</div>
			</form>
			<p class="crm_meta">
				Entrou em: <b><?= date('d/m/Y H\hi', strtotime((string)$Client['client_created'])); ?></b><br>
				Último contato: <b><?= $Client['client_last_contact'] ? date('d/m/Y H\hi', strtotime((string)$Client['client_last_contact'])) : '-'; ?></b>
			</p>
		</div>

		<!-- DADOS DO CLIENTE -->
		<div class="panel_header default">
			<h2 class="icon-user">Dados do cliente</h2>
		</div>
		<div class="panel">
			<form name="crm_client" action="" method="post">
				<input type="hidden" name="callback" value="Crm"/>
				<input type="hidden" name="callback_action" value="client_update"/>
				<input type="hidden" name="client_id" value="<?= $ClientId; ?>"/>
				<label class="label">
					<span class="legend">Nome:</span>
					<input type="text" name="client_name" value="<?= $Html($Client['client_name']); ?>" required/>
				</label>
				<label class="label">
					<span class="legend">E-mail:</span>
					<input type="email" name="client_email" value="<?= $Html($Client['client_email']); ?>" required/>
				</label>
				<label class="label">
					<span class="legend">Telefone:</span>
					<input type="text" class="formPhone" name="client_phone" value="<?= $Html($Client['client_phone']); ?>"/>
				</label>
				<div class="label_50">
					<label class="label">
						<span class="legend">Cidade:</span>
						<input type="text" name="client_city" value="<?= $Html($Client['client_city']); ?>"/>
					</label>
					<label class="label">
						<span class="legend">UF:</span>
						<input type="text" name="client_state" maxlength="2" value="<?= $Html($Client['client_state']); ?>"/>
					</label>
				</div>
				<label class="label">
					<span class="legend">Enquadramento:</span>
					<select name="client_framework">
						<option value="">Não informado</option>
                        <?php foreach (Crm::FRAMEWORKS as $Key => $Label) { ?>
							<option value="<?= $Key; ?>" <?= $Client['client_framework'] === $Key ? 'selected' : ''; ?>><?= $Label; ?></option>
                        <?php } ?>
					</select>
				</label>
				<label class="label">
					<span class="legend">Observações internas:</span>
					<textarea name="client_notes" rows="4"><?= $Html($Client['client_notes']); ?></textarea>
				</label>
				<div class="crm_actions">
					<button class="btn btn_green icon-floppy-disk">Salvar</button>
					<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
				</div>
			</form>
		</div>

		<div class="crm_delete crm_client_single" id="<?= $ClientId; ?>">
			<span rel="crm_client_single" class="j_delete_action btn btn_red icon-bin" id="<?= $ClientId; ?>">Remover cliente do CRM</span>
			<span rel="crm_client_single" callback="Crm" callback_action="client_delete" class="j_delete_action_confirm btn btn_yellow icon-warning" style="display: none" id="<?= $ClientId; ?>">Confirmar remoção? Simulações e histórico serão apagados.</span>
		</div>
	</div>
</div>

<script src="_siswc/crm/crm.js?v=1"></script>
