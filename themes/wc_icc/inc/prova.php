<?php

declare(strict_types=1);

/**
 * Faixa de prova (Onda 3). Só dados verificáveis no próprio site:
 * fundação (página Institucional), postos ativos (banco), limite mínimo e taxa máxima (linhas de crédito).
 */

use App\Conn\Read;

$Read ??= new Read();

$ProvaPostos = 0;
if (defined('DB_SERVICES_STATIONS')) {
    $Read->fullRead('SELECT COUNT(*) AS total FROM ' . DB_SERVICES_STATIONS . ' WHERE status = :st', 'st=1');
    $ProvaPostos = (int)($Read->getResult()[0]['total'] ?? 0);
}

$ProvaAnos = (int)date('Y') - 2001;
?>
<section class="icc_prova" aria-label="A ICC Serra em números">
    <ul class="content">
        <li>
            <strong class="icc_prova_num"><?= $ProvaAnos; ?> anos</strong>
            <span class="icc_prova_label">de microcrédito, desde 2001</span>
        </li>
        <?php if ($ProvaPostos > 0) { ?>
            <li>
                <strong class="icc_prova_num"><?= $ProvaPostos; ?> postos</strong>
                <span class="icc_prova_label">de atendimento no RS</span>
            </li>
        <?php } ?>
        <li>
            <strong class="icc_prova_num">R$ 600</strong>
            <span class="icc_prova_label">valor mínimo de crédito</span>
        </li>
        <li>
            <strong class="icc_prova_num">3,99% a.m.</strong>
            <span class="icc_prova_label">taxa máxima nas linhas</span>
        </li>
    </ul>
</section>
