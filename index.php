<?php

    ob_start();

// A sessão precisa existir ANTES do autoload: o ConfigLoader lê $_SESSION['WC_THEME']
// para definir a constante THEME. Mesma ordem do admin/dashboard.php.
    session_start();

    require_once __DIR__ . '/vendor/autoload.php';

    use App\Config\ConfigLoader;
    use App\Conn\Read;
    use App\Conn\Update;
    use App\Models\Seo;
    use App\Models\Session;

    ini_set('allow_url_fopen', 'On');
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

// TROCA DE TEMA VIA SESSÃO (?wctheme=<tema> para trocar, ?wctheme=null para voltar ao padrão).
// O redirect é necessário: a constante THEME já foi definida no boot do autoload.
// Só grava tema cuja pasta exista — o valor vai parar nos require abaixo, então um
// nome inválido derrubaria o site (ou serviria de path traversal).
    $WC_THEME = filter_input(INPUT_GET, 'wctheme', FILTER_DEFAULT);
    if ($WC_THEME) {
        if ('null' === $WC_THEME) {
            unset($_SESSION['WC_THEME']);
        } elseif (ConfigLoader::themeExists($WC_THEME)) {
            $_SESSION['WC_THEME'] = $WC_THEME;
        }

        header('Location: ' . BASE);

        exit;
    }

// Descarta tema órfão herdado de sessão antiga (tema renomeado/removido).
    if (isset($_SESSION['WC_THEME']) && !ConfigLoader::themeExists((string)$_SESSION['WC_THEME'])) {
        unset($_SESSION['WC_THEME']);
    }

// READ CLASS AUTO INSTANCE
    $Read ??= new Read();
    $Session = new Session(SIS_CACHE_TIME);

// USER SESSION VALIDATION
    if (!empty($_SESSION['userLogin']) && !empty($_SESSION['userLogin']['user_id'])) {
        $Read->exeRead(DB_USERS, 'WHERE user_id = :user_id', 'user_id=' . $_SESSION['userLogin']['user_id']);
        if ($Read->getResult()) {
            $_SESSION['userLogin'] = $Read->getResult()[0];
        } else {
            unset($_SESSION['userLogin']);
        }
    }

