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
        <header>
          <h1 class="title">Biblioteca do Microcrédito ICC SERRA</h1>
          <em>e-books, webinars, whitepapers, infográficos, kits, planilhas, vídeos</em>
        </header>
        <?php
        $Read->exeRead(DB_MATERIAIS, 'WHERE mat_status = :st ORDER BY mat_date', 'st=1');
        if ($Read->getResult()) {
          foreach ($Read->getResult() as $Mat) {
            extract($Mat);
            ?>

            <article class="materiais" id="<?= $mat_name; ?>">
              <a href="<?= $mat_link; ?>" target="_blank" title="Clique para Salvar">
                <img src="<?= BASE; ?>/uploads/<?= $mat_cover; ?>" alt="<?= $mat_title; ?>">
                <div class="mat-info">
                  <h4><?= $mat_title; ?></h4>
                  <?php
                  // TODO: o rótulo do formato usava getWcMatFormato($mat_type), função perdida na
                  // migração. Reimplementar como Check::getWcMatFormat() com o mapa de formatos
                  // original antes de reexibir. O admin de materiais grava mat_type = 0 hoje.
                  ?>
                </div>
              </a>

            </article>

            <?php
          }
        }
        ?>
        <div class="clear"><br/></div>
        <?php
        $WC_TITLE_LINK = "Biblioteca do Microcrédito ICC SERRA";
        $WC_SHARE_HASH = "IccSerra";
        $WC_SHARE_LINK = BASE . "/materiais";
        require './_cdn/widgets/share/share.wc.php';
        ?>
      </div>
    </div><?php require REQUIRE_PATH . '/inc/sidebar_lines.php'; ?>
  </div>




  <div class="clear"></div>
<!--  <php if (APP_COMMENTS && COMMENT_ON_PAGES): ?>
    <div class="container" style="background: #fff; padding: 0;">
      <div class="content">
        <php
        $CommentKey = $page_id;
        $CommentType = 'page';
        require '_cdn/widgets/comments/comments.php';
        ?>
        <div class="clear"></div>
      </div>
    </div>
  <php endif; ?>-->


</div>


