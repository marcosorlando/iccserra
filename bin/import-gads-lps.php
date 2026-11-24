<?php

declare(strict_types=1);

/**
 * Importa os HTMLs de lp/*.html para ws_gads_lps (módulo gadslps), em campos estruturados (page_data)
 * e copia a imagem do topo para uploads/gadslps/.
 * Idempotente: atualiza a LP se o slug já existir, mantendo status e IDs de tags.
 *
 *   /opt/homebrew/opt/php@8.5/bin/php bin/import-gads-lps.php --host=localhost [--publish]
 *
 * --host é obrigatório: o projeto escolhe o banco (dev x produção) pelo HTTP_HOST.
 */

use App\Conn\Create;
use App\Conn\Read;
use App\Conn\Update;

$Host = null;
foreach ($argv as $Arg) {
    if (str_starts_with($Arg, '--host=')) {
        $Host = substr($Arg, 7);
    }
}
if (!$Host) {
    fwrite(STDERR, "Informe --host=localhost (dev) ou --host=iccserra.org.br (produção)\n");
    exit(1);
}
$_SERVER['HTTP_HOST'] = $Host;

require __DIR__ . '/../vendor/autoload.php';

$Publish = in_array('--publish', $argv, true);
$Read = new Read();
$Create = new Create();
$Update = new Update();

/** Texto limpo de um nó: sem tags e com espaços normalizados. */
$Text = static fn(?DOMNode $n): string => $n ? trim((string)preg_replace('/\s+/u', ' ', $n->textContent)) : '';

