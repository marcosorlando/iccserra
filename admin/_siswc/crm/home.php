<?php

use App\Conn\Read;
use App\Helpers\Check;
use App\Models\Crm;

$AdminLevel = LEVEL_WC_CRM;
if (!APP_CRM || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

$S = (string)filter_input(INPUT_GET, 's', FILTER_DEFAULT);
$Search = filter_input_array(INPUT_POST);
if ($Search && isset($Search['s'])) {
    $Status = (int)($Search['st'] ?? Crm::STATUS_OPEN);
    header('Location: dashboard.php?wc=crm/home&st=' . $Status . '&s=' . Check::safeUrlEncode($Search['s']));

    exit;
}

$Status = filter_input(INPUT_GET, 'st', FILTER_VALIDATE_INT);
$Status = (false === $Status || null === $Status) ? Crm::STATUS_OPEN : $Status;

// ETAPAS DO FUNIL
$Read->fullRead('SELECT stage_id, stage_name, stage_color FROM ' . DB_CRM_STAGES . ' ORDER BY stage_order ASC, stage_id ASC');
$Stages = $Read->getResult() ?? [];
$FirstStage = $Stages[0]['stage_id'] ?? null;

// CLIENTES + ÚLTIMA SIMULAÇÃO
$Where = [];
$Places = [];
if (isset(Crm::STATUS[$Status])) {
    $Where[] = 'c.client_status = :st';
    $Places['st'] = $Status;
}
if ('' !== $S) {
    $Where[] = "(c.client_name LIKE CONCAT('%', :s, '%') OR c.client_email LIKE CONCAT('%', :s, '%') OR c.client_phone LIKE CONCAT('%', :s, '%') OR c.client_city LIKE CONCAT('%', :s, '%'))";
    $Places['s'] = $S;
}

$Read->fullRead(
    'SELECT c.*, s.sim_amount, s.sim_credit_line, s.sim_installments, s.sim_created,'
    . ' (SELECT COUNT(*) FROM ' . DB_CRM_SIMULATIONS . ' x WHERE x.client_id = c.client_id) AS sim_total'
    . ' FROM ' . DB_CRM_CLIENTS . ' c'
    . ' LEFT JOIN ' . DB_CRM_SIMULATIONS . ' s ON s.sim_id = (SELECT MAX(y.sim_id) FROM ' . DB_CRM_SIMULATIONS . ' y WHERE y.client_id = c.client_id)'
    . ($Where ? ' WHERE ' . implode(' AND ', $Where) : '')
    . ' ORDER BY COALESCE(s.sim_created, c.client_created) DESC LIMIT 1000',
    $Places ? http_build_query($Places) : null
);

$Board = [];
foreach ($Read->getResult() ?? [] as $Client) {
    $StageKey = $Client['stage_id'] ?: $FirstStage;
    $Board[$StageKey][] = $Client;
}

$Html = static fn(?string $v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<link rel="stylesheet" href="_siswc/crm/crm.css?v=1"/>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-filter">CRM do Simulador</h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="Funil de atendimento" href="dashboard.php?wc=crm/home">CRM do Simulador</a>
            <?= ('' !== $S ? "<span class='crumb'>/</span> <span class='icon-search'>" . $Html($S) . '</span>' : ''); ?>
		</p>
	</div>

	<div class="dashboard_header_search">
		<form class="ajax_off crm_filter" name="searchCrm" action="" method="post">
			<select name="st" title="Status do atendimento">
                <?php
                foreach (Crm::STATUS as $Key => $Label) {
                    echo "<option value='{$Key}'" . ($Status === $Key ? ' selected' : '') . ">{$Label}</option>";
                }
                ?>
				<option value="0" <?= !isset(Crm::STATUS[$Status]) ? 'selected' : ''; ?>>Todos</option>
			</select>
			<input type="search" value="<?= $Html($S); ?>" name="s" placeholder="Nome, e-mail, telefone ou cidade:">
			<button class="btn btn_green icon icon-search icon-notext"></button>
		</form>
		<a title="Etapas do funil" href="dashboard.php?wc=crm/etapas" class="btn btn_blue icon-list2">Etapas</a>
		<a title="Mensagens pré-definidas" href="dashboard.php?wc=crm/mensagens" class="btn btn_yellow icon-bubbles4">Mensagens</a>
	</div>
</header>

<div class="dashboard_content">
    <?php
    if (!$Stages) {
        echo Check::erro("<span class='icon-notification'>Nenhuma etapa cadastrada no funil. <a href='dashboard.php?wc=crm/etapas'>Cadastre as etapas</a> para começar.</span>", E_USER_NOTICE);
    } else {
        echo "<div class='crm_board j_crm_board'>";
        foreach ($Stages as $Stage) {
            $Cards = $Board[$Stage['stage_id']] ?? [];
            $Total = array_sum(array_map(static fn($c) => (float)$c['sim_amount'], $Cards));

            echo "<section class='crm_column' style='--stage-color: {$Html($Stage['stage_color'])}'>";
            echo "<header class='crm_column_header'><h2>{$Html($Stage['stage_name'])}</h2>"
                . "<p><b class='j_crm_count'>" . count($Cards) . "</b> leads &bull; <span class='j_crm_total'>" . Crm::money($Total) . '</span></p></header>';
            echo "<div class='crm_column_body j_crm_drop' data-stage='{$Stage['stage_id']}'>";

            foreach ($Cards as $Card) {
                $Name = $Card['client_name'] ?: $Card['client_email'];
                $Line = $Card['sim_credit_line'] ? Crm::creditLine($Card['sim_credit_line']) : 'Sem simulação';
                $Date = date('d/m/Y H\hi', strtotime((string)($Card['sim_created'] ?: $Card['client_created'])));
                $StatusCss = (int)$Card['client_status'] === Crm::STATUS_WON ? 'crm_card_won' : ((int)$Card['client_status'] === Crm::STATUS_LOST ? 'crm_card_lost' : '');
                $Whats = $Card['client_phone']
                    ? "<a draggable='false' class='crm_card_whats icon-whatsapp' target='_blank' href='" . $Html(Check::whatsMessage((string)$Card['client_phone'], '')) . "' title='Abrir WhatsApp'></a>"
                    : '';

                echo "<article class='crm_card {$StatusCss}' draggable='true' data-client='{$Card['client_id']}' data-amount='" . (float)$Card['sim_amount'] . "'>"
                    . "<h3><a draggable='false' href='dashboard.php?wc=crm/cliente&id={$Card['client_id']}' title='Abrir atendimento'>{$Html($Name)}</a></h3>"
                    . "<p class='crm_card_value'>" . Crm::money((float)$Card['sim_amount'])
                    . ($Card['sim_installments'] ? " <small>em {$Card['sim_installments']}x</small>" : '') . '</p>'
                    . "<p class='crm_card_line'>{$Html($Line)}"
                    . ((int)$Card['sim_total'] > 1 ? " <span class='crm_badge' title='Simulações feitas'>{$Card['sim_total']} simulações</span>" : '') . '</p>'
                    . "<p class='crm_card_meta'><span class='icon-location'>{$Html($Card['client_city'])}</span><span class='icon-clock'>{$Date}</span></p>"
                    . "<footer><span class='icon-phone'>{$Html($Card['client_phone'])}</span>{$Whats}</footer>"
                    . '</article>';
            }

            echo '</div></section>';
        }
        echo '</div>';
        echo "<p class='crm_hint icon-info'>Arraste os cards entre as colunas para mudar a etapa. Exibindo: <b>"
            . (isset(Crm::STATUS[$Status]) ? Crm::status($Status) : 'Todos') . '</b>.</p>';
    }
    ?>
</div>

<script src="_siswc/crm/crm.js?v=1"></script>
