<?php

declare(strict_types=1);

/**
 * Transparência (Onda 3): documentos institucionais em lista legível.
 * A cor dos botões cadastrada no admin (doc_btn_*) deixa de ser aplicada: o visual segue o tema (contraste AA).
 */

use App\Conn\Read;

$Read ??= new Read();

$DocSize = static function (string $file): string {
    $path = dirname(__DIR__, 3) . '/uploads/documents/' . $file;
    if ('' === $file || !is_file($path)) {
        return 'PDF';
    }

    $bytes = (int)filesize($path);

    return $bytes >= 1048576
        ? 'PDF · ' . number_format($bytes / 1048576, 1, ',', '.') . ' MB'
        : 'PDF · ' . max(1, (int)round($bytes / 1024)) . ' KB';
};

if (defined('DB_DOCUMENTS')) {
    $Read->exeRead(DB_DOCUMENTS, 'WHERE doc_status = :st ORDER BY doc_order', 'st=1');

    if ($Read->getResult()) {
        ?>
        <section class="icc_transparencia" aria-labelledby="icc-transparencia-titulo">
            <h2 class="icc_transparencia_title" id="icc-transparencia-titulo">Transparência</h2>
            <ul class="icc_docs">
                <?php
                foreach ($Read->getResult() as $Doc) {
                    $DocTitle = htmlspecialchars((string)$Doc['doc_title'], ENT_QUOTES);
                    $DocFile = (string)$Doc['doc_pdf'];
                    ?>
                    <li>
                        <a class="icc_doc icon-file-pdf" href="<?= BASE; ?>/uploads/documents/<?= $DocFile; ?>"
                           download="<?= $DocTitle; ?> - ICC Serra">
                            <span class="icc_doc_title"><?= $DocTitle; ?></span>
                            <span class="icc_doc_meta"><?= $DocSize($DocFile); ?><span class="sr_only">, baixar</span></span>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </section>
        <?php
    }
}