foreach (glob(__DIR__ . '/../lp/*.html') ?: [] as $File) {
    $Html = (string)file_get_contents($File);
    $Slug = basename($File, '.html');

    $Dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $Dom->loadHTML('<?xml encoding="UTF-8">' . $Html);
    libxml_clear_errors();
    $X = new DOMXPath($Dom);

    // Atalhos XPath: um nó, vários nós
    $One = static fn(string $q, ?DOMNode $ctx = null): ?DOMNode => $X->query($q, $ctx)->item(0);
    $All = static fn(string $q, ?DOMNode $ctx = null): array => iterator_to_array($X->query($q, $ctx));
    $Cls = static fn(string $c): string => ".//*[contains(concat(' ', normalize-space(@class), ' '), ' {$c} ')]";
    $Meta = static fn(string $q): string => (string)($One($q)?->attributes?->getNamedItem('content')?->nodeValue ?? '');

    $Title = $Text($One('//title'));
    $Description = $Meta('//meta[@name="description"]');
    $OgImage = $Meta('//meta[@property="og:image"]');
    $Schema = trim((string)$One('//script[@type="application/ld+json"]')?->textContent);
    $Cta = static fn(string $id): string => $Text($One('//*[@data-cta="' . $id . '"]'));
    $Lists = static fn(?DOMNode $ul): array => array_map($Text, $ul ? $All('.//li', $ul) : []);

    // IMAGEM DO TOPO -> uploads/gadslps/images/importadas/
    $Img = $One($Cls('hero-image-wrap') . '//img');
    $HeroImage = null;
    if ($Img) {
        $Src = __DIR__ . '/../lp/' . $Img->attributes->getNamedItem('src')->nodeValue;
        if (is_file($Src)) {
            $Dir = __DIR__ . '/../uploads/gadslps/images/importadas';
            is_dir($Dir) || mkdir($Dir, 0777, true);
            copy($Src, $Dir . '/' . basename($Src));
            $HeroImage = 'images/importadas/' . basename($Src);
        }
    }

    // COLUNAS DA ESQUERDA / DIREITA DA SEÇÃO "LINHA"
    $LineSection = $One('//section[@aria-labelledby="line-title"]');
    $Cols = $All('.//*[contains(@class,"line-detail-grid")]/div', $LineSection);
    $Left = $Cols[0] ?? null;
    $Right = $Cols[1] ?? null;
    $H3 = $Left ? $All('./h3', $Left) : [];
    $Uls = $Left ? $All('./ul[contains(@class,"check-list")]', $Left) : [];
    $UseText = $Left ? $One('./p[not(@class)]', $Left) : null;
    $Docs = $Left ? $One('.//ul[contains(@class,"doc-list")]', $Left) : null;
    $Prazo = $Left ? $One('.//*[contains(@class,"prazo-info")]', $Left) : null;

    $Cond = [];
    foreach ($All($Cls('condition-item')) as $Item) {
        $Cond[] = [
            'value' => $Text($One($Cls('condition-value'), $Item)),
            'label' => $Text($One($Cls('condition-label'), $Item)),
            'badge' => $Text($One($Cls('condition-badge'), $Item)),
        ];
    }

    $Faq = [];
    foreach ($All($Cls('faq-item')) as $I => $Item) {
        $Faq[] = [
            'q' => $Text($One($Cls('faq-question'), $Item)),
            'a' => $Text($One('.//div[contains(@class,"faq-answer")]/p', $Item)),
            'cta' => rtrim($Text($One($Cls('faq-cta'), $Item))),
            'open' => $Item->attributes->getNamedItem('open') !== null,
        ];
    }

    $Wa = $One('//a[contains(@class,"wa-float")]');
    parse_str((string)parse_url((string)$Wa?->attributes?->getNamedItem('href')?->nodeValue, PHP_URL_QUERY), $WaQuery);

    // TEXTO FINAL: <br><br> vira parágrafo
    $FinalNode = $One($Cls('cta-final-text'));
    $FinalHtml = $FinalNode ? $Dom->saveHTML($FinalNode) : '';
    $FinalText = trim(html_entity_decode(strip_tags((string)preg_replace('/<br\s*\/?>/i', "\n", (string)preg_replace('/\s+/u', ' ', $FinalHtml))), ENT_QUOTES, 'UTF-8'));
    $FinalText = trim((string)preg_replace('/ *\n */', "\n", $FinalText));

    $Nav = $One('//nav[contains(@class,"header-nav")]//a[@aria-current="page"]');

    $Content = [
        'theme' => str_contains((string)$One('//body')?->attributes?->getNamedItem('class')?->nodeValue, 'lp-mulher') ? 'mulher' : '',
        'nav_label' => $Text($Nav),
        'header_cta' => $Cta('header'),
        'hero' => [
            'pill' => $Text($One($Cls('pill'))),
            'title' => $Text($One($Cls('hero-title'))),
            'subtitle' => $Text($One($Cls('hero-subtitle'))),
            'cta' => $Cta('hero_primary'),
            'cta2' => $Cta('hero_secondary'),
            'microcopy' => $Text($One($Cls('hero-microcopy'))),
            'img_alt' => (string)$Img?->attributes?->getNamedItem('alt')?->nodeValue,
        ],
        'trust' => array_map(static fn(DOMNode $n): array => [
            'icon' => $Text($One($Cls('trust-icon'), $n)),
            'text' => trim(str_replace($Text($One($Cls('trust-icon'), $n)), '', $Text($n))),
        ], $All($Cls('trust-item'))),
        'pains' => [
            'title' => $Text($One('//*[@id="pains-title"]')),
            'items' => array_map($Text, $All($Cls('pain-text'))),
            'transition' => $Text($One($Cls('pains-transition'))),
            'cta' => $Cta('pains_block'),
        ],
        'steps' => [
            'title' => $Text($One('//*[@id="steps-title"]')),
            'items' => array_map(static fn(DOMNode $n): array => [
                'title' => $Text($One($Cls('step-title'), $n)),
                'desc' => $Text($One($Cls('step-desc'), $n)),
            ], $All($Cls('step-card'))),
        ],
        'line' => [
            'tag' => $Text($One($Cls('section-tag'), $LineSection)),
            'title' => $Text($One('//*[@id="line-title"]')),
            'who_title' => $Text($H3[0] ?? null) ?: 'Quem pode pedir',
            'who' => $Lists($Uls[0] ?? null),
            'use_title' => $Text($H3[1] ?? null) ?: 'Para que serve',
            'use' => $Lists($Uls[1] ?? null),
            'use_text' => $Text($UseText),
            'docs' => $Lists($Docs),
            'docs_note' => $Text($One($Cls('docs-note'))),
            'prazo' => trim(preg_replace('/^Prazo médio:\s*/u', '', $Text($Prazo))),
            'cond_title' => $Text($One('.//h3', $One($Cls('conditions-box')))) ?: 'Condições do crédito',
            'cond' => $Cond,
            'cta' => $Cta('conditions_box'),
        ],
        'proof' => [
            'tag' => $Text($One($Cls('section-tag'), $One('//section[@aria-labelledby="proof-title"]'))),
            'title' => $Text($One('//*[@id="proof-title"]')),
            'cards' => array_map(static fn(DOMNode $n): array => [
                'icon' => $Text($One($Cls('cred-icon'), $n)),
                'title' => $Text($One($Cls('cred-title'), $n)),
                'text' => $Text($One($Cls('cred-text'), $n)),
            ], $All($Cls('cred-card'))),
            'testi_title' => $Text($One($Cls('testimonials-section') . '/h3')),
            'testi' => array_map(static fn(DOMNode $n): array => [
                'text' => trim($Text($One('./p', $n)), '"“” '),
                'author' => $Text($One($Cls('testimonial-author'), $n)),
                'meta' => $Text($One($Cls('testimonial-meta'), $n)),
            ], $All($Cls('testimonial-card'))),
        ],
        'faq' => ['title' => $Text($One('//*[@id="faq-title"]')), 'items' => $Faq],
        'final' => [
            'title' => $Text($One('//*[@id="cta-final-title"]')),
            'text' => $FinalText,
            'cta' => $Cta('cta_final'),
        ],
        'legal' => $Text($One($Cls('footer-legal-text'))),
        'wa_text' => (string)($WaQuery['text'] ?? ''),
    ];

    $Data = [
        'page_title' => $Title,
        'page_name' => $Slug,
        'page_description' => $Description,
        'page_og_image' => $OgImage ?: null,
        'page_schema' => $Schema ?: null,
        'page_data' => json_encode($Content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'page_hero_image' => $HeroImage,
    ];

    $Read->fullRead('SELECT page_id FROM ' . DB_GADS_LPS . ' WHERE page_name = :nm', 'nm=' . $Slug);
    if ($Read->getResult()) {
        $Update->exeUpdate(DB_GADS_LPS, $Data, 'WHERE page_id = :id', 'id=' . $Read->getResult()[0]['page_id']);
        echo "atualizada: {$Slug}\n";
    } else {
        $Create->exeCreate(DB_GADS_LPS, $Data + ['page_status' => $Publish ? 1 : 0, 'page_date' => date('Y-m-d H:i:s')]);
        echo "criada: {$Slug}\n";
    }
}
