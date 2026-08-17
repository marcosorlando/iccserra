<?php

use App\Helpers\Check;

$AdminLevel = LEVEL_WC_PRODUCTS_TRAVI;
if (!APP_PRODUCTS_TRAVI || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

// AUTO INSTANCE OBJECT CREATE
$Create ??= new Create();

$SaleId = \filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($SaleId) {
    $Read->exeRead(DB_PDT_SALEUNITS_TRAVI, 'WHERE saleunit_id = :id', 'id='.$SaleId);
    if ($Read->getResult()) {
        $FormData = \array_map(
            fn ($v) => \htmlspecialchars((string) (\is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $Read->getResult()[0]
        );
        \extract($FormData);
    } else {
        $_SESSION['trigger_controll'] = Check::erro(
            \sprintf(
                '<b>OPPSS %s</b>, você tentou editar um unidade de venda que não existe ou que foi removido recentemente!',
                $Admin['user_name']
            ),
            E_USER_NOTICE
        );
        \header('Location: dashboard.php?wc=products/saleunits');

        exit;
    }
} else {
    $Date = \date('Y-m-d H:i:s');
    $Title = 'Nova Unidade de Venda - '.$Date;
    $Name = Check::name($Title);
    $CarCreate = ['saleunit_name' => $Name, 'saleunit_created' => $Date];
    $Create->exeCreate(DB_PDT_SALEUNITS_TRAVI, $CarCreate);
    \header('Location: dashboard.php?wc=products/saleunit&id='.$Create->getResult());

    exit;
}
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-price-tags"><?php echo $saleunit_title ? $saleunit_title : 'Nova Unidade de Venda'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?php echo ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?php echo ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?php echo ADMIN_NAME; ?>" href="dashboard.php?wc=products/home">Produtos</a>
            <span class="crumb">/</span>
            <a title="<?php echo ADMIN_NAME; ?>" href="dashboard.php?wc=products/saleunits">Unidades</a>
            <span class="crumb">/</span>
            Gerenciar Unidades
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Unidades de venda!" href="dashboard.php?wc=products/saleunits" class="btn btn_blue icon-eye">Ver
            Unidaddes de Venda!</a>
        <a title="Nova Unidade de VEnda" href="dashboard.php?wc=products/saleunit" class="btn btn_green icon-plus">Adicionar
            Unidade de Venda!</a>
    </div>

</header>

<div class="dashboard_content">
    <div class="box box100">

        <div class="panel_header default">
            <h2 class="icon-price-tags">Dados da Unidade de Venda</h2>
        </div>
        <div class="panel">
            <form class="auto_save" name="category_add" action="" method="post" enctype="multipart/form-data">
                <div class="callback_return"></div>
                <input type="hidden" name="callback" value="ProductsTravi"/>
                <input type="hidden" name="callback_action" value="saleunit_manager"/>
                <input type="hidden" name="saleunit_id" value="<?php echo $SaleId; ?>"/>
                <label class="label">
                    <span class="legend">Unidade de Venda:</span>
                    <input style="font-size: 1.5em;" type="text" name="saleunit_title" value="<?php echo $saleunit_title; ?>"
                           placeholder="Nome da Unidade de Venda:" required/>
                </label>

                <div class="m_top">&nbsp;</div>
                <img class="form_load fl_right none" style="margin-left: 10px; margin-top: 2px;"
                     alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                <button class="btn btn_green icon-price-tags fl_right">Atualizar Unidade!</button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>
