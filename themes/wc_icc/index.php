<?php

use App\Conn\Read;
use App\Helpers\Check;

$Read ??= new Read();
?>
<?php
require REQUIRE_PATH . '/inc/hero.php';
require REQUIRE_PATH . '/inc/prova.php';
?>

<?php if (APP_SLIDE) { ?>
<?php /* A arte dos fios em espiral vai por variável: o CSS do widget vive em
         _cdn/widgets/slide/ e não alcança as imagens do tema por caminho relativo. */ ?>
<div class="icc_campanhas" style="--slide-shape:url('<?= INCLUDE_PATH; ?>/images/shapes/shape-1.png')">
	<?php
    $SlideSeconts = 5;
    $SlidePriority = false;
    require '_cdn/widgets/slide/slide.wc.php';
    ?>
</div>
<?php } ?>

<?php
require REQUIRE_PATH . '/inc/linhas-comparativo.php';
require REQUIRE_PATH . '/inc/como-funciona.php';
?>

<section class="wc_videos" id="b">
	<div class="content">
		<header class="site_header">
			<h2>
				<b>Últimas novidades da</b> <?= SITE_NAME; ?>!
			</h2>
			<p>Artigos, infográficos, e-books, planilhas, muito conteúdo qualificado e gratuito para você!</p>
		</header>

		<div class="wc_videos_top">
			<?php
            $PostHeadingTag = 'h3';
            $Read->exeRead(
                DB_POSTS,
                'WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_date DESC LIMIT :limit OFFSET :offset',
                'offset=0&limit=1'
            );
            $Offsetpost = [];
            if ($Read->getResult()) {
                foreach ($Read->getResult() as $Bora) {
                    extract($Bora);
                    $Offsetpost[] = $post_id;
                    $BOX = 1;
                    require REQUIRE_PATH . '/inc/post.php';
                }
            } else {
                echo Check::erro(
                    "<div style='text-align: center; display: block; width: 100%;'>"
                    . 'Ainda não existem posts aqui! Volte mais tarde :)</div>',
                    E_USER_NOTICE
                );
            }
            ?>
		</div>
		<div class="wc_videos_more">
			<?php
            $Read->setPlaces('offset=1&limit=2');
            if ($Read->getResult()) {
                foreach ($Read->getResult() as $Bora) {
                    extract($Bora);
                    $Offsetpost[] = $post_id;
                    $BOX = 1;
                    require REQUIRE_PATH . '/inc/post.php';
                }
            } else {
                echo Check::erro(
                    "<div style='text-align: center; display: block; width: 100%;'>"
                    . 'Ainda não existem posts aqui! Volte mais tarde :)</div>',
                    E_USER_NOTICE
                );
            }
            ?>
		</div>
		<!--<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
		<div class="clear"></div>-->
	</div>
</section>
<section class="icc_parceiros">
	<div class="content">
		<?php
        require REQUIRE_PATH . '/inc/partners.inc.php';
        ?>
	</div>
</section>

<?php
require REQUIRE_PATH . '/inc/partner.php';
?>



<section class="wc_more" id="c">
	<div class="content">
		<header class="site_header">
			<h2>
				<b>Quer saber mais?</b> Então veja também!
			</h2>
			<p>O que separamos exclusivamente para você, aguardamos seus comentários!</p>
		</header>

		<?php
        $OffsetpostGet = (!empty($Offsetpost) ? implode(', ', $Offsetpost) : 0);
        $Read->exeRead(
            DB_POSTS,
            sprintf(
                'WHERE post_id NOT IN(%s) AND post_status = 1 AND post_date <= NOW() '
                . 'ORDER BY post_date DESC LIMIT :limit OFFSET :offset',
                $OffsetpostGet
            ),
            'offset=0&limit=4'
        );
        if ($Read->getResult()) {
            foreach ($Read->getResult() as $Bora) {
                extract($Bora);
                $BOX = 4;
                require REQUIRE_PATH . '/inc/post.php';
            }
        } else {
            echo Check::erro(
                "<div style='text-align: center; display: block; width: 100%;'>"
                . 'Ainda não existem posts aqui! Volte mais tarde :)</div>',
                E_USER_NOTICE
            );
        }
        ?>
		<div class="clear"></div>
	</div>

</section>
