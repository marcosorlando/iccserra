<?php

use App\Conn\Create;
use App\Conn\Delete;
use App\Conn\Read;
use App\Helpers\Check;
use App\Models\Pager;

$AdminLevel = LEVEL_WC_PRODUCTS_TRAVI;
if (!APP_PRODUCTS_TRAVI || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO DELETE PRODUCT TRASH
if (DB_AUTO_TRASH !== 0) {
    $Delete = new Delete();
    $Delete->exeDelete(DB_PDT_TRAVI, 'WHERE pdt_title IS NULL AND pdt_content IS NULL and pdt_status = :st', 'st=0');
    // AUTO INSTANCE OBJECT READ
    $Read ??= new Read();
    // AUTO TRASH IMAGES
    $Read->fullRead(
        'SELECT image FROM ' . DB_PDT_IMAGE_TRAVI . ' WHERE product_id NOT IN(SELECT pdt_id FROM ' . DB_PDT_TRAVI . ')'
    );
    if ($Read->getResult()) {
        $Delete->exeDelete(
            DB_PDT_IMAGE_TRAVI,
            'WHERE id >= :id AND product_id NOT IN(SELECT pdt_id FROM ' . DB_PDT_TRAVI . ')',
            'id=1'
        );
        foreach ($Read->getResult() as $ImageRemove) {
            if (
                file_exists('../uploads/' . $ImageRemove['image']) && !is_dir(
                    '../uploads/' . $ImageRemove['image']
                )
            ) {
                unlink('../uploads/' . $ImageRemove['image']);
            }
        }
    }
}
// AUTO INSTANCE OBJECT READ
$Read ??= new Read();
// AUTO INSTANCE OBJECT CREATE
$Create ??= new Create();

$S = filter_input(INPUT_GET, 's', FILTER_DEFAULT);
$O = filter_input(INPUT_GET, 'opt', FILTER_DEFAULT);

$WhereString = (empty($S) ? '' : " AND (pdt_title LIKE '%{$S}%' OR pdt_content LIKE '%{$S}%') ");
$WhereOpt = ((empty($O)) ? '' : ' AND (pdt_status != 1) ');

$Search = filter_input_array(INPUT_POST);
if ($Search) {
    $S = urlencode((string)$Search['s']);
    $O = urlencode((string)$Search['opt']);
    header(sprintf('Location: dashboard.php?wc=products/home&opt=%s&s=%s', $O, $S));

    exit;
}
?>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-books">Produtos</h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?php
            echo ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?php
            echo ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span> Produtos
		</p>
	</div>

	<div class="dashboard_header_search">
		<form name="searchPosts" action="" method="post" enctype="multipart/form-data" class="ajax_off">
			<input type="search" name="s" placeholder="Pesquisar:" style="width: 38%; margin-right: 3px;"/>
			<select name="opt" style="width: 45%; margin-right: 3px; padding: 5px 10px">
				<option value="">Todos</option>
				<option <?php
                echo 'outsale' == $O ? "selected='selected'" : ''; ?> value="outsale">Indisponíveis
				</option>
			</select>
			<button class="btn btn_green icon icon-search icon-notext"></button>
		</form>
	</div>

</header>
<div class="dashboard_content">
    <?php
    $RedirectOpt = ('' === $WhereOpt || '0' === $WhereOpt ? '' : '&opt=outsale');
    $Page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
    $Pager = new Pager(sprintf('dashboard.php?wc=products/home%s&page=', $RedirectOpt), '<<', '>>', 5);
    $Pager->exePager($Page, 12);
    $Read->exeRead(
        DB_PDT_TRAVI,
        sprintf(' WHERE 1=1 %s %s ORDER BY pdt_created DESC LIMIT :limit OFFSET :offset', $WhereString, $WhereOpt),
        sprintf('limit=%d&offset=%d', $Pager->getLimit(), $Pager->getOffset())
    );

    if (!$Read->getResult()) {
        $Pager->returnPage();
        echo Check::erro(
            '<span>Ainda não existem produtos cadastrados. Comece agora mesmo criando seu primeiro produto!</span>',
            E_USER_NOTICE
        );
    } else {
        foreach ($Read->getResult() as $Products) {
            extract($Products);
            $PdtImage = ($pdt_cover && file_exists('../uploads/' . $pdt_cover) && !is_dir(
                '../uploads/' . $pdt_cover
            ) ? 'uploads/' . $pdt_cover : 'admin/_img/no_image.jpg');
            $PdtTitle = ($pdt_title ? Check::chars($pdt_title, 45) : 'Edite este produto para coloca-lo a venda!');
            $PdtClass = (1 != $pdt_status ? 'inactive' : '');

            echo "<article class='box box25 single_pdt {$PdtClass}' id='{$pdt_id}'>
            <div class='single_pdt_thumb'>
            <img title='{$PdtTitle}' alt='{$PdtTitle}' src='../tim.php?src={$PdtImage}&w=" . THUMB_W . '&h=' . THUMB_H . "'/>
                <header>
                    <h1><a target='_blank' href='" . BASE . sprintf(
                    "/produto/%s' title='Ver %s no site'>%s</a></h1>",
                    $pdt_name,
                    $PdtTitle,
                    $PdtTitle
                );

            $Read->fullRead(
                'SELECT cat_title FROM ' . DB_PDT_CATS_TRAVI . ' WHERE cat_id = :cat',
                'cat=' . $pdt_category
            );
            $Category = ($Read->getResult() ? $Read->getResult()[0]['cat_title'] : 'indefinida');

            $Read->fullRead(
                'SELECT cat_title FROM ' . DB_PDT_CATS_TRAVI . ' WHERE cat_id = :cat',
                'cat=' . $pdt_subcategory
            );
            $SubCategory = ($Read->getResult() ? $Read->getResult()[0]['cat_title'] : 'indefinida');

            echo "</header>
            </div>
            <div class='box_content'>
                <div class='single_pdt_info wc_normalize_height'>
             <p>Cor/Padrão: <b>{$pdt_color}</b></p>
                    <p>Em: <b>{$Category}</b> &raquo; <b>{$SubCategory}</b></p>
                </div>
            </div>
            <div class='single_pdt_actions'>
                <a title='Editar produto' href='dashboard.php?wc=products/create&id={$pdt_id}' class='post_single_center icon-pencil btn btn_blue'>Editar</a>
                <span rel='single_pdt' class='j_delete_action icon-cancel-circle btn btn_red' id='{$pdt_id}'>Excluir</span>
                <span rel='single_pdt' callback='ProductsTravi' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$pdt_id}'>Remover Produto?</span>
            </div>
        </article>";
        }

        $Pager->exePaginator(DB_PDT_TRAVI, sprintf('WHERE 1 = 1 %s %s', $WhereString, $WhereOpt));
        echo $Pager->getPaginator();
    }
    ?>
</div>
