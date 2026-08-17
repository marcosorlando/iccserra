   <?php

   use App\Conn\Read;

   $Read ??= new Read();
   $Read->exeRead(DB_PARTNERS, 'ORDER BY partner_name');
	if ($Read->getResult()) {
		?>
		<aside class="partners">
			<header class="site_header">
				<h1>Alguns de nossos <b>Parceiros</b></h1>
				<p>Transformando vidas por meio de microcrédito produtivo, capacitação e orientação.</p>
			</header>
			<div class="clients-area">
				<ul>
					<?php
						foreach ($Read->getResult() as $Partners) {
							extract($Partners);

							echo "<li><a href='{$partner_page}' target='_blank'><img src='" . BASE
								. "/tim.php?src=uploads/{$partner_image}&w=200&h=100' title='{$partner_name}'"
								. " alt='Logotipo | {$partner_name}'/></a></li>";
						}
					?>
				</ul>
			</div>
		</aside>
		<?php
	} ?>
