<?php

use App\Conn\Read;
use App\Helpers\Check;

$Read ??= new Read();
?>
<footer class="main_footer">
	<article id="footeroptin">
		<div class="content">
			<div class="content_left box box2">
				<img
						src="<?= INCLUDE_PATH; ?>/images/ebook-metas.jpg"
						alt="Metas - O segredo das pessoas bem sucedidas"
						title="Metas - O segredo das pessoas bem sucedidas"
				/>
				<div>
					<h1>E-BOOK <b>METAS</b></h1>
					<p> Preparamos um excelente e-book com <b>CONTEÚDO PREMIUM</b> deguste ele foi feito carinhosamente
						para você!</p>
				</div>
			</div>
			<div class="content_right box box2">
				<p>O SEGREDO DAS PESSOAS<b> BEM SUCEDIDAS</b></p><br>
				<a href="https://iccserra.org.br/metas-o-segredo-das-pessoas-bem-sucedidas" title="Clique para baixar!"
				   style="display: block; text-align: center;" target="_blank" class="cta cta-red">DOWNLOAD GRATUITO</a>
			</div>
		</div>
		<div class="clear"></div>
	</article>

	<section class="box address" id="me" itemscope itemtype="http://microformats.org/profile/hcard">
		<div class="content">
			<header class="site_header">
				<h1><b>Postos de Atendimento</b> <span itemprop="nickname">BANCO DO POVO</span><br/>
					<a rel="me" itemprop="url fn" href="<?= BASE; ?>">Instituição Comunitária de Crédito da Serra - ICC
						Serra</a>
				</h1>
				<span>Pessoa jurídica de direito privado, constituída sob forma de Organização da Sociedade Civil e de Interesse Público - OSCIP, CNPJ: 05.036/783/0002-68</span>
				<div itemscope itemprop="adr">MATRIZ:
					<span class="icon-location" itemprop="street‐address"><?= SITE_ADDR_ADDR; ?></span>, CEP:
					<span itemprop="postal‐code"><?= SITE_ADDR_ZIP; ?></span> /
					<span itemprop="locality"><strong>Caxias do Sul</strong></span> -
					<span itemprop="region">RS | </span>
					<span itemprop="value" class="icon-phone"><a href="tel:5432178787"
					                                             title="Clique para LIGAR"><strong>+55 54 3217 8787</strong></a></span>
				</div>

				<h6 style="font-size: 0.97em; text-align: center; margin: 10px 0 20px 0;">
					<span class="text_color_red">HORÁRIO DE ATENDIMENTO:</span> DE SEGUNDA À SEXTA-FEIRA DAS 9:00H AS
					17:00H</h6>

				<a class="btn_cta_red icon-map" rel="shadowbox" href="<?= BASE; ?>/mapa.html" title="Como chegar?">Clique
					para ver o mapa!</a>
			</header>

			<ul>

                <?php

                    // TODO: DB_SERVICES_STATIONS não é definida pelo ConfigLoader (postos de
                    // atendimento). Registrar a constante em defineDatabaseTableConstants() para
                    // reativar a listagem. Sem a guarda o footer derruba o site inteiro.
                    if (defined('DB_SERVICES_STATIONS')) {
                        $Read->exeRead(DB_SERVICES_STATIONS, 'WHERE status = :st ORDER BY id', 'st=1');
                    }

                    if (defined('DB_SERVICES_STATIONS') && $Read->getResult()) {
                        foreach ($Read->getResult() as $posto) {
                            echo "
                            <li class='box box4'>
					<h3>{$posto['ss_name']}</h3>
					<div itemscope itemprop='adr'>
						<span itemprop='locality'>{$posto['ss_city']}</span> - 
						<span itemprop='region'>{$posto['ss_state']}</span>
					</div>
					
					<div itemprop='email' class='ss-email icon-mail2'>
	                    <a href='mailto:{$posto['ss_email']}' title='Para enviar um e-mail agora. Clique!'>{$posto['ss_email']}</a>
                    </div><br/>
					
					<div class='ss-phone' itemscope>
						<span itemprop='type' type='tel, work' style='display:none'>work</span>
						<span itemprop='value' class='icon-phone'>
							<a href='tel:" . Check::clearNumber($posto['ss_phone']) . "' title='Clique para LIGAR'><b>{$posto['ss_phone']}</b></a>
						</span>
					</div>
				</li>
                            
                            
                            ";
                        }
                    }

                ?>

			</ul>
		</div>
	</section>
	<div class="clear"></div>
	<div class="content">
		<nav>
			<h1 class="site_title"><?= SITE_SUBNAME; ?></h1>
            <?php
                if (empty($URL[1]) || 'index' === $URL[0]) {
                    require REQUIRE_PATH . '/inc/menufooter.php';
                } else {
                    require REQUIRE_PATH . '/inc/menulinkfooter.php';
                }
            ?>
		</nav>
		<section class="box box3">
			<h1 class="section_title">Últimas <b>do site!</b></h1>
            <?php
                $Read->exeRead(
                    DB_POSTS,
                    'WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_date DESC LIMIT :limit OFFSET :offset',
                    'offset=0&limit=4'
                );
                foreach (($Read->getResult() ?? []) as $Bora) {
                    extract($Bora);
                    echo "<article><h1><a href='" . BASE
                        . "/artigo/{$post_name}' title='{$post_title}'>{$post_title}</a></h1></article>";
                }
            ?>
		</section>
		<section class="box box3">
			<h1 class="section_title">Mais <b>acessados!</b></h1>
            <?php
                $Read->exeRead(
                    DB_POSTS,
                    'WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_views DESC LIMIT :limit OFFSET :offset',
                    'offset=0&limit=4'
                );
                foreach (($Read->getResult() ?? []) as $Bora) {
                    extract($Bora);
                    echo "<article><h1><a href='" . BASE
                        . "/artigo/{$post_name}' title='{$post_title}'>{$post_title}</a></h1></article>";
                }
            ?>
		</section>
		<section class="box box3 conect">
			<h1 class="section_title">Conecte-se <b>conosco</b></h1>
            <?php
                require REQUIRE_PATH . '/inc/social.php'; ?>
		</section>

		<div class="clear"></div>
	</div>
	<div class="wc_privacity">
		<div class="content">
            <?php
                require __DIR__ . "/docs.php" ?>

			<div class="left">
				<a href="<?= BASE; ?>" title="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>">
					<img class="main_logo" src="<?= INCLUDE_PATH; ?>/images/iccserra-logo-h2.png"
					     alt="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>"
					     title="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>"/>

				</a>
			</div>
			<div class="right">
				<ul>
					<li><a href="<?= BASE; ?>/termos-de-uso">Termos de Uso</a></li>
					<li><a href="<?= BASE; ?>/politicas-de-privacidade">Política de Privacidade</a></li>
					<li><a href="<?= BASE; ?>/aviso-legal">Aviso Legal</a></li>
				</ul>
			</div>
			<div class="box copy">
				<div class="box box2">Copyright © <?= date('Y'); ?> - <?= SITE_NAME; ?> - OSCIP, CNPJ:
                    <?= SITE_ADDR_CNPJ; ?> - Todos os direitos reservados.
				</div>
				<div class="box box2"><a class="zen" href="https://zen.ppg.br"
				                         title="Zen Agência Web - Marketing Digital" target="_blank"></a></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</footer>
