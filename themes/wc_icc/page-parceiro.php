<?php

use App\Conn\Read;

$Read ??= new Read();

$Read->exeRead(DB_PAGES, 'WHERE page_name = :nm AND page_status = 1', "nm={$URL[0]}");
if (!$Read->getResult()) {
    require REQUIRE_PATH . '/404.php';

    return;
}

extract($Read->getResult()[0]);
?>

<div class="container post_single">
  <div class="content">
    <div class="left_content">
      <div class="post_content">
        <header class="padding-bottom-0"><h1 class="title center"><?= $page_subtitle; ?></h1></header>

        <div class="htmlchars padding-top-0">
	        <?php require_once REQUIRE_PATH . '/inc/partner_form.php'; ?>
        </div>
        <div class="clear"></div>

        <?php
            $WC_TITLE_LINK = "Quer ser parceiro da ICC Serra e ter mais Linhas de Crédito para ofertar aos seus clientes";
            $WC_SHARE_HASH = "IccSerra";
            $WC_SHARE_LINK = BASE . "/parceiro";
            require './_cdn/widgets/share/share.wc.php';
        ?>
        <div class="clear"></div>
        <section class="wc_line">
          <?php require REQUIRE_PATH . '/inc/linha_microcredito_produtivo.php'; ?>
          <?php require REQUIRE_PATH . '/inc/linha_reforma.php'; ?>
          <?php require REQUIRE_PATH . '/inc/linha_agricola.php'; ?>
        </section>
      </div>
    </div><?php require REQUIRE_PATH . '/inc/sidebar_lines.php'; ?>
    <div class="clear"></div>
  </div>
</div>

<script src="<?= INCLUDE_PATH; ?>/lp/js/lp.js"></script>
