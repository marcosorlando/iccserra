<?php

declare(strict_types=1);

use App\Conn\Update;
use App\Models\GadsLp;

/**
 * LP de destino de campanhas Google Ads: documento próprio (fora do layout do site).
 * $GadsPage vem do index.php. Os CTAs levam ao simulador (fim do funil).
 */
$E = GadsLp::e(...);
$AssetsUrl = INCLUDE_PATH . '/gads';
$AssetsDir = __DIR__ . '/gads';
$Schema = GadsLp::schema($GadsPage);
$Canonical = BASE . '/' . $GadsPage['page_name'];
$OgImage = (string)$GadsPage['page_og_image'] ?: (empty($GadsPage['page_hero_image']) ? '' : GadsLp::heroImage($GadsPage, $AssetsUrl));
$BodyClass = 'mulher' === (GadsLp::data($GadsPage)['theme'] ?? '') ? ' class="lp-mulher"' : '';

// VISITA
(new Update())->exeUpdate(
    DB_GADS_LPS,
    ['page_views' => (int)$GadsPage['page_views'] + 1],
    'WHERE page_id = :id',
    'id=' . (int)$GadsPage['page_id']
);

$Css = $AssetsDir . '/css/styles.css';
$Js = $AssetsDir . '/js/main.js';
$JsCta = $AssetsDir . '/js/gads-cta.js';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $E($GadsPage['page_title']); ?></title>
  <meta name="description" content="<?= $E($GadsPage['page_description']); ?>">
  <link rel="canonical" href="<?= $E($Canonical); ?>">

  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= $E($GadsPage['page_title']); ?>">
  <meta property="og:description" content="<?= $E($GadsPage['page_description']); ?>">
  <meta property="og:url" content="<?= $E($Canonical); ?>">
<?php if ($OgImage) { ?>
  <meta property="og:image" content="<?= $E($OgImage); ?>">
<?php } ?>
  <meta property="og:locale" content="pt_BR">
  <meta property="og:site_name" content="<?= $E(SITE_NAME); ?>">

  <!-- TAGS GOOGLE (dinâmicas, ws_gads_lps) -->
<?= GadsLp::headTags($GadsPage); ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $AssetsUrl; ?>/css/styles.css?v=<?= is_file($Css) ? filemtime($Css) : 1; ?>">
  <link rel="icon" type="image/png" href="<?= $AssetsUrl; ?>/img/iccserra-logo-h.png">
<?php if ($Schema) { ?>
  <script type="application/ld+json"><?= $Schema; ?></script>
<?php } ?>
</head>

<body<?= $BodyClass; ?> data-send-to="<?= $E(GadsLp::sendTo($GadsPage)); ?>" data-cta-url="<?= $E(GadsLp::ctaUrl($GadsPage)); ?>">
<?= GadsLp::bodyTags($GadsPage); ?>

<?php require $AssetsDir . '/inc/lp-body.php'; ?>

<script src="<?= $AssetsUrl; ?>/js/main.js?v=<?= is_file($Js) ? filemtime($Js) : 1; ?>"></script>
<script src="<?= $AssetsUrl; ?>/js/gads-cta.js?v=<?= is_file($JsCta) ? filemtime($JsCta) : 1; ?>"></script>
</body>
</html>
