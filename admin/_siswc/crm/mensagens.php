<?php

use App\Conn\Read;
use App\Helpers\Check;

$AdminLevel = LEVEL_WC_CRM;
if (!APP_CRM || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

$Read->exeRead(DB_CRM_TEMPLATES, 'ORDER BY template_type ASC, template_title ASC');
$Templates = $Read->getResult() ?? [];

// EDIÇÃO
$Edit = [];
$EditId = (int)filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
foreach ($Templates as $Template) {
    if ((int)$Template['template_id'] === $EditId) {
        $Edit = $Template;
    }
}
$EditType = $Edit['template_type'] ?? (string)filter_input(INPUT_GET, 'type', FILTER_DEFAULT);
$EditType = 'email' === $EditType ? 'email' : 'whatsapp';

$Html = static fn(?string $v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$Types = ['whatsapp' => ['WhatsApp', 'icon-whatsapp'], 'email' => ['E-mail', 'icon-envelop']];
?>
<link rel="stylesheet" href="_siswc/crm/crm.css?v=1"/>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-bubbles4">Mensagens Pré-definidas</h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="Funil de atendimento" href="dashboard.php?wc=crm/home">CRM do Simulador</a>
			<span class="crumb">/</span>
			Mensagens
		</p>
	</div>

	<div class="dashboard_header_search">
		<a title="Nova mensagem de WhatsApp" href="dashboard.php?wc=crm/mensagens&type=whatsapp" class="btn btn_green icon-whatsapp">Novo WhatsApp</a>
		<a title="Novo e-mail" href="dashboard.php?wc=crm/mensagens&type=email" class="btn btn_blue icon-envelop">Novo E-mail</a>
		<a title="Voltar ao funil" href="dashboard.php?wc=crm/home" class="btn btn_yellow icon-filter">Funil</a>
	</div>
</header>

<div class="dashboard_content">
	<div class="box box50">
        <?php
        foreach ($Types as $Type => [$Label, $Icon]) {
            $List = array_filter($Templates, static fn($t) => $t['template_type'] === $Type);
            echo "<div class='panel_header default'><h2 class='{$Icon}'>{$Label}</h2></div><div class='panel'>";

            if (!$List) {
                echo "<p class='crm_empty'>Nenhuma mensagem de {$Label} cadastrada.</p>";
            }

            foreach ($List as $Template) {
                $Active = (int)$Template['template_status'] === 1
                    ? "<span class='font_green'>Ativa</span>"
                    : "<span class='font_red'>Inativa</span>";
                $Current = (int)$Template['template_id'] === $EditId ? ' crm_template_current' : '';

                echo "<article class='crm_template crm_template_single{$Current}' id='{$Template['template_id']}'>"
                    . "<header><h3>{$Html($Template['template_title'])}</h3>{$Active}</header>"
                    . ($Template['template_subject'] ? "<p class='crm_template_subject'><b>Assunto:</b> {$Html($Template['template_subject'])}</p>" : '')
                    . "<p>" . nl2br($Html(Check::chars((string)$Template['template_body'], 220))) . '</p>'
                    . "<footer>"
                    . "<a href='dashboard.php?wc=crm/mensagens&id={$Template['template_id']}' class='btn btn_blue icon-pencil2'>Editar</a> "
                    . "<span rel='crm_template_single' class='j_delete_action btn btn_red icon-bin icon-notext' id='{$Template['template_id']}'></span>"
                    . "<span rel='crm_template_single' callback='Crm' callback_action='template_delete' class='j_delete_action_confirm btn btn_yellow icon-warning' style='display: none' id='{$Template['template_id']}'>Remover mensagem?</span>"
                    . '</footer></article>';
            }

            echo '</div>';
        }
        ?>
	</div>

	<div class="box box50">
		<div class="panel_header default">
			<h2 class="<?= $Types[$EditType][1]; ?>"><?= $Edit ? 'Editar mensagem' : 'Nova mensagem'; ?> de <?= $Types[$EditType][0]; ?></h2>
		</div>
		<div class="panel">
			<form name="template_save" action="" method="post">
				<input type="hidden" name="callback" value="Crm"/>
				<input type="hidden" name="callback_action" value="template_save"/>
				<input type="hidden" name="template_id" value="<?= (int)($Edit['template_id'] ?? 0); ?>"/>
				<input type="hidden" name="template_type" value="<?= $EditType; ?>"/>

				<label class="label">
					<span class="legend">Título interno:</span>
					<input type="text" name="template_title" maxlength="150" value="<?= $Html($Edit['template_title'] ?? ''); ?>" placeholder="Ex.: Primeiro contato" required/>
				</label>

                <?php if ('email' === $EditType) { ?>
					<label class="label">
						<span class="legend">Assunto do e-mail:</span>
						<input type="text" name="template_subject" maxlength="255" value="<?= $Html($Edit['template_subject'] ?? ''); ?>" placeholder="Assunto:" required/>
					</label>
                <?php } ?>

				<label class="label">
					<span class="legend">Texto da mensagem:</span>
					<textarea name="template_body" rows="10" required><?= $Html($Edit['template_body'] ?? ''); ?></textarea>
				</label>

				<label class="label_check">
					<input type="checkbox" name="template_status" value="1" <?= !$Edit || (int)$Edit['template_status'] === 1 ? 'checked' : ''; ?>/> Mensagem ativa
				</label>

				<div class="crm_actions">
					<button class="btn btn_green icon-floppy-disk">Salvar mensagem</button>
					<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
				</div>
			</form>

			<div class="crm_vars">
				<p><b>Variáveis disponíveis</b> (substituídas pelos dados do cliente e da última simulação):</p>
				<p>
					<code>{nome}</code> <code>{primeiro_nome}</code> <code>{email}</code> <code>{telefone}</code> <code>{cidade}</code>
					<code>{valor}</code> <code>{parcelas}</code> <code>{valor_parcela}</code> <code>{linha_credito}</code>
					<code>{tipo_capital}</code> <code>{data_simulacao}</code> <code>{site_nome}</code> <code>{site_telefone}</code> <code>{site_email}</code>
				</p>
			</div>
		</div>
	</div>
</div>
