<?php

declare(strict_types=1);

namespace App\Models;

use App\Conn\Read;

use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_map;
use function array_replace_recursive;
use function dirname;
use function implode;
use function is_file;
use function trim;
use function array_values;
use function constant;
use function defined;
use function htmlspecialchars;
use function is_array;
use function json_decode;
use function json_encode;
use function preg_match;
use function sprintf;

use const ENT_QUOTES;
use const ENT_SUBSTITUTE;
use const JSON_HEX_AMP;
use const JSON_HEX_APOS;
use const JSON_HEX_QUOT;
use const JSON_HEX_TAG;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

/**
 * LPs de destino das campanhas Google Ads (ws_gads_lps).
 * A LP é um documento próprio: os textos vêm do banco (page_data, JSON dos campos do admin) e as tags Google
 * são montadas a partir dos IDs cadastrados no admin (com fallback para as globais do site).
 */
final class GadsLp
{
    public const RE_GTAG = '/^G-[A-Z0-9]{4,15}$/';
    public const RE_ADS = '/^AW-\d{6,15}$/';
    public const RE_GTM = '/^GTM-[A-Z0-9]{4,10}$/';
    public const RE_LABEL = '/^[A-Za-z0-9_-]{6,60}$/';

    /** Retorna a LP publicada pelo slug, ou null. */
    public static function find(string $slug): ?array
    {
        if ('' === $slug) {
            return null;
        }

        $Read = new Read();
        $Read->exeRead(DB_GADS_LPS, 'WHERE page_name = :nm AND page_status = 1', 'nm=' . $slug);

        return $Read->getResult()[0] ?? null;
    }

    /** IDs efetivos: o da LP, ou o global do site quando a LP não define. */
    public static function ids(array $lp): array
    {
        return [
            'gtag' => self::valid($lp['page_gtag_id'] ?? null, self::RE_GTAG) ?? self::valid(self::global('SEGMENT_GL_ANALYTICS'), self::RE_GTAG),
            'ads' => self::valid($lp['page_ads_id'] ?? null, self::RE_ADS) ?? self::valid(self::global('SEGMENT_GL_ADWORDS_ID'), self::RE_ADS),
            'gtm' => self::valid($lp['page_gtm_id'] ?? null, self::RE_GTM) ?? self::valid(self::global('SEGMENT_GL_TAGMANAGER'), self::RE_GTM),
            'label' => self::valid($lp['page_ads_label'] ?? null, self::RE_LABEL),
        ];
    }

    /** Tags do <head>: um único gtag.js, um config por ID e o GTM. */
    public static function headTags(array $lp): string
    {
        $ids = self::ids($lp);
        $out = '';

        $configs = array_values(array_filter([$ids['gtag'], $ids['ads']]));
        if ($configs) {
            $out .= sprintf(
                "<script async src=\"https://www.googletagmanager.com/gtag/js?id=%s\"></script>\r\n",
                self::e($configs[0])
            );
            $calls = '';
            foreach ($configs as $id) {
                $calls .= sprintf('gtag(\'config\',%s);', self::js($id));
            }
            $out .= "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());" . $calls . "</script>\r\n";
        }

        if ($ids['gtm']) {
            $out .= "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});"
                . "var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;"
                . "j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})"
                . '(window,document,\'script\',\'dataLayer\',' . self::js($ids['gtm']) . ");</script>\r\n";
        }

        return $out;
    }

    /** <noscript> do GTM, logo após o <body>. */
    public static function bodyTags(array $lp): string
    {
        $gtm = self::ids($lp)['gtm'];

        return $gtm ? sprintf(
            '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>',
            self::e($gtm)
        ) : '';
    }

    /** "AW-123/rótulo" para o send_to da conversão, ou '' quando não configurada. */
    public static function sendTo(array $lp): string
    {
        $ids = self::ids($lp);

        return $ids['ads'] && $ids['label'] ? $ids['ads'] . '/' . $ids['label'] : '';
    }

