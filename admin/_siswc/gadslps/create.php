<?php

declare(strict_types=1);

use App\Conn\Create;
use App\Conn\Read;
use App\Helpers\Check;
use App\Models\GadsLp;

$AdminLevel = LEVEL_WC_GADS_LPS;
if (!APP_GADS_LPS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

$Read ??= new Read();
$Create ??= new Create();

$PageId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($PageId) {
    $Read->exeRead(DB_GADS_LPS, 'WHERE page_id = :id', 'id=' . $PageId);
    if ($Read->getResult()) {
        $Row = $Read->getResult()[0];
        $D = GadsLp::data($Row);
        $FormData = array_map(
            static fn($v) => htmlspecialchars((string)(is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $Read->getResult()[0]
        );
        extract($FormData);
    } else {
        $_SESSION['trigger_controll'] = Check::erro(
            sprintf('<b>OPPSS %s</b>, você tentou editar uma página que não existe ou que foi removida recentemente!', $Admin['user_name']),
            E_USER_NOTICE
        );
        header('Location: dashboard.php?wc=gadslps/home');

        exit;
    }
} else {
    $Create->exeCreate(DB_GADS_LPS, ['page_date' => date('Y-m-d H:i:s'), 'page_status' => 0]);
    header('Location: dashboard.php?wc=gadslps/create&id=' . $Create->getResult());

    exit;
}


// ESCAPE + HELPERS DO FORM
$h = static fn(mixed $v): string => htmlspecialchars((string)(is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$input = static function (string $name, string $label, mixed $value, string $type = 'text', string $ph = '', string $class = '') use ($h): string {
    $field = 'textarea' === $type
        ? sprintf('<textarea name="%s" rows="3" placeholder="%s">%s</textarea>', $h($name), $h($ph), $h($value))
        : sprintf('<input type="text" name="%s" value="%s" placeholder="%s"/>', $h($name), $h($value), $h($ph));

    return sprintf('<label class="label %s"><span class="legend">%s</span>%s</label>', $class, $label, $field);
};

/**
 * Repetidor: linhas com os campos de $fields (['chave' => [rótulo, tipo, placeholder]]); chave '' = lista de textos.
 * O <template> com __i__ é clonado pelo botão "Adicionar".
 */
$repeater = static function (string $base, string $title, array $fields, array $items, string $add) use ($h): string {
    $row = static function (string $i, mixed $item) use ($base, $fields, $h): string {
        $html = '<div class="gl_row">';
        foreach ($fields as $key => [$label, $type, $ph]) {
            $name = $base . '[' . $i . ']' . ('' === $key ? '' : '[' . $key . ']');
            $value = '' === $key ? $item : ($item[$key] ?? '');
            if ('check' === $type) {
                $html .= sprintf('<label class="gl_check"><input type="checkbox" name="%s" value="1"%s/> %s</label>', $h($name), $value ? ' checked' : '', $label);
            } elseif ('textarea' === $type) {
                $html .= sprintf('<label class="label"><span class="legend">%s</span><textarea name="%s" rows="3" placeholder="%s">%s</textarea></label>', $label, $h($name), $h($ph), $h($value));
            } else {
                $html .= sprintf('<label class="label"><span class="legend">%s</span><input type="text" name="%s" value="%s" placeholder="%s"/></label>', $label, $h($name), $h($value), $h($ph));
            }
        }

        return $html . '<span class="gl_remove btn btn_red icon-cancel-circle" title="Remover">Remover</span></div>';
    };

    $out = sprintf('<div class="gl_rep" data-base="%s"><p class="gl_rep_title">%s</p><div class="gl_rows">', $h($base), $title);
    foreach (array_values($items) as $i => $item) {
        $out .= $row((string)$i, $item);
    }
    $out .= '</div><template>' . $row('__i__', []) . '</template>';

    return $out . sprintf('<span class="gl_add btn btn_green icon-plus">%s</span></div>', $add);
};
?>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-target"><?= $page_title ?: 'Nova LP de Destino'; ?></h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="LPs de Destino" href="dashboard.php?wc=gadslps/home">LPs Google Ads</a>
			<span class="crumb">/</span>
			Gerenciar LP
		</p>
	</div>

	<div class="dashboard_header_search">
		<a title="Nova LP" href="dashboard.php?wc=gadslps/create" class="btn btn_green icon-plus">Nova</a>
        <?php if ($page_name) { ?>
			<a target="_blank" title="Ver no site" href="<?= BASE; ?>/<?= $page_name; ?>" class="wc_view btn btn_blue icon-eye float-right">Ver no site!</a>
        <?php } ?>
	</div>
</header>

<style>
	.gl_rep { margin: 0 0 14px; }
	.gl_rep_title { font-size: .9em; font-weight: 600; margin: 0 0 6px; }
	.gl_row { position: relative; border: 1px solid #ddd; background: #fafafa; border-radius: 3px; padding: 10px 10px 4px; margin-bottom: 8px; }
	.gl_row .btn { padding: 3px 8px; font-size: .75em; margin: 0 0 6px; }
	.gl_check { display: block; margin: 0 0 8px; font-size: .9em; }
	.gl_add { cursor: pointer; font-size: .8em; padding: 5px 10px; }
	.gl_remove { cursor: pointer; }
	.gl_sub { border-top: 1px solid #eee; margin: 14px 0 10px; padding-top: 10px; font-weight: 600; color: #555; }
</style>

<div class="dashboard_content">
	<form class="auto_save" name="page_add" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="callback" value="Gadslps"/>
		<input type="hidden" name="callback_action" value="manage"/>
		<input type="hidden" name="page_id" value="<?= $PageId; ?>"/>

		<div class="box box70">
			<div class="panel_header default"><h2 class="icon-page-break">Dados da página (SEO)</h2></div>
			<div class="panel">
				<label class="label">
					<span class="legend">Título (&lt;title&gt; e uso interno):</span>
					<input style="font-size: 1.4em;" type="text" name="page_title" value="<?= $page_title; ?>" placeholder="Ex.: Microcrédito Produtivo em Caxias do Sul - ICC Serra" required/>
				</label>
				<label class="label">
					<span class="legend">Descrição (meta description e Open Graph):</span>
					<input type="text" name="page_description" maxlength="320" value="<?= $page_description; ?>" placeholder="Até 160 caracteres para o Google"/>
				</label>
				<div class="label_50">
                    <?= $input('data[nav_label]', 'Nome no menu e no rodapé:', $D['nav_label'], 'text', 'Ex.: Agrícola'); ?>
                    <?= $input('data[header_cta]', 'Botão do cabeçalho:', $D['header_cta'], 'text', 'Simular crédito'); ?>
				</div>
				<label class="label">
					<span class="legend">Schema.org (JSON-LD):</span>
					<textarea name="page_schema" rows="6" style="font-family: monospace; font-size: .85em;"><?= $page_schema; ?></textarea>
				</label>
				<div class="clear"></div>
			</div>

			<div class="panel_header default"><h2 class="icon-star-full">Topo da página (hero)</h2></div>
			<div class="panel">
                <?= $input('data[hero][pill]', 'Chamada acima do título (selo):', $D['hero']['pill'], 'text', 'Para o pequeno produtor da Serra Gaúcha...'); ?>
				<label class="label">
					<span class="legend">Título principal (H1):</span>
					<input type="text" id="hero_title" name="data[hero][title]" value="<?= $h($D['hero']['title']); ?>"/>
				</label>
                <?= $input('data[hero][subtitle]', 'Subtítulo:', $D['hero']['subtitle'], 'textarea'); ?>
				<div class="label_50">
                    <?= $input('data[hero][cta]', 'Botão principal:', $D['hero']['cta'], 'text', 'Simular crédito agrícola'); ?>
                    <?= $input('data[hero][cta2]', 'Botão secundário (opcional):', $D['hero']['cta2']); ?>
				</div>
                <?= $input('data[hero][microcopy]', 'Texto abaixo dos botões:', $D['hero']['microcopy'], 'text', 'Leva 2 minutos. Um agente fala com você em até 24 horas.'); ?>
                <?= $input('data[hero][img_alt]', 'Descrição da imagem (alt):', $D['hero']['img_alt']); ?>

				<div class="gl_sub">Selos de confiança (faixa abaixo do topo)</div>
                <?= $repeater('data[trust]', 'Selos', ['icon' => ['Ícone (emoji)', 'text', '🏛️'], 'text' => ['Texto', 'text', 'Sem IOF']], $D['trust'], 'Adicionar selo'); ?>
			</div>

			<div class="panel_header default"><h2 class="icon-warning">Dores do cliente</h2></div>
			<div class="panel">
                <?= $input('data[pains][title]', 'Título da seção:', $D['pains']['title']); ?>
                <?= $repeater('data[pains][items]', 'Frases (uma por linha)', ['' => ['Frase', 'text', '"O trator quebrou na semana errada."']], $D['pains']['items'], 'Adicionar frase'); ?>
                <?= $input('data[pains][transition]', 'Texto de transição:', $D['pains']['transition'], 'textarea'); ?>
                <?= $input('data[pains][cta]', 'Botão da seção:', $D['pains']['cta']); ?>
			</div>

			<div class="panel_header default"><h2 class="icon-list-numbered">Como funciona (passos)</h2></div>
			<div class="panel">
                <?= $input('data[steps][title]', 'Título da seção:', $D['steps']['title']); ?>
                <?= $repeater('data[steps][items]', 'Passos (o 2º é destacado)', ['title' => ['Título do passo', 'text', ''], 'desc' => ['Descrição', 'textarea', '']], $D['steps']['items'], 'Adicionar passo'); ?>
			</div>

			<div class="panel_header default"><h2 class="icon-checkmark">Detalhes da linha de crédito</h2></div>
			<div class="panel">
				<div class="label_50">
                    <?= $input('data[line][tag]', 'Etiqueta da seção:', $D['line']['tag'], 'text', 'Crédito Agrícola ICC Serra'); ?>
                    <?= $input('data[line][title]', 'Título da seção:', $D['line']['title']); ?>
				</div>
                <?= $input('data[line][who_title]', 'Título da 1ª lista:', $D['line']['who_title']); ?>
                <?= $repeater('data[line][who]', 'Itens da 1ª lista', ['' => ['Item', 'text', '']], $D['line']['who'], 'Adicionar item'); ?>
                <?= $input('data[line][use_title]', 'Título da 2ª lista (esvazie os itens para ocultar):', $D['line']['use_title']); ?>
                <?= $repeater('data[line][use]', 'Itens da 2ª lista', ['' => ['Item', 'text', '']], $D['line']['use'], 'Adicionar item'); ?>
                <?= $input('data[line][use_text]', 'Ou um parágrafo no lugar da 2ª lista (opcional):', $D['line']['use_text'], 'textarea'); ?>
				<div class="gl_sub">Documentos necessários</div>
                <?= $repeater('data[line][docs]', 'Documentos', ['' => ['Documento', 'text', 'RG ou CNH']], $D['line']['docs'], 'Adicionar documento'); ?>
                <?= $input('data[line][docs_note]', 'Observação sobre os documentos:', $D['line']['docs_note'], 'textarea'); ?>
                <?= $input('data[line][prazo]', 'Prazo médio (continua depois de "Prazo médio:"):', $D['line']['prazo'], 'textarea', 'em média 5 a 10 dias úteis após a visita do agente...'); ?>
				<div class="gl_sub">Condições do crédito (caixa lateral)</div>
                <?= $repeater('data[line][cond]', 'Condições', ['value' => ['Valor em destaque', 'text', 'R$ 600 – R$ 20.000'], 'label' => ['Descrição', 'text', 'Valor disponível'], 'badge' => ['Selo (opcional)', 'text', 'Diferencial']], $D['line']['cond'], 'Adicionar condição'); ?>
                <?= $input('data[line][cond_title]', 'Título da caixa de condições:', $D['line']['cond_title']); ?>
                <?= $input('data[line][cta]', 'Botão da caixa de condições:', $D['line']['cta']); ?>
			</div>

			<div class="panel_header default"><h2 class="icon-heart">Prova social</h2></div>
			<div class="panel">
				<div class="label_50">
                    <?= $input('data[proof][tag]', 'Etiqueta da seção:', $D['proof']['tag'], 'text', 'Por que confiar'); ?>
                    <?= $input('data[proof][title]', 'Título da seção:', $D['proof']['title']); ?>
				</div>
                <?= $repeater('data[proof][cards]', 'Cards de credibilidade', ['icon' => ['Ícone (emoji)', 'text', '🏛️'], 'title' => ['Título', 'text', ''], 'text' => ['Texto', 'textarea', '']], $D['proof']['cards'], 'Adicionar card'); ?>
				<div class="gl_sub">Depoimentos</div>
                <?= $input('data[proof][testi_title]', 'Título dos depoimentos:', $D['proof']['testi_title']); ?>
                <?= $repeater('data[proof][testi]', 'Depoimentos', ['text' => ['Depoimento', 'textarea', ''], 'author' => ['Nome', 'text', ''], 'meta' => ['Profissão e cidade', 'text', 'Viticultor — Bento Gonçalves']], $D['proof']['testi'], 'Adicionar depoimento'); ?>
			</div>

			<div class="panel_header default"><h2 class="icon-question">Perguntas frequentes</h2></div>
			<div class="panel">
                <?= $input('data[faq][title]', 'Título da seção:', $D['faq']['title']); ?>
                <?= $repeater('data[faq][items]', 'Perguntas', ['q' => ['Pergunta', 'text', ''], 'a' => ['Resposta', 'textarea', ''], 'cta' => ['Texto do link para o simulador (opcional)', 'text', 'Simular agora →'], 'open' => ['Já aberta ao carregar', 'check', '']], $D['faq']['items'], 'Adicionar pergunta'); ?>
			</div>

			<div class="panel_header default"><h2 class="icon-bullhorn">Chamada final e rodapé</h2></div>
			<div class="panel">
                <?= $input('data[final][title]', 'Título da chamada final:', $D['final']['title'], 'textarea'); ?>
                <?= $input('data[final][text]', 'Texto (linha em branco separa parágrafos):', $D['final']['text'], 'textarea'); ?>
                <?= $input('data[final][cta]', 'Botão final:', $D['final']['cta']); ?>
                <?= $input('data[legal]', 'Texto legal do rodapé (taxas, prazos, condições):', $D['legal'], 'textarea'); ?>
                <?= $input('data[wa_text]', 'Mensagem do WhatsApp flutuante:', $D['wa_text'], 'text', 'Olá! Quero saber sobre o Crédito Agrícola da ICC Serra.'); ?>
			</div>
		</div>

		<div class="box box30">
			<div class="panel_header default"><h2 class="icon-image">Imagem do topo</h2></div>
			<div class="panel">
				<label class="label">
					<span class="legend">Imagem do hero <b>(1200x900px, proporção 4:3 - JPG/PNG/WEBP)</b>:</span>
					<input type="file" class="wc_loadimage" name="page_hero_image"/>
				</label>
				<div class="post_create_cover m_botton">
					<div class="upload_progress none">0%</div>
                    <?php
                    $HeroFile = (!empty($page_hero_image) && is_file('../uploads/gadslps/' . $page_hero_image))
                        ? 'uploads/gadslps/' . $page_hero_image
                        : 'admin/_img/no_image.jpg';
                    ?>
					<img class="post_thumb page_hero_image" alt="Imagem do topo" title="Imagem do topo"
					     src="../tim.php?src=<?= $HeroFile; ?>&w=600&h=450&fit=crop"
					     default="../tim.php?src=<?= $HeroFile; ?>&w=600&h=450&fit=crop"/>
				</div>
				<small>A imagem é recortada ao centro em 4:3, exatamente como aparece no site. Sem imagem enviada, a LP usa a imagem padrão do tema.</small>
			</div>

			<div class="panel_header default"><h2 class="icon-target">Tags Google (dinâmicas)</h2></div>
			<div class="panel">
				<p style="font-size: .85em; margin-bottom: 10px;">Em branco, a LP usa as tags globais do site (<?= SITE_NAME; ?>). Os códigos são injetados no &lt;head&gt; só desta página.</p>
				<label class="label">
					<span class="legend">Google Analytics 4:</span>
					<input type="text" name="page_gtag_id" value="<?= $page_gtag_id; ?>" placeholder="<?= $h(defined('SEGMENT_GL_ANALYTICS') ? SEGMENT_GL_ANALYTICS : 'G-XXXXXXXXXX'); ?>"/>
				</label>
				<label class="label">
					<span class="legend">Google Ads - ID da conta:</span>
					<input type="text" name="page_ads_id" value="<?= $page_ads_id; ?>" placeholder="<?= $h(defined('SEGMENT_GL_ADWORDS_ID') ? SEGMENT_GL_ADWORDS_ID : 'AW-123456789'); ?>"/>
				</label>
				<label class="label">
					<span class="legend">Google Ads - Rótulo da conversão (disparada ao concluir a simulação):</span>
					<input type="text" name="page_ads_label" value="<?= $page_ads_label; ?>" placeholder="AbC-D_efG-h12345"/>
				</label>
				<label class="label">
					<span class="legend">Google Tag Manager:</span>
					<input type="text" name="page_gtm_id" value="<?= $page_gtm_id; ?>" placeholder="<?= $h(defined('SEGMENT_GL_TAGMANAGER') ? SEGMENT_GL_TAGMANAGER : 'GTM-XXXXXXX'); ?>"/>
				</label>
			</div>

			<div class="panel_header default"><h2>Publicação</h2></div>
			<div class="panel">
				<label class="label">
					<span class="legend">URL da página (<?= BASE; ?>/...):</span>
					<input type="text" name="page_name" value="<?= $page_name; ?>" placeholder="gerado a partir do título"/>
				</label>
				<label class="label">
					<span class="legend">Visual:</span>
					<select name="data[theme]">
						<option value="">Padrão (vermelho)</option>
						<option value="mulher"<?= 'mulher' === $D['theme'] ? ' selected' : ''; ?>>Mulher (detalhe terracota)</option>
					</select>
				</label>
				<label class="label">
					<span class="legend">Destino dos CTAs (vazio = <?= BASE; ?>/simulador):</span>
					<input type="url" name="page_cta_url" value="<?= $page_cta_url; ?>" placeholder="https://..."/>
				</label>
				<label class="label">
					<span class="legend">Imagem Open Graph (URL completa, opcional - vazio usa a imagem do topo):</span>
					<input type="url" name="page_og_image" value="<?= $page_og_image; ?>" placeholder="https://..."/>
				</label>

				<div class="wc_actions" style="text-align: center; margin: 10px 0;">
                    <?= Check::switchOnOff('page_status', (int)$page_status); ?>
					<button name="public" value="1" class="btn btn_green icon-share">ATUALIZAR</button>
					<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	// REPETIDORES: clona o <template> trocando __i__ por um índice único e dispara o auto_save
	$(function () {
		$('form.auto_save').on('click', '.gl_add', function () {
			var rep = $(this).closest('.gl_rep');
			var html = rep.find('template').html().replace(/__i__/g, 'n' + Date.now());
			rep.find('.gl_rows').append(html);
			rep.find('.gl_rows .gl_row:last input:first, .gl_rows .gl_row:last textarea:first').focus();
		}).on('click', '.gl_remove', function () {
			var form = $(this).closest('form');
			$(this).closest('.gl_row').remove();
			form.trigger('change');
		});
	});
</script>
