<?php

declare(strict_types=1);

/**
 * Como funciona (Onda 3): substitui a seção "Converse com um Agente" na home.
 * Etapas descritas no próprio site (simulador online, visita do agente, análise e liberação).
 */

$PassosWhatsMsg = urlencode(
    'Olá, estou no site da ICC SERRA e quero agendar a visita de um Agente de Crédito.'
);
?>
<section class="icc_passos" aria-labelledby="icc-passos-titulo">
    <div class="content">
        <header class="site_header">
            <h2 id="icc-passos-titulo">Como <b>funciona</b></h2>
            <p>Do primeiro contato ao crédito liberado</p>
        </header>

        <ol class="icc_passos_lista">
            <li>
                <span class="icc_passo_num" aria-hidden="true">1</span>
                <h3>Simule online</h3>
                <p>Escolha a linha, o valor e o número de parcelas no simulador.</p>
            </li>
            <li>
                <span class="icc_passo_num" aria-hidden="true">2</span>
                <h3>Receba o agente de crédito</h3>
                <p>Um agente vai até você para entender a necessidade do seu negócio.</p>
            </li>
            <li>
                <span class="icc_passo_num" aria-hidden="true">3</span>
                <h3>Análise e liberação</h3>
                <p>Com a capacidade de pagamento avaliada, o crédito é liberado.</p>
            </li>
        </ol>

        <div class="icc_passos_actions">
            <a class="cta cta-red" href="<?= BASE; ?>/simulador">Simular meu crédito</a>
            <a class="cta cta_green icon-whatsapp" href="https://wa.me/<?= SITE_ADDR_WHATS_TRIM; ?>?text=<?= $PassosWhatsMsg; ?>"
               target="_blank" rel="noopener">Agendar visita<span class="sr_only"> pelo WhatsApp (abre em nova aba)</span></a>
        </div>
    </div>
</section>
