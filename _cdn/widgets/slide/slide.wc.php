<?php

/**
 * WC SLIDE · carrossel de destaque em tela cheia.
 *
 * Modelo visual: hero de largura total com imagem ao fundo, véu escuro,
 * título de display com a última palavra em destaque, texto de apoio e CTA
 * (referência aprovada pelo cliente em 2026-09-17).
 *
 * Variáveis opcionais antes do require:
 *   $SlideSeconts  int   segundos entre slides (padrão 5)
 *   $SlidePriority bool  true quando o carrossel é o LCP da página
 *
 * O texto sobre a imagem só aparece nos slides em que o admin marcou
 * "mostrar título" ou "mostrar descrição" — as artes que já trazem texto
 * gravado continuam limpas.
 *
 * Acessibilidade: pausa no hover/foco, botão Pausar/Reproduzir (WCAG 2.2.2),
 * setas e paginação por botão, slides fora de foco ficam inertes e sem
 * autoplay com prefers-reduced-motion.
 */

use App\Conn\Read;

$Read ??= new Read();

$Read->fullRead(
    'SELECT slide_image_mobile, slide_image_tablet, slide_image_desktop, slide_title, slide_headline, slide_desc, '
    . 'slide_link, show_headline, show_desc FROM ' . DB_SLIDES
    . ' WHERE slide_status = :status AND slide_start <= NOW() AND (slide_end >= NOW() OR slide_end IS NULL)'
    . ' ORDER BY slide_date DESC',
    'status=1'
);

if ($Read->getResult()) {
    $Slides = $Read->getResult();
    $SlideSeconds = (int)($SlideSeconts ?? 5);
    $SlideFirstPriority = (bool)($SlidePriority ?? true); // false quando o carrossel não é o LCP (ex.: abaixo do hero)
    $SlideTotal = count($Slides);
    $SlideAsset = static fn (string $file): string => BASE . '/_cdn/widgets/slide/' . $file
        . '?v=' . (string)@filemtime(__DIR__ . '/' . $file);

    /** Destaca a última palavra do título, como no <b> dos títulos do tema. */
    $SlideHighlight = static function (string $title): string {
        $title = trim($title);
        $safe = htmlspecialchars($title, ENT_QUOTES);
        $cut = mb_strrpos($title, ' ');

        if (false === $cut) {
            return '<b>' . $safe . '</b>';
        }

        return htmlspecialchars(mb_substr($title, 0, $cut + 1), ENT_QUOTES)
            . '<b>' . htmlspecialchars(mb_substr($title, $cut + 1), ENT_QUOTES) . '</b>';
    };
    ?>
    <link rel="stylesheet" href="<?= $SlideAsset('slide.wc.min.css'); ?>"/>
    <script defer src="<?= $SlideAsset('slide.wc.min.js'); ?>"></script>

    <section class="wc_slides" aria-roledescription="carrossel" aria-label="Destaques"
             data-seconds="<?= $SlideSeconds; ?>">
        <div class="wc_slide_track">
            <?php
            foreach ($Slides as $Index => $SLIDE) {
                $ImageDesktop = $SLIDE['slide_image_desktop'];
                $ImageTablet = empty($SLIDE['slide_image_tablet']) ? $ImageDesktop : $SLIDE['slide_image_tablet'];
                $ImageMobile = empty($SLIDE['slide_image_mobile']) ? $ImageTablet : $SLIDE['slide_image_mobile'];
                $Title = htmlspecialchars((string)$SLIDE['slide_title'], ENT_QUOTES);
                $Link = htmlspecialchars((string)$SLIDE['slide_link'], ENT_QUOTES);
                $Headline = trim((string)$SLIDE['slide_headline']);
                $Desc = trim((string)$SLIDE['slide_desc']);
                $ShowHeadline = !empty($SLIDE['show_headline']) && '' !== $Headline;
                $ShowDesc = !empty($SLIDE['show_desc']) && '' !== $Desc;
                $HasText = $ShowHeadline || $ShowDesc;
                $IsFirst = 0 === $Index;
                ?>
                <article class="wc_slide_item<?= $IsFirst ? ' is-current' : ''; ?><?= $HasText ? ' has-text' : ''; ?>"
                         aria-roledescription="slide"
                         aria-label="<?= ($Index + 1) . ' de ' . $SlideTotal; ?>"
                    <?= $IsFirst ? '' : 'aria-hidden="true"'; ?>>
                    <?php if (!$HasText) { ?>
                    <a class="wc_slide_link" href="<?= $Link; ?>" title="<?= $Title; ?>">
                        <?php } ?>
                        <picture class="wc_slide_media">
                            <source media="(min-width: 992px)" srcset="<?= BASE; ?>/uploads/<?= $ImageDesktop; ?>"/>
                            <source media="(min-width: 544px)" srcset="<?= BASE; ?>/uploads/<?= $ImageTablet; ?>"/>
                            <img src="<?= BASE; ?>/uploads/<?= $ImageMobile; ?>" alt="<?= $Title; ?>"
                                 width="<?= SLIDE_W; ?>" height="<?= SLIDE_H; ?>"
                                 <?= $IsFirst ? ($SlideFirstPriority ? 'fetchpriority="high"' : '') : 'loading="lazy"'; ?> decoding="async"/>
                        </picture>
                        <?php if (!$HasText) { ?>
                    </a>
                <?php } ?>

                    <?php if ($HasText) { ?>
                        <span class="wc_slide_scrim" aria-hidden="true"></span>
                        <span class="wc_slide_wave" aria-hidden="true"></span>
                        <div class="wc_slide_content">
                            <div class="wc_slide_inner">
                                <h2 class="wc_slide_title"><?= $SlideHighlight((string)$SLIDE['slide_title']); ?></h2>
                                <?php if ($ShowHeadline) { ?>
                                    <p class="wc_slide_headline"><?= htmlspecialchars($Headline, ENT_QUOTES); ?></p>
                                <?php } ?>
                                <?php if ($ShowDesc) { ?>
                                    <p class="wc_slide_text"><?= htmlspecialchars($Desc, ENT_QUOTES); ?></p>
                                <?php } ?>
                                <?php if ('' !== $Link) { ?>
                                    <a class="wc_slide_cta" href="<?= $Link; ?>">Saiba mais<span
                                                class="sr_only"> sobre <?= $Title; ?></span></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </article>
                <?php
            }
            ?>
        </div>

        <?php
        if ($SlideTotal > 1) {
            ?>
            <button type="button" class="wc_slide_arrow wc_slide_prev" aria-label="Destaque anterior">
                <span aria-hidden="true"></span>
            </button>
            <button type="button" class="wc_slide_arrow wc_slide_next" aria-label="Próximo destaque">
                <span aria-hidden="true"></span>
            </button>

            <div class="wc_slide_controls">
                <button type="button" class="wc_slide_toggle" aria-pressed="false" aria-label="Pausar destaques">
                    <span class="wc_slide_toggle_icon" aria-hidden="true"></span>
                </button>
                <div class="wc_slide_pager">
                    <?php for ($i = 0; $i < $SlideTotal; $i++) { ?>
                        <button type="button"<?= 0 === $i ? " class='active' aria-current='true'" : ''; ?>
                                aria-label="Ir para o destaque <?= $i + 1; ?> de <?= $SlideTotal; ?>"></button>
                    <?php } ?>
                </div>
            </div>
            <span class="wc_slide_progress" aria-hidden="true"></span>
        <?php } ?>
    </section>
    <?php
}
