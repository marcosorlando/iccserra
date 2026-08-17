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
                    <h1 class="title al_center"><?= $page_subtitle; ?></h1>
                </header>

                <div class="htmlchars">
                    <?php
                        require_once REQUIRE_PATH . '/simulador/simulacao.inc.php';
                    ?>

                </div>
                <div class="clear"></div>


                <div class="clear"></div>
                <section class="wc_line">
                    <h1 class="title" style="margin-bottom: -80px;">CONHEÇA NOSSAS LINHAS DE CRÉDITO</h1>
                    <?php require REQUIRE_PATH . '/inc/linha_microcredito_produtivo.php'; ?>
                    <?php require REQUIRE_PATH . '/inc/linha_reforma.php'; ?>
                    <?php require REQUIRE_PATH . '/inc/linha_agricola.php'; ?>
                </section>
            </div>
        </div><?php require REQUIRE_PATH . '/inc/sidebar_lines.php'; ?>
        <div class="clear"></div>
    </div>
</div>

<script>
    document.getElementById('printar').onclick = function () {
        var conteudo = document.getElementById('result_simulator').innerHTML,
            tela_impressao = window.open('about:blank');

        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    };
</script>