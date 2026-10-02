<?php

declare(strict_types=1);

/**
 * Comparador das linhas de crédito (Onda 3): usado na home e no simulador.
 * Cards com linhas alinhadas por subgrid; no mobile viram cards empilhados.
 *
 * Variáveis opcionais antes do require:
 *   $LinhasEmbutido     bool          true em páginas com coluna de conteúdo (sem container próprio).
 *   $LinhasNoSimulador  bool          true em page-simulador.php: o CTA de cada linha preenche a linha no
 *                                     formulário (#tipo_credito) e leva o foco até ele. Implica embutido.
 *   $LinhasIds          list<string>  mostra só estas linhas (ids de inc/linhas-dados.php); padrão: todas.
 */

$LinhasNoSimulador = (bool)($LinhasNoSimulador ?? false);
$LinhasEmbutido = $LinhasNoSimulador || (bool)($LinhasEmbutido ?? false);
$Linhas = require REQUIRE_PATH . '/inc/linhas-dados.php';

if (!empty($LinhasIds) && is_array($LinhasIds)) {
    $Linhas = array_values(array_filter($Linhas, static fn (array $l): bool => in_array($l['id'], $LinhasIds, true)));
}
?>
<section class="icc_linhas<?= $LinhasEmbutido ? ' icc_linhas_embutido' : ''; ?>" id="linhas" aria-labelledby="icc-linhas-titulo">
    <div class="<?= $LinhasEmbutido ? 'icc_linhas_inner' : 'content'; ?>">
        <header class="site_header">
            <h2 id="icc-linhas-titulo">Descubra a linha de <b>crédito</b> ideal para você</h2>
            <p>Compare limite, prazo, taxa e condições <?= 4 === count($Linhas) ? 'das quatro linhas' : 'das linhas'; ?></p>
        </header>

        <div class="icc_linhas_grid">
            <?php foreach ($Linhas as $Linha) { ?>
                <article class="icc_linha icc_linha_<?= $Linha['id']; ?>" id="linha-<?= $Linha['id']; ?>"
                         aria-labelledby="linha-<?= $Linha['id']; ?>-titulo">
                    <header class="icc_linha_head">
                        <p class="icc_linha_tag"><?= $Linha['destaque']; ?></p>
                        <h3 id="linha-<?= $Linha['id']; ?>-titulo"><?= $Linha['nome']; ?></h3>
                        <p class="icc_linha_para"><?= $Linha['para']; ?></p>
                    </header>
                    <dl class="icc_linha_dados">
                        <div><dt><i class="icc-icon-limite" aria-hidden="true"></i>Limite</dt><dd><?= $Linha['limite']; ?></dd></div>
                        <div><dt><i class="icc-icon-prazo" aria-hidden="true"></i>Prazo</dt><dd><?= $Linha['prazo']; ?></dd></div>
                        <div><dt><i class="icc-icon-taxa" aria-hidden="true"></i>Taxa de juros</dt><dd><?= $Linha['taxa']; ?> <small><?= $Linha['taxa_ano']; ?></small></dd></div>
                        <div><dt><i class="icc-icon-avalista" aria-hidden="true"></i>Garantia</dt><dd><?= $Linha['garantia']; ?></dd></div>
                        <div><dt><i class="icc-icon-tac" aria-hidden="true"></i>Abertura</dt><dd><?= $Linha['tac']; ?></dd></div>
                        <div><dt><i class="<?= $Linha['extra_icone']; ?>" aria-hidden="true"></i><?= $Linha['extra_rotulo']; ?></dt><dd><?= $Linha['extra']; ?></dd></div>
                    </dl>
                    <footer class="icc_linha_actions">
                        <?php if ($LinhasNoSimulador) { ?>
                            <a class="cta cta-red" href="#simulador-credito" data-simular-linha="<?= $Linha['simulador']; ?>">Simular esta linha<span class="sr_only">: <?= $Linha['nome']; ?></span></a>
                        <?php } else { ?>
                            <a class="cta cta-red" href="<?= BASE; ?>/simulador">Simular<span class="sr_only"> <?= $Linha['nome']; ?></span></a>
                        <?php } ?>
                        <a class="icc_link_more" href="<?= BASE . $Linha['link']; ?>">Saiba mais<span class="sr_only"> sobre <?= $Linha['nome']; ?></span></a>
                    </footer>
                </article>
            <?php } ?>
        </div>

        <p class="icc_linhas_nota">
            Exemplo de simulação: empréstimo de R$ 1.000,00 em 12 parcelas de R$ 108,48. Pagando em dia, o total
            será de R$ 1.301,75. CET: 4,31% a.m. e 65,92% a.a.
        </p>
        <p class="icc_linhas_nota">
            Também fazemos <a href="<?= BASE; ?>/artigo/troca-de-cheques">troca de cheques</a> pré-datados.
        </p>
    </div>
</section>