    /** URL de destino dos CTAs (simulador). */
    public static function ctaUrl(array $lp): string
    {
        $url = (string)($lp['page_cta_url'] ?? '');

        return '' !== $url ? $url : BASE . '/simulador';
    }

    /** Conteúdo editável da LP (page_data) mesclado com a estrutura padrão, para o template e o admin. */
    public static function data(array $lp): array
    {
        $saved = json_decode((string)($lp['page_data'] ?? ''), true);

        return array_replace_recursive(self::defaults(), is_array($saved) ? $saved : []);
    }

    /** Estrutura padrão do conteúdo: todos os campos existem, mesmo em LP recém-criada. */
    public static function defaults(): array
    {
        return [
            'theme' => '',
            'nav_label' => '',
            'header_cta' => 'Simular crédito',
            'hero' => ['pill' => '', 'title' => '', 'subtitle' => '', 'cta' => '', 'cta2' => '', 'microcopy' => '', 'img_alt' => ''],
            'trust' => [],
            'pains' => ['title' => '', 'items' => [], 'transition' => '', 'cta' => ''],
            'steps' => ['title' => '', 'items' => []],
            'line' => [
                'tag' => '', 'title' => '', 'who_title' => 'Quem pode pedir', 'who' => [], 'use_title' => 'Para que serve', 'use' => [], 'use_text' => '', 'cond_title' => 'Condições do crédito',
                'docs' => [], 'docs_note' => '', 'prazo' => '', 'cond' => [], 'cta' => '',
            ],
            'proof' => ['tag' => 'Por que confiar', 'title' => 'Por que confiar na ICC Serra?', 'cards' => [], 'testi_title' => '', 'testi' => []],
            'faq' => ['title' => '', 'items' => []],
            'final' => ['title' => '', 'text' => '', 'cta' => ''],
            'legal' => '',
            'wa_text' => '',
        ];
    }

    /** Remove itens vazios de uma lista (linhas em branco do repetidor do admin). */
    public static function clean(mixed $items, array $keys = []): array
    {
        $out = [];
        foreach ((array)$items as $item) {
            if ($keys) {
                $item = array_intersect_key((array)$item, array_flip($keys));
                if ('' === trim(implode('', array_map('strval', $item)))) {
                    continue;
                }
            } elseif ('' === trim((string)$item)) {
                continue;
            }
            $out[] = $item;
        }

        return $out;
    }

    /** URL pública da imagem do hero, ou a imagem padrão do tema. */
    public static function heroImage(array $lp, string $assetsUrl): string
    {
        $file = (string)($lp['page_hero_image'] ?? '');
        if ('' !== $file && is_file(dirname(__DIR__, 2) . '/uploads/gadslps/' . $file)) {
            return BASE . '/uploads/gadslps/' . $file;
        }

        return $assetsUrl . '/img/microcredito.webp';
    }

    /** LPs publicadas (menu e rodapé: links entre as linhas de crédito). */
    public static function published(): array
    {
        $Read = new Read();
        $Read->fullRead('SELECT page_name, page_title, page_data FROM ' . DB_GADS_LPS . ' WHERE page_status = 1 ORDER BY page_id ASC');

        $out = [];
        foreach ($Read->getResult() ?: [] as $row) {
            $label = trim((string)(self::data($row)['nav_label'] ?? ''));
            $out[] = ['name' => $row['page_name'], 'label' => '' !== $label ? $label : $row['page_title']];
        }

        return $out;
    }

    /** JSON-LD revalidado e re-serializado (nunca o texto cru do banco). */
    public static function schema(array $lp): string
    {
        $data = json_decode((string)($lp['page_schema'] ?? ''), true);

        return is_array($data)
            ? (string)json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
            : '';
    }

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private static function global(string $constant): string
    {
        return defined($constant) ? (string)constant($constant) : '';
    }

    private static function valid(mixed $value, string $regex): ?string
    {
        $value = (string)$value;

        return '' !== $value && preg_match($regex, $value) ? $value : null;
    }

    private static function js(string $value): string
    {
        return (string)json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
}
