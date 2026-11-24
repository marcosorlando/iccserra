<?php

declare(strict_types=1);

use App\Conn\Create;
use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\GadsLp;
use App\Models\Upload;

session_start();

require __DIR__ . '/../../vendor/autoload.php';
$NivelAcess = LEVEL_WC_GADS_LPS;

if (!APP_GADS_LPS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess) {
    $jSON['trigger'] = Check::ajaxErro(
        '<b>OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!',
        E_USER_ERROR
    );
    echo json_encode($jSON);

    exit;
}

usleep(50000);

// DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Gadslps';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];

// VALIDA AÇÃO
if (!empty($PostData['callback_action']) && ($PostData['callback'] ?? null) === $CallBack) {
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    $Read ??= new Read();
    $Create ??= new Create();
    $Update ??= new Update();
    $Delete ??= new Delete();

    switch ($Case) {
        // DELETE DA LP
        case 'delete':
            $Read->fullRead('SELECT page_hero_image FROM ' . DB_GADS_LPS . ' WHERE page_id = :id', 'id=' . (int)($PostData['del_id'] ?? 0));
            $OldImage = '../../uploads/gadslps/' . ($Read->getResult()[0]['page_hero_image'] ?? '');
            if (!empty($Read->getResult()[0]['page_hero_image']) && is_file($OldImage)) {
                unlink($OldImage);
            }

            $Delete->exeDelete(DB_GADS_LPS, 'WHERE page_id = :id', 'id=' . (int)($PostData['del_id'] ?? 0));
            $jSON['success'] = true;

            break;

        // MANAGER
        case 'manage':
            $PageId = (int)($PostData['page_id'] ?? 0);
            unset($PostData['page_id']);

            $Read->exeRead(DB_GADS_LPS, 'WHERE page_id = :id', 'id=' . $PageId);
            $ThisPage = $Read->getResult()[0] ?? null;
            if (!$ThisPage) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> A página que você está editando não existe mais!', E_USER_ERROR);

                break;
            }

            // CAMPOS DE TEXTO (page_content é HTML do admin e não é filtrado)
            foreach (['page_title', 'page_description', 'page_og_image', 'page_cta_url'] as $Field) {
                $PostData[$Field] = trim(strip_tags((string)($PostData[$Field] ?? '')));
            }
            $PostData['page_status'] = empty($PostData['page_status']) ? '0' : '1';

            // SLUG
            $Slug = Check::name('' === trim((string)($PostData['page_name'] ?? '')) ? $PostData['page_title'] : (string)$PostData['page_name']);
            $PostData['page_name'] = $Slug;
            if ('' === $Slug) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Informe o título da página para gerar a URL!', E_USER_WARNING);
                $jSON['foco'] = 'page_title';

                break;
            }

            // SLUG NÃO PODE COLIDIR COM OUTRA ROTA, PASTA, ARQUIVO OU ARQUIVO DO TEMA
            $Root = __DIR__ . '/../../';
            $Taken = is_dir($Root . $Slug)
                || is_file($Root . $Slug . '.php')
                || is_file($Root . 'themes/' . THEME . '/' . $Slug . '.php')
                || is_dir($Root . 'themes/' . THEME . '/' . $Slug)
                || in_array($Slug, ['index', 'rss', 'feed', 'amp', 'pesquisa', 'artigos', 'artigo'], true);

            $Others = [
                'SELECT page_name FROM ' . DB_PAGES . ' WHERE page_name = :nm',
                'SELECT page_name FROM ' . DB_LANDING_PAGES . ' WHERE page_name = :nm',
                'SELECT page_name FROM ' . DB_THANKYOU_PAGES . ' WHERE page_name = :nm',
                'SELECT carduser_url FROM ' . DB_CARD_USER . ' WHERE carduser_url = :nm',
            ];
            foreach ($Others as $Query) {
                $Read->fullRead($Query, 'nm=' . $Slug);
                $Taken = $Taken || (bool)$Read->getResult();
            }

            $Read->fullRead(
                'SELECT page_id FROM ' . DB_GADS_LPS . ' WHERE page_name = :nm AND page_id != :id',
                sprintf('nm=%s&id=%d', $Slug, $PageId)
            );
            if ($Taken || $Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    sprintf('<b>OPPSSS:</b> A URL <b>/%s</b> já está em uso por outra página, pasta ou arquivo. Escolha outra!', $Slug),
                    E_USER_WARNING
                );
                $jSON['foco'] = 'page_name';

                break;
            }

            // TAGS GOOGLE: SÓ ID VÁLIDO (VAZIO = USA A TAG GLOBAL DO SITE)
            $Tags = [
                'page_gtag_id' => [GadsLp::RE_GTAG, 'GA4 (G-XXXXXXXXXX)'],
                'page_ads_id' => [GadsLp::RE_ADS, 'Google Ads (AW-XXXXXXXXX)'],
                'page_gtm_id' => [GadsLp::RE_GTM, 'GTM (GTM-XXXXXXX)'],
                'page_ads_label' => [GadsLp::RE_LABEL, 'rótulo da conversão'],
            ];
            foreach ($Tags as $Field => [$Regex, $Label]) {
                $Value = trim((string)($PostData[$Field] ?? ''));
                // o rótulo é case-sensitive, os IDs são sempre maiúsculos
                $Value = 'page_ads_label' === $Field ? $Value : strtoupper($Value);
                if ('' !== $Value && !preg_match($Regex, $Value)) {
                    $jSON['trigger'] = Check::ajaxErro(sprintf('<b>OPPSSS:</b> O código de %s não é válido!', $Label), E_USER_WARNING);
                    $jSON['foco'] = $Field;

                    break 2;
                }
                $PostData[$Field] = '' === $Value ? null : $Value;
            }

            if (!empty($PostData['page_ads_label']) && empty($PostData['page_ads_id'])) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Informe o ID do Google Ads (AW-) para usar o rótulo de conversão!', E_USER_WARNING);
                $jSON['foco'] = 'page_ads_id';

                break;
            }

            // URL DO CTA (VAZIO = /simulador)
            if ('' !== $PostData['page_cta_url'] && !filter_var($PostData['page_cta_url'], FILTER_VALIDATE_URL)) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> A URL do CTA deve ser completa (https://...)!', E_USER_WARNING);
                $jSON['foco'] = 'page_cta_url';

                break;
            }
            $PostData['page_cta_url'] = '' === $PostData['page_cta_url'] ? null : $PostData['page_cta_url'];

            // JSON-LD PRECISA SER JSON VÁLIDO
            $PostData['page_schema'] = trim((string)($PostData['page_schema'] ?? ''));
            if ('' !== $PostData['page_schema'] && !is_array(json_decode($PostData['page_schema'], true))) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> O JSON-LD (Schema.org) não é um JSON válido!', E_USER_WARNING);
                $jSON['foco'] = 'page_schema';

                break;
            }
            $PostData['page_schema'] = '' === $PostData['page_schema'] ? null : $PostData['page_schema'];

            // CONTEÚDO: campos estruturados do form (textos sem HTML, listas sem linhas em branco)
            $Raw = is_array($_POST['data'] ?? null) ? $_POST['data'] : [];
            $Txt = static fn(mixed $v): string => trim(strip_tags((string)(is_scalar($v) ? $v : '')));
            $Fields = static function (mixed $src, array $keys) use ($Txt): array {
                $src = is_array($src) ? $src : [];
                $out = [];
                foreach ($keys as $key) {
                    $out[$key] = $Txt($src[$key] ?? '');
                }

                return $out;
            };
            $Rows = static function (mixed $src, array $keys, array $flags = []) use ($Txt): array {
                $out = [];
                foreach (is_array($src) ? $src : [] as $row) {
                    $row = is_array($row) ? $row : [];
                    $item = [];
                    foreach ($keys as $key) {
                        $item[$key] = $Txt($row[$key] ?? '');
                    }
                    if ('' === implode('', $item)) {
                        continue;
                    }
                    foreach ($flags as $flag) {
                        $item[$flag] = !empty($row[$flag]);
                    }
                    $out[] = $item;
                }

                return $out;
            };
            $Strings = static function (mixed $src) use ($Txt): array {
                return array_values(array_filter(array_map($Txt, is_array($src) ? $src : []), static fn(string $v): bool => '' !== $v));
            };

            $Hero = $Fields($Raw['hero'] ?? [], ['pill', 'title', 'subtitle', 'cta', 'cta2', 'microcopy', 'img_alt']);
            $Pains = $Fields($Raw['pains'] ?? [], ['title', 'transition', 'cta']);
            $Steps = $Fields($Raw['steps'] ?? [], ['title']);
            $Line = $Fields($Raw['line'] ?? [], ['tag', 'title', 'who_title', 'use_title', 'use_text', 'cond_title', 'docs_note', 'prazo', 'cta']);
            $Proof = $Fields($Raw['proof'] ?? [], ['tag', 'title', 'testi_title']);
            $Faq = $Fields($Raw['faq'] ?? [], ['title']);
            $Final = $Fields($Raw['final'] ?? [], ['title', 'cta']);
            // o texto final tem parágrafos: preserva as quebras de linha
            $Final['text'] = trim(strip_tags((string)($Raw['final']['text'] ?? '')));

            $Content = [
                'theme' => 'mulher' === ($Raw['theme'] ?? '') ? 'mulher' : '',
                'nav_label' => $Txt($Raw['nav_label'] ?? ''),
                'header_cta' => $Txt($Raw['header_cta'] ?? ''),
                'hero' => $Hero,
                'trust' => $Rows($Raw['trust'] ?? [], ['icon', 'text']),
                'pains' => $Pains + ['items' => $Strings($Raw['pains']['items'] ?? [])],
                'steps' => $Steps + ['items' => $Rows($Raw['steps']['items'] ?? [], ['title', 'desc'])],
                'line' => $Line + [
                    'who' => $Strings($Raw['line']['who'] ?? []),
                    'use' => $Strings($Raw['line']['use'] ?? []),
                    'docs' => $Strings($Raw['line']['docs'] ?? []),
                    'cond' => $Rows($Raw['line']['cond'] ?? [], ['value', 'label', 'badge']),
                ],
                'proof' => $Proof + [
                    'cards' => $Rows($Raw['proof']['cards'] ?? [], ['icon', 'title', 'text']),
                    'testi' => $Rows($Raw['proof']['testi'] ?? [], ['text', 'author', 'meta']),
                ],
                'faq' => $Faq + ['items' => $Rows($Raw['faq']['items'] ?? [], ['q', 'a', 'cta'], ['open'])],
                'final' => $Final,
                'legal' => $Txt($Raw['legal'] ?? ''),
                'wa_text' => $Txt($Raw['wa_text'] ?? ''),
            ];
            unset($PostData['data']);

            // NÃO PODE PUBLICAR SEM O ESSENCIAL
            if ('1' === $PostData['page_status'] && ('' === $Hero['title'] || '' === $Hero['cta'])) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Informe o título e o texto do botão do topo da página antes de publicar!', E_USER_WARNING);
                $jSON['foco'] = 'hero_title';

                break;
            }
            $PostData['page_data'] = json_encode($Content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            // IMAGEM DO TOPO
            $Image = $_FILES['page_hero_image'] ?? null;
            if ($Image && UPLOAD_ERR_OK === (int)$Image['error']) {
                $Upload = new Upload('../../uploads/gadslps/');
                $Upload->image($Image, $Slug . '-' . time(), 1200);

                if (!$Upload->getResult()) {
                    $jSON['trigger'] = Check::ajaxErro('<b>ERRO AO ENVIAR IMAGEM:</b> ' . $Upload->getError(), E_USER_WARNING);

                    break;
                }

                // O topo da LP exibe a imagem em 4:3 (CSS cover): recorta no centro para o admin mostrar o que o site mostra
                $Saved = '../../uploads/gadslps/' . $Upload->getResult();
                $Info = @getimagesize($Saved);
                $Loader = ['image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/webp' => 'imagecreatefromwebp'][$Info['mime'] ?? ''] ?? null;
                if ($Info && $Loader) {
                    [$W, $H] = $Info;
                    $CropW = min($W, (int)round($H * 4 / 3));
                    $CropH = min($H, (int)round($W * 3 / 4));
                    $Src = $Loader($Saved);
                    $Crop = $Src ? imagecrop($Src, ['x' => intdiv($W - $CropW, 2), 'y' => intdiv($H - $CropH, 2), 'width' => $CropW, 'height' => $CropH]) : false;
                    if ($Crop) {
                        match ($Info['mime']) {
                            'image/png' => imagepng($Crop, $Saved),
                            'image/webp' => imagewebp($Crop, $Saved, 85),
                            default => imagejpeg($Crop, $Saved, 90),
                        };
                    }
                }

                $OldImage = '../../uploads/gadslps/' . ($ThisPage['page_hero_image'] ?? '');
                if (!empty($ThisPage['page_hero_image']) && is_file($OldImage)) {
                    unlink($OldImage);
                }
                $PostData['page_hero_image'] = $Upload->getResult();
                $jSON['image'] = BASE . '/uploads/gadslps/' . $PostData['page_hero_image'];
            }

            $Update->exeUpdate(DB_GADS_LPS, $PostData, 'WHERE page_id = :id', 'id=' . $PageId);

            $jSON['name'] = $Slug;
            $jSON['view'] = BASE . '/' . $Slug;
            $jSON['trigger'] = Check::ajaxErro('<b>Ok!</b> Página atualizada com sucesso.');

            break;
    }

    // RETORNA O CALLBACK
    if ($jSON) {
        echo json_encode($jSON);
    } else {
        $jSON['trigger'] = Check::ajaxErro(
            '<b>OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
            E_USER_ERROR
        );
        echo json_encode($jSON);
    }
} else {
    // ACESSO DIRETO
    exit('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
}
