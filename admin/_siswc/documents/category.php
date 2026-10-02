<?php

use App\Conn\Create;
use App\Conn\Read;
use App\Helpers\Check;

$AdminLevel = LEVEL_WC_DOCUMENTS;
if (!APP_DOCUMENTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

$Read ??= new Read();
$Create ??= new Create();

$CatId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($CatId) {
    $Read->exeRead(DB_DOCS_CATEGORIES, 'WHERE category_id = :id', 'id=' . $CatId);
    if ($Read->getResult()) {
        $FormData = array_map(
            static fn($value) => Check::safeHtmlChars(is_scalar($value) ? (string)$value : null),
            $Read->getResult()[0]
        );
        extract($FormData);
    } else {
        $_SESSION['trigger_controll'] = sprintf(
            '<b>OPPSS %s</b>, você tentou editar uma categoria que não existe ou que foi removida recentemente!',
            $Admin['user_name']
        );
        header('Location: dashboard.php?wc=documents/categories');

        exit;
    }
} else {
    $Date = date('Y-m-d H:i:s');
    $Title = "Nova Categoria - {$Date}";
    $Name = Check::name($Title);
    $CatCreate = ['category_name' => $Name, 'category_date' => $Date];
    $Create->exeCreate(DB_DOCS_CATEGORIES, $CatCreate);
    header('Location: dashboard.php?wc=documents/category&id=' . $Create->getResult());

    exit;
}
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-price-tags">Adicionar Categoria</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=documents/home">Documentos</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=documents/categories">Categorias</a>
            <span class="crumb">/</span>
            Nova Categoria
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Categorias!" href="dashboard.php?wc=documents/categories" class="btn btn_blue icon-eye">Ver</a>
        <a title="Nova Categoria" href="dashboard.php?wc=documents/category" class="btn btn_green icon-plus">Adicionar</a>
    </div>

</header>
<div class="dashboard_content">
    <article class="box box100">
        <header>
            <h1>Cadastrar Categoria de Documentos:</h1>
        </header>
        <div class="box_content">
            <form class="auto_save" name="category_add" action="" method="post" enctype="multipart/form-data">
                <div class="callback_return"></div>
                
                <input type="hidden" name="callback" value="Documents"/>
                <input type="hidden" name="callback_action" value="category_add"/>
                <input type="hidden" name="category_id" value="<?= $CatId; ?>"/>
               
                <label class="label">
                    <span class="legend">Nome:</span>
                    <input style="font-size: 1.5em;" type="text" name="category_title" value="<?= $category_title; ?>" placeholder="Título da Categoria:" required/>
                </label>

                <label class="label">
                    <span class="legend">Descrição:</span>
                    <textarea style="font-size: 1.2em;" name="category_content" rows="3" placeholder="Sobre a Categoria:" required><?= $category_content; ?></textarea>
                </label>

                <div class="m_top">&nbsp;</div>
                <img class="form_load fl_right none" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                <button class="btn btn_green icon-price-tags fl_right">Atualizar Categoria!</button>
                <div class="clear"></div>
            </form>
        </div>
    </article>
</div>
