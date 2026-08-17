<header class="main_header" id="h">
	<div class="content">
		<h1 class="site_title"><?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?></h1>
		<a href="<?= BASE; ?>" title="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>">
			<img class="main_logo" src="<?= INCLUDE_PATH; ?>/images/iccserra-logo-h.png"
			     alt="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>" title="<?= SITE_NAME; ?> - <?= SITE_SUBNAME; ?>"/>
		</a>

		<div class="mobile_menu">&#9776;</div>

		<nav class="main_nav">
			<h1 class="site_title"><strong>Microcrédito </strong> - Crédito para Boas Ideias!</h1>
			<?php
				require REQUIRE_PATH . '/inc/menu.php';
				require REQUIRE_PATH . '/inc/social.php';
			?>

			<div class="navbar-search">
				<div class="container">
					<form class="search_form" name="searchTop" action="" method="post" enctype="multipart/form-data">
						<div class="input-group">
							<input type="text" id="search-input" autocomplete="off" class="form-control" tabindex="1"
							       autofocus name="s" placeholder="Digite uma palavra e após tecle ENTER" required/>
							<!--          <span class="input-group-btn">
										<button type="reset" class="btn search-close" id="search-close" >
										  <i>X</i>
										</button>
									  </span>-->
						</div>
					</form>
				</div>
			</div>
		</nav>
		<div class="clear"></div>
	</div>
</header>
