<?php

use App\Helpers\Check;

$AdminLevel = LEVEL_WC_PRODUCTS_TRAVI;
if (!APP_PRODUCTS_TRAVI || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

// AUTO DELETE POST TRASH
if (DB_AUTO_TRASH !== 0) {
    $Delete = new Delete();
    $Delete->exeDelete(DB_PDT_SALEUNITS_TRAVI, 'WHERE saleunit_title IS NULL AND saleunit_id >= :st', 'st=1');
}
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-qrcode">Unidades de Venda</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?php echo ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?php echo ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?php echo ADMIN_NAME; ?>" href="dashboard.php?wc=products/home">Produtos</a>
            <span class="crumb">/</span>
            Unidades
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Nova Unidade de Venda" href="dashboard.php?wc=products/saleunit" class="btn btn_green icon-plus">Adicionar
            Unidade!</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $Read->exeRead(DB_PDT_SALEUNITS_TRAVI, 'ORDER By saleunit_title ASC');
if (!$Read->getResult()) {
    echo Check::erro(
        \sprintf(
            "<span class='al_center icon-notification'>Ainda não unidades de venda cadastradas %s. Comece agora mesmo criando suas unidades de venda de produtos!</span>",
            $Admin['user_name']
        ),
        E_USER_NOTICE
    );
} else {
    foreach ($Read->getResult() as $Brand) {
        $Read->fullRead(
            'SELECT count(pdt_id) as total FROM '.DB_PDT_TRAVI.' WHERE pdt_saleunit = :saleunit',
            'saleunit='.$Brand['saleunit_id']
        );
        $TotalPdtBrand = $Read->getResult()[0]['total'];
        echo "<article class='product_saleunit box box100' id='{$Brand['saleunit_id']}'>
            <div class='box_content'>
                <h1 class='icon-stack'>{$Brand['saleunit_title']} <span>{$TotalPdtBrand} produto(s) encontrado(s)</span></h1>
                <a target='_blank' title='Ver Unidade!' href='".BASE."/marca/{$Brand['saleunit_name']}' class='btn btn_green icon-eye icon-notext'></a>
                <a title='Editar Unidade!' href='dashboard.php?wc=products/saleunit&id={$Brand['saleunit_id']}' class='btn btn_blue icon-pencil icon-notext'></a>
                <span rel='product_saleunit' class='j_delete_action btn btn_red icon-cancel-circle icon-notext' id='{$Brand['saleunit_id']}'></span>
                <span rel='product_saleunit' callback='ProductsTravi' callback_action='saleunit_remove' class='j_delete_action_confirm btn btn_yellow icon-warning' style='display: none;' id='{$Brand['saleunit_id']}'>Deletar Unidade?</span>
            </div>
        </article>";
    }
}
?>
</div>
