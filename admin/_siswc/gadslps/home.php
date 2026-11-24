<?php

declare(strict_types=1);

use App\Conn\Delete;
use App\Conn\Read;
use App\Helpers\Check;
use App\Models\Pager;

$AdminLevel = LEVEL_WC_GADS_LPS;
if (!APP_GADS_LPS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não está logado <br>ou não tem permissão para acessar essa página!</div>');
}

$Read ??= new Read();

// AUTO DELETE DE RASCUNHOS VAZIOS
if (DB_AUTO_TRASH !== 0) {
    $Delete ??= new Delete();
    $Delete->exeDelete(DB_GADS_LPS, 'WHERE page_title IS NULL AND page_status = :st', 'st=0');
}
?>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-target">LPs Google Ads</h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			LPs de Destino
		</p>
	</div>

	<div class="dashboard_header_search">
		<a title="Nova LP de Destino" href="dashboard.php?wc=gadslps/create" class="btn btn_green icon-plus">Nova LP</a>
	</div>
</header>
<div class="dashboard_content">
    <?php
    $Page = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT) ?: 1;
    $Paginator = new Pager('dashboard.php?wc=gadslps/home&pg=', '<<', '>>', 10);
    $Paginator->exePager($Page, 12);

    $Read->fullRead(
        'SELECT * FROM ' . DB_GADS_LPS . ' ORDER BY page_title ASC, page_date DESC LIMIT :limit OFFSET :offset',
        sprintf('limit=%d&offset=%d', $Paginator->getLimit(), $Paginator->getOffset())
    );

    if (!$Read->getResult()) {
        $Paginator->returnPage();
        echo Check::erro(
            sprintf('<span>Ainda não existem LPs cadastradas %s. Comece criando a primeira LP de destino!</span>', $Admin['user_name']),
            E_USER_NOTICE
        );
    } else {
        foreach ($Read->getResult() as $LP) {
            $LpId = (int)$LP['page_id'];
            $LpName = htmlspecialchars((string)$LP['page_name'], ENT_QUOTES);
            $LpTitle = htmlspecialchars((string)$LP['page_title'], ENT_QUOTES);
            $LpStatus = 1 === (int)$LP['page_status']
                ? '<span class="icon-checkmark font_green">Publicada</span>'
                : '<span class="icon-warning font_yellow">Rascunho</span>';
            $Tags = [];
            foreach (['page_gtag_id', 'page_ads_id', 'page_gtm_id'] as $TagField) {
                if (!empty($LP[$TagField])) {
                    $Tags[] = htmlspecialchars((string)$LP[$TagField], ENT_QUOTES);
                }
            }
            $TagsInfo = $Tags ? implode(' · ', $Tags) : 'Tags globais do site';

            echo "<article class='box box25 page_single' id='{$LpId}'>
                <div class='box_content wc_normalize_height'>
                    <h1 class='title'><a title='Ver página no site' target='_blank' href='" . BASE . "/{$LpName}'>/{$LpName}</a></h1>
                    <p>{$LpStatus}</p>
                    <p><small>{$TagsInfo}</small></p>
                    <p><small>{$LP['page_views']} visitas</small></p>
                </div>
                <div class='page_single_action'>
                    <a title='Editar Página' href='dashboard.php?wc=gadslps/create&id={$LpId}' class='post_single_center icon-pencil btn btn_blue'>Editar</a>
                    <span rel='page_single' class='j_delete_action icon-cancel-circle btn btn_red' id='{$LpId}'>Excluir</span>
                    <span rel='page_single' callback='Gadslps' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$LpId}'>Deletar Página?</span>
                </div>
            </article>";
        }

        $Paginator->exePaginator(DB_GADS_LPS);
        echo $Paginator->getPaginator();
    }
    ?>
</div>
