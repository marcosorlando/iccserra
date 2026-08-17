<?php

use App\Conn\Read;

$Read ??= new Read();
?>
<div class='docs'>
    <?php
        // TODO: DB_DOCUMENTS não é definida pelo ConfigLoader e a tabela não consta no schema.
        // Registrar a constante em ConfigLoader::defineDatabaseTableConstants() (e criar a tabela)
        // para reativar o bloco. Sem a guarda abaixo esta página quebra com Undefined constant.
        if (defined('DB_DOCUMENTS')) {
            $Read->exeRead(DB_DOCUMENTS, 'WHERE doc_status = :st ORDER BY doc_order', 'st=1');

            if ($Read->getResult()) {
                foreach ($Read->getResult() as $Doc) {
                    extract($Doc);
                    echo "
                    <div class='doc'>
                        <p>{$doc_title}</p>
                        <p><a href='" . BASE . "/uploads/documents/{$doc_pdf}' title='' class='btn_docs' style='background: {$doc_btn_bg_color}; color:{$doc_btn_text_color};' download='{$doc_title} - Icc Serra' onmouseover=\"this.style.background='{$doc_btn_bg_color_hover}'\" onmouseout=\"this.style.background='{$doc_btn_bg_color}'\">{$doc_btn_text}</a></p>
                    </div>
                ";
                }
            }
        }
    ?>
</div>
