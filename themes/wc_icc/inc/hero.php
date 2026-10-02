<?php

declare(strict_types=1);

/**
 * Hero da home (Onda 3). Único <h1> da página.
 * Copy em rascunho, aprovada como direção em 2026-09-16; validar com o cliente antes de publicar.
 */

$HeroWhatsMsg = urlencode(
    'Olá, estou no site da ICC SERRA e quero conversar com um Agente de Crédito sobre *MICROCRÉDITO*.'
);
?>
<section class="icc_hero" aria-labelledby="icc-hero-titulo">
    <div class="content icc_hero_inner">
        <div class="icc_hero_text">
            <p class="icc_eyebrow"><span class="icc_eyebrow_dot" aria-hidden="true"></span>Banco do Povo · microcrédito desde 2001</p>
            <?php /* [RASCUNHO] H1 e subtítulo: validar com o cliente */ ?>
            <h1 id="icc-hero-titulo">Crédito para quem <b>empreende</b> no Rio Grande do Sul</h1>
            <p class="icc_hero_lead">
                Capital de giro, equipamentos, reforma e ampliação, crédito agrícola para a agricultura familiar
                e uma linha para mulheres empreendedoras. Um agente de crédito vai até você.
            </p>
            <div class="icc_hero_actions">
                <a class="cta cta-red icc_hero_cta" href="<?= BASE; ?>/simulador">Simular meu crédito</a>
                <a class="icc_btn_ghost icon-whatsapp" href="https://wa.me/<?= SITE_ADDR_WHATS_TRIM; ?>?text=<?= $HeroWhatsMsg; ?>"
                   target="_blank" rel="noopener">Falar com um agente<span class="sr_only"> pelo WhatsApp (abre em nova aba)</span></a>
            </div>
        </div>

        <nav class="icc_hero_linhas" aria-label="Linhas de crédito">
            <p class="icc_hero_linhas_title">Escolha sua linha</p>
            <ul>
                <li><a href="#linha-produtivo"><span>Microcrédito Produtivo</span></a></li>
                <li><a href="#linha-mulher"><span>Microcrédito Mulher</span></a></li>
                <li><a href="#linha-reforma"><span>Reforma e Ampliação</span></a></li>
                <li><a href="#linha-agricola"><span>Crédito Agrícola</span></a></li>
            </ul>
        </nav>
    </div>
</section>
