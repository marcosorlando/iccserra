<?php

use App\Conn\Read;

$AdminLevel = LEVEL_WC_CRM;
if (!APP_CRM || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

$Read->fullRead(
    'SELECT s.*, (SELECT COUNT(*) FROM ' . DB_CRM_CLIENTS . ' c WHERE c.stage_id = s.stage_id) AS clients'
    . ' FROM ' . DB_CRM_STAGES . ' s ORDER BY s.stage_order ASC, s.stage_id ASC'
);
$Stages = $Read->getResult() ?? [];
$Html = static fn(?string $v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<link rel="stylesheet" href="_siswc/crm/crm.css?v=1"/>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-list2">Etapas do Funil</h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="Funil de atendimento" href="dashboard.php?wc=crm/home">CRM do Simulador</a>
			<span class="crumb">/</span>
			Etapas
		</p>
	</div>

	<div class="dashboard_header_search">
		<a title="Voltar ao funil" href="dashboard.php?wc=crm/home" class="btn btn_blue icon-filter">Funil</a>
	</div>
</header>

<div class="dashboard_content">
	<div class="box box70">
		<div class="panel_header default">
			<h2 class="icon-list2">Etapas cadastradas</h2>
			<p class="subtitle">A ordem abaixo é a ordem das colunas do quadro. Novos leads do simulador entram na primeira etapa.</p>
		</div>
		<div class="panel">
            <?php
            $Last = count($Stages) - 1;
            foreach ($Stages as $Index => $Stage) {
                ?>
				<form class="crm_stage_row crm_stage_single" id="<?= $Stage['stage_id']; ?>" name="stage_<?= $Stage['stage_id']; ?>" action="" method="post">
					<input type="hidden" name="callback" value="Crm"/>
					<input type="hidden" name="callback_action" value="stage_update"/>
					<input type="hidden" name="stage_id" value="<?= $Stage['stage_id']; ?>"/>

					<span class="crm_stage_pos"><?= $Index + 1; ?>º</span>
					<input type="color" name="stage_color" value="<?= $Html($Stage['stage_color']); ?>" title="Cor da etapa"/>
					<input type="text" name="stage_name" value="<?= $Html($Stage['stage_name']); ?>" maxlength="100" required/>
					<span class="crm_stage_count" title="Clientes nesta etapa"><?= (int)$Stage['clients']; ?> leads</span>

					<span class="crm_stage_buttons">
						<button class="btn btn_green icon-floppy-disk icon-notext wc_tooltip"><span class="wc_tooltip_balloon">Salvar</span></button>
                        <?php if ($Index > 0) { ?>
							<span class="btn btn_blue icon-arrow-up2 icon-notext wc_tooltip j_wc_action" data-callback="Crm" data-callback-action="stage_up" data-value="<?= $Stage['stage_id']; ?>"><span class="wc_tooltip_balloon">Subir</span></span>
                        <?php } ?>
                        <?php if ($Index < $Last) { ?>
							<span class="btn btn_blue icon-arrow-down2 icon-notext wc_tooltip j_wc_action" data-callback="Crm" data-callback-action="stage_down" data-value="<?= $Stage['stage_id']; ?>"><span class="wc_tooltip_balloon">Descer</span></span>
                        <?php } ?>
                        <?php if ($Last > 0) { ?>
							<span rel="crm_stage_single" class="j_delete_action btn btn_red icon-bin icon-notext wc_tooltip" id="<?= $Stage['stage_id']; ?>"><span class="wc_tooltip_balloon">Remover</span></span>
							<span rel="crm_stage_single" callback="Crm" callback_action="stage_delete" class="j_delete_action_confirm btn btn_yellow icon-warning" style="display: none" id="<?= $Stage['stage_id']; ?>">Remover?</span>
                        <?php } ?>
					</span>
				</form>
                <?php
            }
            ?>
			<div class="clear"></div>
		</div>
	</div>

	<div class="box box30">
		<div class="panel_header default">
			<h2 class="icon-plus">Nova etapa</h2>
		</div>
		<div class="panel">
			<form name="stage_create" action="" method="post">
				<input type="hidden" name="callback" value="Crm"/>
				<input type="hidden" name="callback_action" value="stage_create"/>
				<label class="label">
					<span class="legend">Nome:</span>
					<input type="text" name="stage_name" maxlength="100" placeholder="Etapa <?= count($Stages) + 1; ?>"/>
				</label>
				<label class="label">
					<span class="legend">Cor:</span>
					<input type="color" name="stage_color" value="#5d6d7e"/>
				</label>
				<div class="crm_actions">
					<button class="btn btn_green icon-plus">Adicionar etapa</button>
					<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
				</div>
			</form>
			<p class="crm_meta">Ao remover uma etapa, os leads dela são movidos para a primeira etapa restante do funil.</p>
		</div>
	</div>
</div>
