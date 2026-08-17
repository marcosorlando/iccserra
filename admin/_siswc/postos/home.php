<?php
$AdminLevel = LEVEL_WC_SERVICES_STATIONS;
if (!APP_SERVICES_STATIONS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_SERVICES_STATIONS, "WHERE ss_name IS NULL AND ss_email IS NULL AND status = :st", "st=0");
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$S = filter_input(INPUT_GET, "s", FILTER_DEFAULT);
$Search = filter_input_array(INPUT_POST);
if ($Search && isset($Search['s'])):
    $S = urlencode($Search['s']);
    header("Location: dashboard.php?wc=postos/home&s={$S}");
    exit;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-location">Postos de Atendimento</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Todos os Postos" href="dashboard.php?wc=postos/home">Postos de Atendimento</a>
            <?= ($S ? "<span class='crumb'>/</span> <span class='icon-search'>{$S}</span>" : ''); ?>
        </p>
    </div>

    <div class="dashboard_header_search">
        <form style="width: 100%; display: inline-block;" name="searchStations" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" value="<?= $S; ?>" name="s" placeholder="Pesquisar por nome, cidade, telefone ou e-mail:" style="width: 55%; margin-right: 3px;">
            <button class="btn btn_green icon icon-search icon-notext"></button>
            <a title="Novo Posto" href="dashboard.php?wc=postos/create" class="btn btn_green icon-plus">Adicionar</a>
        </form>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=postos/home&s={$S}&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 50);

    if (!empty($S)):
        $WhereString[0] = "AND (ss_name LIKE '%' :s '%' OR ss_city LIKE '%' :s '%' OR ss_email LIKE '%' :s '%' OR ss_phone LIKE '%' :s '%')";
        $WhereString[1] = "&s={$S}";
    else:
        $WhereString[0] = "";
        $WhereString[1] = "";
    endif;

    $Read->FullRead("SELECT * FROM " . DB_SERVICES_STATIONS . " WHERE 1=1 "
        . "{$WhereString[0]} "
        . "ORDER BY ss_name ASC, ss_city ASC "
        . "LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}{$WhereString[1]}"
    );

    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Ainda não existem postos de atendimento cadastrados {$Admin['user_name']}. Comece criando o primeiro posto!</span>", E_USER_NOTICE);
    else:
        echo "<div class='panel dashboard_search'>";
        echo "<article class='lead_header'>
                <p>NOME</p>
                <p>CIDADE/UF</p>
                <p>TELEFONE</p>
                <p>STATUS</p>
                <p></p>
            </article>";

        foreach ($Read->getResult() as $Station):
            extract($Station);

            $StationName = (!empty($ss_name) ? $ss_name : 'Posto de atendimento sem nome');
            $StationCity = trim((!empty($ss_city) ? $ss_city : '') . (!empty($ss_state) ? "/{$ss_state}" : ''));
            $StationStatus = ($status == 1 ? "<span class='icon-checkmark font_green'>Ativo</span>" : "<span class='icon-cross font_red'>Inativo</span>");
            $StationPhone = (!empty($ss_phone) ? $ss_phone : '-');
            $StationEmail = (!empty($ss_email) ? "<a href='mailto:{$ss_email}' title='Enviar e-mail'>{$ss_email}</a>" : '-');

            echo "<article class='station_single' id='{$id}'>
                    <h1 class='icon-location'><a href='dashboard.php?wc=postos/create&id={$id}' title='Editar posto'>{$StationName}</a><span style='display:block; font-size:0.75em; font-weight:400;'>{$StationEmail}</span></h1>
                    <p>{$StationCity}</p>
                    <p>{$StationPhone}</p>
                    <p>{$StationStatus}</p>
                    <p>
                        <a href='dashboard.php?wc=postos/create&id={$id}' class='btn btn_blue icon-notext icon-pen wc_tooltip'><span class='wc_tooltip_balloon'>Editar</span></a>
                        <span rel='station_single' class='j_delete_action icon-cancel-circle btn btn_red icon-notext wc_tooltip' id='{$id}'><span class='wc_tooltip_balloon'>Deletar</span></span>
                        <span rel='station_single' callback='Postos' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$id}'>Deletar Posto?</span>
                    </p>
                </article>";
        endforeach;

        echo "<div class='clear'></div></div>";

        if (!empty($S)):
            $Paginator->ExePaginator(DB_SERVICES_STATIONS, "WHERE (ss_name LIKE '%' :s '%' OR ss_city LIKE '%' :s '%' OR ss_email LIKE '%' :s '%' OR ss_phone LIKE '%' :s '%')", "s={$S}");
        else:
            $Paginator->ExePaginator(DB_SERVICES_STATIONS);
        endif;
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>
