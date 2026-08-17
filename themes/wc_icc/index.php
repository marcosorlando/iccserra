<?php

use App\Conn\Read;
use App\Helpers\Check;

$Read ??= new Read();
?>
<article class="top_conversion">
	<?php
    if (APP_SLIDE) {
        $SlideSeconts = 5;
        require '_cdn/widgets/slide/slide.wc.php';
    }
    ?>
	<img class="credito-boas-ideias" src="<?= INCLUDE_PATH; ?>/images/credito-para-boas-ideias.png"
	     alt="Microcrédito, crédito para boas ideias!" title="Microcrédito, crédito para boas ideias!"/>
	<div class="clear"></div>
</article>

<article id="footeroptin">
	<div class="content">
		<div class=" box box2">
			<div>
				<h2 style="display: inline-block; color: #E8E8E8; ">Acesse nosso
					<b>simulador,</b><span> e realize uma simulação de crédito agora mesmo!</span>
				</h2>
			</div>
		</div>
		<div class="content_right box box2">
			<p style="text-align: center !important; margin-bottom: 2em; ">
				<br>
				<a class="cta cta-red _submit" href="<?= BASE; ?>/simulador"
				   title="Simulador de Crédito - ICC SERRA - Banco do Povo">SIMULAR AGORA</a>
			</p>
		</div>
	</div>
	<div class="clear"></div>
</article>

<section class="wc_line">
	<h2>DESCUBRA A MODALIDADE DE CRÉDITO IDEAL PARA VOCÊ</h2>
	<?php
    require REQUIRE_PATH . '/inc/linha_microcredito_produtivo.php';
    require REQUIRE_PATH . '/inc/linha_microcredito_mulher.php';
    require REQUIRE_PATH . '/inc/linha_reforma.php';
    require REQUIRE_PATH . '/inc/linha_agricola.php';
    ?>
</section>

<section class="wc_videos" id="b">
	<div class="content">
		<header class="site_header">
			<h1>
				<b>Últimas novidades da</b> <?= SITE_NAME; ?>!
			</h1>
			<p>Artigos, infográficos, e-books, planilhas, muito conteúdo qualificado e gratuito para você!</p>
		</header>

		<div class="wc_videos_top">
			<?php
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
<section class="wc_bio" id="a">
	<div class="content">
		<header class="site_header">
			<h1>Quais são as principais linhas de
				<b>crédito?</b>
			</h1>
			<p>Entenda quais as diferenças entre as principais modalidades de Crédito para microempreendedores.</p>
		</header>

		<div class="bio_logo">
			<img title="<?= SITE_NAME; ?> Sobre Microcrédito ICC Serra"
			     alt="<?= SITE_NAME; ?> Sobre Microcrédito ICC Serra"
			     src="<?= INCLUDE_PATH; ?>/images/iccserra-logo-box.png"/>
		</div>
		<div class="bio_content">
			<h3>
				<a href="<?= BASE; ?>/artigo/microcredito-produtivo"
				   title="Para conhecer em detalhes o Microcrédito Produtivo. Clique!">MICROCRÉDITO PRODUTIVO:</a>
			</h3>
			<p>O Microcrédito Produtivo é ideal para quem já é dono do próprio negócio, mesmo na informalidade. É um
				crédito para investir na sua atividade e existe para incentivar a geração de trabalho e renda de
				microempreendedores populares. </p>

			<h3>
				<a href="<?= BASE; ?>/artigo/credito-para-reforma-ou-ampliacao"
				   title="Para saber mais sobre o Crédito para Reforma. Clique!">REFORMA & AMPLIACÃO:</a>
			</h3>
			<p>Com esta linha de crédito a ICC SERRA - Banco do Povo ajuda você e sua família a reformar ou ampliar sua
				moradia ou estabelecimento comercial. Tire a ideia do papel e faça o seu sonho acontecer. </p>

			<h3>
				<a href="<?= BASE; ?>/artigo/credito-agricola"
				   title="Para saber mais sobre o Crédito Agricola. Clique!">CRÉDITO AGRICOLA:</a>
			</h3>
			<p>O Crédito Agricola é a solução perfeita para empreendedores rurais de desejam adquirir/consertar
				equipamentos ou suprimentos para ampliar sua capacidade produtiva. </p>


			<h3>
				<a href="<?= BASE; ?>/artigo/troca-de-cheques" title="Para saber mais sobre Troca de Cheques. Clique!">TROCA
					DE CHEQUE:</a>
			</h3>
			<p>A troca de cheques é a solução exata para quem necessita converter os cheques pré datados em dinheiro
				vivo. </p>

		</div>

		<?php
        require REQUIRE_PATH . '/inc/partners.inc.php';
        ?>
		<div class="clear"></div>
	</div>
</section>

<?php
require REQUIRE_PATH . '/inc/partner.php';
?>

<article class="wc_social">
	<div class="content">
		<header class="site_header">
			<h1>Siga o
				<b><?= SITE_SOCIAL_NAME; ?></b> no Facebook
			</h1>
			<p>Acompanhe as publicações periódicas do <b>ICC SERRA</b> nas mídias sociais</p>
		</header>
		<div class="fb-like" data-href="https://www.facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>" data-width=""
		     data-layout="standard" data-action="like" data-size="large"
		     style="z-index: 9; max-width: 100%; overflow: hidden;" data-share="true"></div>
		<div class="lead_social_face"></div>
		<div class="clear"></div>
	</div>
</article>

<?php
require REQUIRE_PATH . '/inc/activeform.inc.php';
?>


<section class="wc_more" id="c">
	<div class="content">
		<header class="site_header">
			<h1>
				<b>Quer saber mais?</b> Então veja também!
			</h1>
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
            'offset=0&limit=8'
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