// GET PARAMETER URL
    $getURL = strip_tags(trim((string)filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
    $setURL = ('' === $getURL ? 'index' : $getURL);
    $URL = explode('/', $setURL);
    $SEO = new Seo($setURL);

// CHECK IF THIS POST IS ABLE TO AMP
    $IsArticle = isset($URL[0]) && 'artigo' === $URL[0];
    $HasAmpTemplate = file_exists(REQUIRE_PATH . '/amp.php');
    $PostAmp = false;

    if (APP_POSTS_AMP && $IsArticle && $HasAmpTemplate && isset($URL[1])) {
        $Read->exeRead(DB_POSTS, 'WHERE post_name = :name', 'name=' . $URL[1]);
        $PostAmp = (bool)($Read->getResult()[0]['post_amp'] ?? false);
    }

// INSTANCE AMP (valid single article only)
    if (
        APP_POSTS_AMP
        && $IsArticle
        && $HasAmpTemplate
        && $PostAmp
        && (isset($URL[2]) && 'amp' === $URL[2])
    ) {
        require REQUIRE_PATH . '/amp.php';
    } else {
        ?>
		<!DOCTYPE html>
		<html lang="pt-br" itemscope itemtype="https://schema.org/<?= $SEO->getSchema(); ?>">

		<head>
			<meta charset="UTF-8">
			<meta name="mit" content="2017-11-16T11:05:36-02:00+24186">
			<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
			<meta name="viewport" content="width=device-width,initial-scale=1">

			<title><?= $SEO->getTitle(); ?></title>
			<meta name="description" content="<?= $SEO->getDescription(); ?>"/>
			<meta name="robots" content="index, follow"/>

			<link rel="base" href="<?= BASE; ?>"/>
			<link rel="canonical" href="<?= BASE; ?>/<?= $getURL; ?>"/>
            <?php
                if (APP_POSTS_AMP && $IsArticle && $HasAmpTemplate && $PostAmp) {
                    echo '<link rel="amphtml" href="' . BASE . '/' . $getURL . '/amp" />' . "\r\n";
                }
            ?>
			<link rel="alternate" type="application/rss+xml" href="<?= BASE; ?>/rss.php"/>
			<link rel="sitemap" type="application/xml" href="<?= BASE; ?>/sitemap.xml"/>

			<meta itemprop="name" content="<?= $SEO->getTitle(); ?>"/>
			<meta itemprop="description" content="<?= $SEO->getDescription(); ?>"/>
			<meta itemprop="image" content="<?= $SEO->getImage(); ?>"/>
			<meta itemprop="url" content="<?= BASE; ?>/<?= $getURL; ?>"/>

			<meta property="og:type" content="article"/>
			<meta property="og:title" content="<?= $SEO->getTitle(); ?>"/>
			<meta property="og:description" content="<?= $SEO->getDescription(); ?>"/>
			<meta property="og:image" content="<?= $SEO->getImage(); ?>"/>
			<meta property="og:url" content="<?= BASE; ?>/<?= $getURL; ?>"/>
			<meta property="og:site_name" content="<?= SITE_NAME; ?>"/>
			<meta property="og:locale" content="pt_BR"/>
            <?php
                if (SITE_SOCIAL_FB) {
                    echo '<meta property="article:author" content="https://www.facebook.com/'
                        . SITE_SOCIAL_FB_AUTHOR . '" />' . "\r\n";
                    echo '<meta property="article:publisher" content="https://www.facebook.com/'
                        . SITE_SOCIAL_FB_PAGE . '" />' . "\r\n";

                    if (SITE_SOCIAL_FB_APP) {
                        echo '<meta property="og:app_id" content="' . SITE_SOCIAL_FB_APP . '" />' . "\r\n";
                    }

                    if (SEGMENT_FB_PAGE_ID) {
                        echo '<meta property="fb:pages" content="' . SEGMENT_FB_PAGE_ID . '" />' . "\r\n";
                    }
                }
            ?>

			<meta property="twitter:card" content="summary_large_image"/>
            <?php
                if (SITE_SOCIAL_TWITTER) {
                    echo '<meta property="twitter:site" content="@' . SITE_SOCIAL_TWITTER . '" />' . "\r\n";
                }
            ?>
			<meta property="twitter:domain" content="<?= BASE; ?>"/>
			<meta property="twitter:title" content="<?= $SEO->getTitle(); ?>"/>
			<meta property="twitter:description" content="<?= $SEO->getDescription(); ?>"/>
			<meta property="twitter:image" content="<?= $SEO->getImage(); ?>"/>
			<meta property="twitter:url" content="<?= BASE; ?>/<?= $getURL; ?>"/>

			<link rel="shortcut icon" href="<?= INCLUDE_PATH; ?>/images/favicon.png"/>
			<link href='https://fonts.googleapis.com/css?family=<?= SITE_FONT_NAME; ?>:<?= SITE_FONT_WHIGHT; ?>'
			      rel='stylesheet' type='text/css'>
			<style>* {
                    font-family: '<?= SITE_FONT_NAME; ?>', sans-serif;
                }</style>

			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap">

			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/bootcss/reset.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/bootcss/fonticon.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/shadowbox/shadowbox.min.css"/>
            <?php
                // ESTILO PRINCIPAL DO TEMA (as animações usadas do animate.css foram incorporadas ao style.css)

                if (file_exists(REQUIRE_PATH . '/css/icc-icons.min.css')) {
                    echo '<link rel="stylesheet" href="' . INCLUDE_PATH . '/css/icc-icons.min.css"/>' . "\r\n";
                }

                // folha única do tema (tokens + legado do WC + páginas); ?v= fura o cache a cada alteração
                if (file_exists(REQUIRE_PATH . '/css/style.css')) {
                    echo '<link rel="stylesheet" href="' . INCLUDE_PATH . '/css/style.css?v=' . filemtime(REQUIRE_PATH . '/css/style.css') . '"/>' . "\r\n";
                }

                // WC THEME CSS FILES (extras do tema em /wc_css)
                if (is_dir(REQUIRE_PATH . '/wc_css')) {
                    foreach (scandir(REQUIRE_PATH . '/wc_css') as $wcCssThemeFiles) {
                        $cssFile = REQUIRE_PATH . '/wc_css/' . $wcCssThemeFiles;
                        if (is_file($cssFile) && 'css' === pathinfo($cssFile, PATHINFO_EXTENSION)) {
                            echo '<link rel="stylesheet" href="' . INCLUDE_PATH . '/wc_css/' . $wcCssThemeFiles . '"/>' . "\r\n";
                        }
                    }
                }

                // GOOGLE ANALYTICS (gtag.js)
                if (SEGMENT_GL_ANALYTICS) {
                    echo "<script async src='https://www.googletagmanager.com/gtag/js?id="
                        . SEGMENT_GL_ANALYTICS . "'></script>";
                    echo "<script>
                window.dataLayer = window.dataLayer || [];
                function gtag() { dataLayer.push(arguments); }
                gtag('js', new Date());
                gtag('config', '" . SEGMENT_GL_ANALYTICS . "'); "
                        . (SEGMENT_GL_ADWORDS_ID ? "gtag('config', '" . SEGMENT_GL_ADWORDS_ID . "');" : '')
                        . '</script>';
                }

                // GOOGLE TAG MANAGER
                if (SEGMENT_GL_TAGMANAGER) {
                    echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':"
                        . " new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],"
                        . " j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= "
                        . "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })"
                        . "(window,document,'script','dataLayer','" . SEGMENT_GL_TAGMANAGER . "');</script>";
                }
            ?>

            <?php
                if (SEGMENT_FB_PIXEL_ID) { ?>
					<!-- Facebook Pixel Code -->
					<script>
                        !function (f, b, e, v, n, t, s) {
                            if (f.fbq) return;
                            n = f.fbq = function () {
                                n.callMethod ?
                                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                            };
                            if (!f._fbq) f._fbq = n;
                            n.push = n;
                            n.loaded = !0;
                            n.version = '2.0';
                            n.queue = [];
                            t = b.createElement(e);
                            t.async = !0;
                            t.src = v;
                            s = b.getElementsByTagName(e)[0];
                            s.parentNode.insertBefore(t, s)
                        }(window, document, 'script',
                            'https://connect.facebook.net/en_US/fbevents.js');
                        fbq('init', '<?= SEGMENT_FB_PIXEL_ID; ?>');
                        fbq('track', 'PageView');
					</script>
					<noscript>
						<img height="1" width="1" style="display:none"
						     src="https://www.facebook.com/tr?id=<?= SEGMENT_FB_PIXEL_ID; ?>&ev=PageView&noscript=1"/>
					</noscript>
					<!-- End Facebook Pixel Code -->
                <?php
                } ?>
		</head>

		<body>
        <?php
            // GOOGLE TAG MANAGER (noscript)
            if (SEGMENT_GL_TAGMANAGER) {
                echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='
                    . SEGMENT_GL_TAGMANAGER
                    . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
            }
        ?>

		<div class="trigger_modal"></div>

		<div id="fb-root"></div>
		<script async defer crossorigin="anonymous"
		        src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v8.0"></script>

        <?php
            // MESSAGE MAINTENANCE FOR ADMIN
            if (
                ADMIN_MAINTENANCE
                && !empty($_SESSION['userLogin']['user_level'])
                && $_SESSION['userLogin']['user_level'] >= 6
            ) {
                echo "<div class='workcontrol_maintenance'>&#x267A; O MODO de manutenção está ativo. "
                    . 'Somente administradores podem ver o site assim &#x267A;</div>';
            }

            // REDIRECT PUBLIC TO MAINTENANCE
            if (
                ADMIN_MAINTENANCE
                && (empty($_SESSION['userLogin']['user_level'])
                    || $_SESSION['userLogin']['user_level'] < 6)
            ) {
                require __DIR__ . '/maintenance.php';
            } else {
                // PESQUISA
                $Search = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if ($Search && !empty($Search['s'])) {
                    $Search = urlencode(strip_tags(trim($Search['s'])));
                    header('Location: ' . BASE . '/pesquisa/' . $Search);

                    exit;
                }

                // LANDING PAGES
                $Customers = [];
                $Read->fullRead('SELECT page_name FROM ' . DB_LANDING_PAGES);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $SinglePage) {
                        $Customers[] = $SinglePage['page_name'];
                    }
                }

                // THANKYOU PAGES
                $Tps = [];
                $Read->fullRead('SELECT page_name FROM ' . DB_THANKYOU_PAGES);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $SinglePage) {
                        $Tps[] = $SinglePage['page_name'];
                    }
                }

                // LINKTREE
                $Cards = [];
                if (APP_LINKTREE) {
                    $Read->fullRead('SELECT carduser_url FROM ' . DB_CARD_USER);
                    if ($Read->getResult()) {
                        foreach ($Read->getResult() as $SinglePage) {
                            $Cards[] = $SinglePage['carduser_url'];
                        }
                    }
                }

                if (in_array($URL[0], $Tps) && file_exists(REQUIRE_PATH . '/thankyou-page.php')) {
                    if (file_exists(REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]))) {
                        require REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]);
                    } else {
                        require REQUIRE_PATH . '/thankyou-page.php';
                    }
                } elseif (in_array($URL[0], $Customers) && file_exists(REQUIRE_PATH . '/landing-page.php')) {
                    if (file_exists(REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]))) {
                        require REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]);
                    } else {
                        require REQUIRE_PATH . '/landing-page.php';
                    }
                } elseif (in_array($URL[0], $Cards) && file_exists(REQUIRE_PATH . '/cartao-de-contato.php')) {
                    if (file_exists(REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]))) {
                        require REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]);
                    } else {
                        require REQUIRE_PATH . '/cartao-de-contato.php';
                    }
                } else {
                    // HEADER
                    if (file_exists(REQUIRE_PATH . '/inc/header.php')) {
                        require REQUIRE_PATH . '/inc/header.php';
                    } else {
                        trigger_error('Crie um arquivo /inc/header.php na pasta do tema!');
                    }

                    // CONTENT
                    echo "<main id='conteudo' class='main-wrapper oh' tabindex='-1'>";
                    $URL[1] = (empty($URL[1]) ? null : $URL[1]);

                    if ('rss' === $URL[0] || 'feed' === $URL[0] || 'rss.xml' === $URL[0]) {
                        header('Location: ' . BASE . '/rss.php');

                        exit;
                    }

                    $Pages = [];
                    $Read->fullRead('SELECT page_name FROM ' . DB_PAGES);
                    if ($Read->getResult()) {
                        foreach ($Read->getResult() as $SinglePage) {
                            $Pages[] = $SinglePage['page_name'];
                        }
                    }

                    if (in_array($URL[0], $Pages) && file_exists(REQUIRE_PATH . '/pagina.php')) {
                        if (file_exists(REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]))) {
                            require REQUIRE_PATH . sprintf('/page-%s.php', $URL[0]);
                        } else {
                            require REQUIRE_PATH . '/pagina.php';
                        }
                    } elseif (file_exists(REQUIRE_PATH . '/' . $URL[0] . '.php')) {
                        if ('artigos' === $URL[0] && file_exists(REQUIRE_PATH . sprintf('/cat-%s.php', $URL[1]))) {
                            require REQUIRE_PATH . sprintf('/cat-%s.php', $URL[1]);
                        } else {
                            require REQUIRE_PATH . '/' . $URL[0] . '.php';
                        }
                    } elseif (null !== $URL[1] && file_exists(REQUIRE_PATH . '/' . $URL[0] . '/' . $URL[1] . '.php')) {
                        require REQUIRE_PATH . '/' . $URL[0] . '/' . $URL[1] . '.php';
                    } elseif (file_exists(REQUIRE_PATH . '/404.php')) {
                        require REQUIRE_PATH . '/404.php';
                    } else {
                        trigger_error(
                            'Não foi possível incluir o arquivo themes/'
                            . THEME . sprintf('/%s.php <b>(O arquivo 404 também não existe!)</b>', $getURL)
                        );
                    }

                    echo '</main>';

                    // FOOTER
                    if (file_exists(REQUIRE_PATH . '/inc/footer.php')) {
                        require REQUIRE_PATH . '/inc/footer.php';
                    } else {
                        trigger_error('Crie um arquivo /inc/footer.php na pasta do tema!');
                    }
                }
            }
        ?>

		<!--SCRIPTS-->
		<script src="<?= BASE; ?>/_cdn/jquery.min.js"></script>
		<script src="<?= BASE; ?>/_cdn/workcontrol.min.js"></script>
		<script src="<?= BASE; ?>/_cdn/shadowbox/shadowbox.min.js"></script>

        <?php
            // MAIN SCRIPT THEME
            if (file_exists(REQUIRE_PATH . '/js/scripts.min.js')) {
                echo '<script src="' . INCLUDE_PATH . '/js/scripts.min.js?v=' . filemtime(REQUIRE_PATH . '/js/scripts.min.js') . '"></script>' . "\r\n";
            }

            // WC THEME JS FILES (extras do tema em /wc_js)
            if (is_dir(REQUIRE_PATH . '/wc_js')) {
                foreach (scandir(REQUIRE_PATH . '/wc_js') as $wcJsThemeFiles) {
                    $jsFile = REQUIRE_PATH . '/wc_js/' . $wcJsThemeFiles;
                    if (is_file($jsFile) && 'js' === pathinfo($jsFile, PATHINFO_EXTENSION)) {
                        echo '<script src="' . INCLUDE_PATH . '/wc_js/' . $wcJsThemeFiles . '"></script>' . "\r\n";
                    }
                }
            }

            // WC CODES
            $Read->exeRead(DB_WC_CODE);
            if ($Read->getResult()) {
                $Update ??= new Update();

                $ActiveCodes = (string)filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
                echo "\r\n\r\n\r\n<!--WorkControl Codes-->\r\n";
                foreach ($Read->getResult() as $HomeCodes) {
                    $ShouldRender = empty($HomeCodes['code_condition'])
                        || preg_match(
                            '/' . str_replace('/', '\/', $HomeCodes['code_condition']) . '/',
                            $ActiveCodes
                        );

                    if ($ShouldRender) {
                        echo $HomeCodes['code_script'];
                        $UpdateCodes = ['code_views' => $HomeCodes['code_views'] + 1];
                        $Update->exeUpdate(
                            DB_WC_CODE,
                            $UpdateCodes,
                            'WHERE code_id = :id',
                            'id=' . $HomeCodes['code_id']
                        );
                    }
                }

                echo "\r\n<!--/WorkControl Codes-->\r\n\r\n\r\n";
            }

            // WIDGET DE ACESSIBILIDADE
            require __DIR__ . '/_cdn/widgets/accessibility/accessibility.inc.php';
        ?>

		</body>

		</html>
        <?php
    }

    ob_end_flush();

    if (!file_exists('.htaccess')) {
        $htaccesswrite = "RewriteEngine On\r\nOptions All -Indexes\r\n\r\n"
            . "# WC WWW Redirect.\r\n#RewriteCond %{HTTP_HOST} !^www\\. [NC]\r\n"
            . "#RewriteRule ^ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\r\n\r\n"
            . "# WC HTTPS Redirect\r\nRewriteCond %{HTTP:X-Forwarded-Proto} !https\r\n"
            . "RewriteCond %{HTTPS} off\r\nRewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\r\n\r\n"
            . "# WC URL Rewrite\r\nRewriteCond %{SCRIPT_FILENAME} !-f\r\n"
            . "RewriteCond %{SCRIPT_FILENAME} !-d\r\nRewriteRule ^(.*)$ index.php?url=$1";
        $htaccess = fopen('.htaccess', 'w');
        fwrite($htaccess, str_replace("'", '"', $htaccesswrite));
        fclose($htaccess);
    }
