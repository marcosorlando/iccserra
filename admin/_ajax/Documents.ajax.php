<?php

use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Upload;

session_start();

require __DIR__ . '/../../vendor/autoload.php';
$NivelAcess = LEVEL_WC_DOCUMENTS;

$jSON = [];
if (
    !APP_DOCUMENTS
    || !isset($_SESSION['userLogin']['user_level'])
    || (int)$_SESSION['userLogin']['user_level'] < (int)$NivelAcess
) {
    $jSON['trigger'] = Check::ajaxErro(
        '<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!',
        E_USER_ERROR
    );
    echo json_encode($jSON);

    exit;
}

usleep(50000);

// DEFINE O CALLBACK E RECUPERA O POST
$jSON = [];
$CallBack = 'Documents';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];

// VALIDA AÇÃO
if (isset($PostData['callback_action'], $PostData['callback']) && $PostData['callback'] === $CallBack) {
    // PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    $Read ??= new Read();
    // AUTO INSTANCE OBJECT UPDATE
    $Update ??= new Update();
    // AUTO INSTANCE OBJECT DELETE
    $Delete ??= new Delete();

    $PostData = array_map(static fn($v) => is_string($v) ? strip_tags($v) : $v, $PostData);
    $UploadDir = '../../uploads/documents/';

    // SELECIONA AÇÃO
    switch ($Case) {
        // DELETE
        case 'delete':
            $DocId = (int)($PostData['del_id'] ?? 0);
            $Read->fullRead('SELECT doc_cover, doc_pdf FROM ' . DB_DOCUMENTS . ' WHERE doc_id = :id', 'id=' . $DocId);

            foreach ($Read->getResult() ?? [] as $DocFiles) {
                foreach (['doc_cover', 'doc_pdf'] as $Field) {
                    $FileRemove = $UploadDir . $DocFiles[$Field];
                    if (!empty($DocFiles[$Field]) && file_exists($FileRemove) && !is_dir($FileRemove)) {
                        unlink($FileRemove);
                    }
                }
            }

            $Delete->exeDelete(DB_DOCUMENTS, 'WHERE doc_id = :id', 'id=' . $DocId);
            $jSON['success'] = true;

            break;

        // MANAGER
        case 'manage':
            $DocId = (int)($PostData['doc_id'] ?? 0);
            unset($PostData['doc_id'], $PostData['public']);

            // VALIDANDO CAMPOS VAZIOS
            $RequiredFields = [
                'doc_title' => 'Por favor, informe o Título do Documento.',
                'doc_btn_bg_color' => 'Por favor, informe a cor de fundo do botão de download.',
                'doc_btn_bg_color_hover' => 'Por favor, informe a cor de fundo do botão de download ao passar mouse em cima.',
                'doc_btn_text_color' => 'Por favor, informe a cor do texto do botão.',
                'doc_btn_text' => 'Por favor, informe o texto do botão de download.',
                'doc_category' => 'Por favor, informe a categoria do documento.',
            ];
            foreach ($RequiredFields as $Field => $Message) {
                if (empty($PostData[$Field])) {
                    $jSON['field'] = $Field;
                    $jSON['trigger'] = Check::ajaxErro("<b class='icon-image'>Ooops!</b> {$Message}", E_USER_NOTICE);

                    break 2;
                }
            }

            $Read->exeRead(DB_DOCUMENTS, 'WHERE doc_id = :id', 'id=' . $DocId);
            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b class="icon-warning">OPPSSS:</b> O documento informado não existe ou foi removido!',
                    E_USER_ERROR
                );

                break;
            }
            $ThisDoc = $Read->getResult()[0];

            $PdfFile = (!empty($_FILES['doc_pdf']['tmp_name']) ? $_FILES['doc_pdf'] : null);
            $CoverFile = (!empty($_FILES['doc_cover']['tmp_name']) ? $_FILES['doc_cover'] : null);
            $HasPdf = !empty($ThisDoc['doc_pdf']) && file_exists($UploadDir . $ThisDoc['doc_pdf']);

            if (!$PdfFile && !$HasPdf) {
                $jSON['trigger'] = Check::ajaxErro(
                    sprintf(
                        "<b class='icon-file-pdf'>Doc.: (PDF):</b> Olá %s, selecione um arquivo PDF para enviar como documento para Download.",
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
                $jSON['field'] = 'doc_pdf';

                break;
            }

            $PostData['doc_status'] = (!empty($PostData['doc_status']) ? '1' : '0');
            $PostData['doc_name'] = Check::name(
                !empty($PostData['doc_name']) ? $PostData['doc_name'] : $PostData['doc_title']
            );

            // UPLOAD PDF
            unset($PostData['doc_pdf'], $PostData['doc_cover']);
            if ($PdfFile) {
                $Upload = new Upload($UploadDir);
                $Upload->file($PdfFile, $PostData['doc_name'] . '-' . time());

                if (!$Upload->getResult()) {
                    $jSON['trigger'] = Check::ajaxErro(
                        sprintf(
                            "<b class='icon-file-pdf'>ERRO AO ENVIAR PDF:</b> Olá %s, selecione um arquivo em PDF para enviar como documento!",
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );

                    break;
                }

                if ($HasPdf && !is_dir($UploadDir . $ThisDoc['doc_pdf'])) {
                    unlink($UploadDir . $ThisDoc['doc_pdf']);
                }
                $PostData['doc_pdf'] = $Upload->getResult();
            }

            // UPLOAD BACKGROUND-IMAGE
            if ($CoverFile) {
                $Upload = new Upload($UploadDir);
                $Upload->image($CoverFile, $PostData['doc_name'] . '-cover');

                if (!$Upload->getResult()) {
                    $jSON['trigger'] = Check::ajaxErro(
                        sprintf(
                            "<b class='icon-image'>ERRO AO ENVIAR CAPA:</b> Olá %s, selecione uma imagem JPG ou PNG para enviar como capa!",
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );

                    break;
                }

                $OldCover = $UploadDir . $ThisDoc['doc_cover'];
                if (!empty($ThisDoc['doc_cover']) && file_exists($OldCover) && !is_dir($OldCover)) {
                    unlink($OldCover);
                }
                $PostData['doc_cover'] = $Upload->getResult();
            }

            $Read->fullRead(
                'SELECT doc_name FROM ' . DB_DOCUMENTS . ' WHERE doc_name = :nm AND doc_id != :id',
                'nm=' . urlencode($PostData['doc_name']) . '&id=' . $DocId
            );
            if ($Read->getResult()) {
                $PostData['doc_name'] = $PostData['doc_name'] . '-' . $DocId;
            }

            $jSON['name'] = $PostData['doc_name'];
            $jSON['view'] = BASE . '/' . $PostData['doc_name'];

            $Update->exeUpdate(DB_DOCUMENTS, $PostData, 'WHERE doc_id = :id', 'id=' . $DocId);
            $jSON['trigger'] = Check::ajaxErro("<b class='icon-checkmark'>Documento atualizado com sucesso!</b>");

            break;

        case 'docs_order':
            if (!empty($PostData['Data']) && is_array($PostData['Data'])) {
                foreach ($PostData['Data'] as $RE) {
                    $Update->exeUpdate(
                        DB_DOCUMENTS,
                        ['doc_order' => (int)$RE[1]],
                        'WHERE doc_id = :doc',
                        'doc=' . (int)$RE[0]
                    );
                }
                $jSON['sucess'] = true;
            }

            break;

        case 'category_add':
            $CatId = (int)($PostData['category_id'] ?? 0);
            unset($PostData['category_id']);

            $PostData['category_name'] = Check::name((string)($PostData['category_title'] ?? ''));

            $Read->fullRead(
                'SELECT category_id FROM ' . DB_DOCS_CATEGORIES . ' WHERE category_name = :cn AND category_id != :ci',
                'cn=' . urlencode($PostData['category_name']) . '&ci=' . $CatId
            );
            if ($Read->getResult()) {
                $PostData['category_name'] = $PostData['category_name'] . '-' . $CatId;
            }

            $Update->exeUpdate(DB_DOCS_CATEGORIES, $PostData, 'WHERE category_id = :id', 'id=' . $CatId);
            $jSON['trigger'] = Check::ajaxErro(
                sprintf(
                    "<b class='icon-checkmark'>TUDO CERTO: </b> A categoria <b>%s</b> foi atualizada com sucesso!",
                    $PostData['category_title'] ?? ''
                )
            );

            break;

        case 'category_remove':
            $CatId = (int)($PostData['del_id'] ?? 0);
            $Read->fullRead('SELECT doc_id FROM ' . DB_DOCUMENTS . ' WHERE doc_category = :cat', 'cat=' . $CatId);

            if ($Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    sprintf(
                        "<b class='icon-warning'>%d DOC(S): </b> Olá %s, não é possível remover categorias quando existem documentos cadastrados na mesma! Exclua o documento ou mude sua categoria e tente novamente.",
                        $Read->getRowCount(),
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Delete->exeDelete(DB_DOCS_CATEGORIES, 'WHERE category_id = :cat', 'cat=' . $CatId);
                $jSON['success'] = true;
            }

            break;
    }

    // RETORNA O CALLBACK
    if ([] !== $jSON) {
        echo json_encode($jSON);
    } else {
        $jSON['trigger'] = Check::ajaxErro(
            '<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
            E_USER_ERROR
        );
        echo json_encode($jSON);
    }
} else {
    // ACESSO DIRETO
    exit('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
}
