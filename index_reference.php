<?php
ob_start();
session_start();

require './_app/Config.inc.php';

//CHANCE THEME IN SESSION
$WC_THEME = filter_input(INPUT_GET, "wctheme", FILTER_DEFAULT);
if ($WC_THEME && $WC_THEME != 'null') {
    $_SESSION['WC_THEME'] = $WC_THEME;
    header("Location: " . BASE);
    exit;
} elseif ($WC_THEME && $WC_THEME == 'null') {
    unset($_SESSION['WC_THEME']);
    header("Location: " . BASE);
    exit;
}

//READ CLASS AUTO INSTANCE
if (empty($Read)) {
    $Read = new Read;
}

$Sesssion = new Session(SIS_CACHE_TIME);

//USER SESSION VALIDATION
if (!empty($_SESSION['userLogin']) && !empty($_SESSION['userLogin']['user_id'])) {
    if (empty($Read)) {
        $Read = new Read;
    }
    $Read->ExeRead(DB_USERS, "WHERE user_id = :user_id", "user_id={$_SESSION['userLogin']['user_id']}");
    if ($Read->getResult()) {
        $_SESSION['userLogin'] = $Read->getResult()[0];
    } else {
        unset($_SESSION['userLogin']);
    }
}

//GET PARAMETER URL
$getURL = strip_tags(trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
$setURL = (empty($getURL) ? 'index' : $getURL);
$URL = explode('/', $setURL);
$SEO = new Seo($setURL);

//CHECK IF THIS POST ABLE TO AMP
if (APP_POSTS_AMP && (!empty($URL[0]) && $URL[0] == 'artigo') && file_exists(REQUIRE_PATH . '/amp.php')) {
    $Read->ExeRead(DB_POSTS, "WHERE post_name = :name", "name={$URL[1]}");
    $PostAmp = ($Read->getResult()[0]['post_amp'] == 1 ? true : false);
}

//INSTANCE AMP (valid single article only)
if (APP_POSTS_AMP && (!empty($URL[0]) && $URL[0] == 'artigo') && file_exists(
    REQUIRE_PATH . '/amp.php'
) && (!empty($URL[2]) && $URL[2] == 'amp') && (!empty($PostAmp) && $PostAmp == true)){
    require REQUIRE_PATH . '/amp.php';
}else{
?><!DOCTYPE html>
<html lang="pt-br" itemscope itemtype="https://schema.org/<?= $SEO->getSchema(); ?>">
<head>
	<meta charset="UTF-8">
	<meta name="mit" content="2017-07-25T15:12:29-03:00+24186">
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<title><?= $SEO->getTitle(); ?></title>
	<meta name="description" content="<?= $SEO->getDescription(); ?>"/>
	<meta name="robots" content="index, follow"/>

	<link rel="base" href="<?= BASE; ?>"/>
	<link rel="canonical" href="<?= BASE; ?>/<?= $getURL; ?>"/>
    <?php
        if (
            APP_POSTS_AMP && (!empty($URL[0]) && $URL[0] == 'artigo') && file_exists(
                REQUIRE_PATH . '/amp.php'
            ) && (!empty($PostAmp) && $PostAmp == true)
        ):
            echo '<link rel="amphtml" href="' . BASE . '/' . $getURL . '/amp" />' . "\r\n";
        endif;
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
        if (SITE_SOCIAL_FB):
            echo '<meta property="article:author" content="https://www.facebook.com/' . SITE_SOCIAL_FB_AUTHOR . '" />' . "\r\n";
            echo '<meta property="article:publisher" content="https://www.facebook.com/' . SITE_SOCIAL_FB_PAGE . '" />' . "\r\n";

            if (SITE_SOCIAL_FB_APP):
                echo '<meta property="og:app_id" content="' . SITE_SOCIAL_FB_APP . '" />' . "\r\n";
            endif;

            if (SEGMENT_FB_PAGE_ID):
                echo '<meta property="fb:pages" content="' . SEGMENT_FB_PAGE_ID . '" />' . "\r\n";
            endif;
        endif;
    ?>

	<meta property="twitter:card" content="summary_large_image"/>
    <?php
        if (SITE_SOCIAL_TWITTER):
            echo '<meta property="twitter:site" content="@' . SITE_SOCIAL_TWITTER . '" />' . "\r\n";
        endif;
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


	<link rel="stylesheet" href="<?= BASE; ?>/_cdn/bootcss/reset.min.css"/>
	<link rel='stylesheet' href='<?= BASE; ?>/_cdn/bootcss/fonticon.min.css'/>
	<link rel='stylesheet' href='<?= INCLUDE_PATH; ?>/css/animate.min.css'/>

	<script src='<?= BASE; ?>/_cdn/jquery.min.js'></script>
    <?php
        // MAIN STYLE THEME

        if (file_exists('themes/' . THEME . '/css/style.css')):
            echo '<link rel="stylesheet" href="' . INCLUDE_PATH . '/css/style.min.css"/>' . "\r\n";
        endif;

        if (APP_EAD):
            echo '<link rel="stylesheet" href="' . BASE . '/_ead/wc_ead.css"/>' . "\r\n";
        endif;

        //WC THEME CSS FILES
        if (file_exists("themes/" . THEME . "/wc_css")):
            foreach (scandir("themes/" . THEME . "/wc_css") as $wcCssThemeFiles) :
                if (
                    file_exists("themes/" . THEME . "/wc_css/{$wcCssThemeFiles}") && !is_dir(
                        "themes/" . THEME . "/wc_css/{$wcCssThemeFiles}"
                    ) && pathinfo("themes/" . THEME . "/wc_css/{$wcCssThemeFiles}")['extension'] == 'css'
                ):
                    echo '<link rel="stylesheet" href="' . INCLUDE_PATH . '/wc_css/' . $wcCssThemeFiles . '"/>';
                endif;
            endforeach;
        endif;
    ?>

    <?php

        if (!empty(SEGMENT_FB_PIXEL_ID)) {
            require '_cdn/wc_track.php';
        }
        /**
         *GOOGLE ANALYTICS WITH DEFINE IN CONFIG :  Google tag (gtag.js)
         */
        if (!empty(SEGMENT_GL_ANALYTICS_GA4)) {
            echo " <script async src='https://www.googletagmanager.com/gtag/js?id=" . SEGMENT_GL_ANALYTICS_GA4 . "'></script>
                <script> 
                    window.dataLayer = window.dataLayer || []; 
					function gtag(){dataLayer.push(arguments);}
					
					gtag('js', new Date()); gtag('config', '" . SEGMENT_GL_ANALYTICS_GA4 . "');
				</script> ";
        }
        /**
         *GOOGLE TAG MANAGER - WITH DEFINE IN CONFIG
         */
        if (!empty(SEGMENT_GL_ANALYTICS_GTM)) {
            echo "<script>
					(function (w, d, s, l, i) {
				         w[l] = w[l] || [];
				         w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
				         var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
				         j.async = true;
				         j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				         f.parentNode.insertBefore(j, f);
			        })(window, document, 'script', 'dataLayer', '" . SEGMENT_GL_ANALYTICS_GTM . "');
		          </script>";
        }
    ?>
</head>
<body>
<?php
    /**
     * Google Tag Manager (noscript)
     * */
    if (!empty(SEGMENT_GL_ANALYTICS_GTM)) {
        echo "<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=" . SEGMENT_GL_ANALYTICS_GTM . "' height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>";
    }
?>

<!--<div class="callback_return trigger_modal"></div>-->
<div class="trigger_modal"></div>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v8.0&appId=109826913017941"
        nonce="B17UBelM"></script>

<?php
    // MESSAGE MAINTENANCE FOR ADMIN
    if (ADMIN_MAINTENANCE && !empty($_SESSION['userLogin']['user_level']) && $_SESSION['userLogin']['user_level'] >= 6) {
        echo "<div class='workcontrol_maintenance'>&#x267A; O MODO de manutenção está ativo. Somente administradores podem ver o site assim &#x267A;</div>";
    }

    // REDIRECT PUBLIC TO MAINTENANCE
    if (ADMIN_MAINTENANCE && (empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < 6)){
    require 'maintenance.php';
}else{
    // PESQUISA
    $Search = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if ($Search && !empty($Search['s'])) {
        $Search = urlencode(strip_tags(trim($Search['s'])));
        header('Location: ' . BASE . '/pesquisa/' . $Search);
        exit;
    }

    //LANDING_PAGES MODULE
    //LP
    $Customers = [];
    $Read->FullRead("SELECT page_name FROM " . DB_LANDING_PAGES);
    if ($Read->getResult()) {
        foreach ($Read->getResult() as $SinglePage) {
            $Customers[] = $SinglePage['page_name'];
        }
    }

    //TP
    $Tps = [];
    $Read->FullRead("SELECT page_name FROM " . DB_THANKYOU_PAGES);
    if ($Read->getResult()) {
        foreach ($Read->getResult() as $SinglePage) {
            $Tps[] = $SinglePage['page_name'];
        }
    }

    if (in_array($URL[0], $Tps) && file_exists(REQUIRE_PATH . '/thankyou-page.php')) {
        if (file_exists(REQUIRE_PATH . "/page-{$URL[0]}.php")) {
            require REQUIRE_PATH . "/page-{$URL[0]}.php";
        } else {
            require REQUIRE_PATH . '/thankyou-page.php';
        }
    } elseif (in_array($URL[0], $Customers) && file_exists(REQUIRE_PATH . '/landing-page.php')) {
        if (file_exists(REQUIRE_PATH . "/page-{$URL[0]}.php")) {
            require REQUIRE_PATH . "/page-{$URL[0]}.php";
        } else {
            require REQUIRE_PATH . '/landing-page.php';
        }
    } else {
        //END LANDING_PAGES MODULE

        // HEADER
        if (file_exists(REQUIRE_PATH . "/inc/header.php")) {
            require REQUIRE_PATH . "/inc/header.php";
        } else {
            trigger_error('Crie um arquivo /inc/header.php na pasta do tema!');
        }

        // CONTENT
        $URL[1] = (empty($URL[1]) ? null : $URL[1]);

        if ($URL[0] == 'rss' || $URL[0] == 'feed' || $URL[0] == 'rss.xml') {
            header("Location: " . BASE . "/rss.php");
            exit;
        }

        $Pages = [];
        $Read->FullRead("SELECT page_name FROM " . DB_PAGES);
        if ($Read->getResult()) {
            foreach ($Read->getResult() as $SinglePage) {
                $Pages[] = $SinglePage['page_name'];
            }
        }

        if (in_array($URL[0], $Pages) && file_exists(REQUIRE_PATH . '/pagina.php')) {
            if (file_exists(REQUIRE_PATH . "/page-{$URL[0]}.php")) {
                require REQUIRE_PATH . "/page-{$URL[0]}.php";
            } else {
                require REQUIRE_PATH . '/pagina.php';
            }
        } elseif (file_exists(REQUIRE_PATH . '/' . $URL[0] . '.php')) {
            if ($URL[0] == 'artigos' && file_exists(REQUIRE_PATH . "/cat-{$URL[1]}.php")) {
                require REQUIRE_PATH . "/cat-{$URL[1]}.php";
            } else {
                require REQUIRE_PATH . '/' . $URL[0] . '.php';
            }
        } elseif (file_exists(REQUIRE_PATH . '/' . $URL[0] . '/' . $URL[1] . '.php')) {
            require REQUIRE_PATH . '/' . $URL[0] . '/' . $URL[1] . '.php';
        } else {
            if (file_exists(REQUIRE_PATH . "/404.php")) {
                require REQUIRE_PATH . '/404.php';
            } else {
                trigger_error(
                    "Não foi possível incluir o arquivo themes/" . THEME . "/{$getURL}.php <b>(O arquivo 404 também não existe!)</b>"
                );
            }
        }

        // FOOTER
        if (file_exists(REQUIRE_PATH . "/inc/footer.php")) {
            require REQUIRE_PATH . "/inc/footer.php";
        } else {
            trigger_error('Crie um arquivo /inc/footer.php na pasta do tema!');
        }
    }
?>

<!--SCRIPTS && CSS-->

<script src='<?= BASE; ?>/_cdn/workcontrol.min.js'></script>

<?php
    // MAIN SCRIPT THEME
    if (file_exists('themes/' . THEME . '/js/scripts.min.js')):
        echo '<script src="' . INCLUDE_PATH . '/js/scripts.min.js"></script>' . "\r\n";
    endif;

    // WC THEME JS FILES
    if (file_exists('themes/' . THEME . '/wc_js')):
        foreach (scandir('themes/' . THEME . '/wc_js') as $wcJsThemeFiles):
            if (
                file_exists('themes/' . THEME . "/wc_js/{$wcJsThemeFiles}") && !is_dir(
                    'themes/' . THEME . "/wc_js/{$wcJsThemeFiles}"
                ) && pathinfo('themes/' . THEME . "/wc_js/{$wcJsThemeFiles}")['extension'] == 'js'
            ):
                echo '<script src="' . INCLUDE_PATH . '/wc_js/' . $wcJsThemeFiles . '"></script>' . "\r\n";
            endif;
        endforeach;
    endif;

    // WC CODES
    $Read->ExeRead(DB_WC_CODE);
    if ($Read->getResult()) {
        if (empty($Update)) {
            $Update = new Update;
        }

        $ActiveCodes = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
        echo "\r\n\r\n\r\n<!--WorkControl Codes-->\r\n";
        foreach ($Read->getResult() as $HomeCodes) {
            if (empty($HomeCodes['code_condition'])) {
                echo $HomeCodes['code_script'];
                $UpdateCodes = ['code_views' => $HomeCodes['code_views'] + 1];
                $Update->ExeUpdate(DB_WC_CODE, $UpdateCodes, "WHERE code_id = :id", "id={$HomeCodes['code_id']}");
            } elseif (preg_match("/" . str_replace("/", "\/", $HomeCodes['code_condition']) . "/", $ActiveCodes)) {
                echo $HomeCodes['code_script'];
                $UpdateCodes = ['code_views' => $HomeCodes['code_views'] + 1];
                $Update->ExeUpdate(DB_WC_CODE, $UpdateCodes, "WHERE code_id = :id", "id={$HomeCodes['code_id']}");
            }
        }
        echo "\r\n<!--/WorkControl Codes-->\r\n\r\n\r\n";
    }
?>

</body>

</html>
<?php
    }
    }
    ob_end_flush();

    if (!file_exists('.htaccess')) {
        $htaccesswrite = "RewriteEngine On\r\nOptions All -Indexes\r\n\r\n# WC WWW Redirect.\r\n#RewriteCond %{HTTP_HOST} !^www\. [NC]\r\n#RewriteRule ^ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\r\n\r\n# WC HTTPS Redirect\r\nRewriteCond %{HTTP:X-Forwarded-Proto} !https\r\nRewriteCond %{HTTPS} off\r\nRewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\r\n\r\n# WC URL Rewrite\r\nRewriteCond %{SCRIPT_FILENAME} !-f\r\nRewriteCond %{SCRIPT_FILENAME} !-d\r\nRewriteRule ^(.*)$ index.php?url=$1";
        $htaccess = fopen('.htaccess', "w");
        fwrite($htaccess, str_replace("'", '"', $htaccesswrite));
        fclose($htaccess);
    }
