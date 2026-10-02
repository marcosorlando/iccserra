<?php

use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Upload;

session_start();

require __DIR__ . '/../../vendor/autoload.php';
$NivelAcess = LEVEL_WC_SLIDES;

$jSON = [];
if (
    !APP_SLIDE
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
$CallBack = 'Slides';
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

    // SELECIONA AÇÃO
    switch ($Case) {
        // GERENCIA
        case 'manager':
            $SlideId = (int)($PostData['slide_id'] ?? 0);
            $SlideEnd = (!empty($PostData['slide_end']) ? $PostData['slide_end'] : null);
            $Image = (!empty($_FILES['slide_image']['tmp_name']) ? $_FILES['slide_image'] : null);

            unset($PostData['slide_id'], $PostData['slide_end'], $PostData['slide_image']);
            $Read->fullRead('SELECT slide_image FROM ' . DB_SLIDES . ' WHERE slide_id = :id', 'id=' . $SlideId);
            $CurrentImage = $Read->getResult()[0]['slide_image'] ?? null;

            if (empty($Image) && empty($CurrentImage)) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b class="icon-warning">ERRO AO CADASTRAR:</b> Favor envie uma imagem de destaque nas medidas de ' . SLIDE_W . 'x' . SLIDE_H . 'px!',
                    E_USER_ERROR
                );
            } elseif (in_array('', $PostData, true)) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b class="icon-warning">ERRO AO CADASTRAR:</b> Para atualizar o destaque, favor preencha todos os campos!',
                    E_USER_ERROR
                );
                $jSON['error'] = true;
            } else {
                $PostData['slide_date'] = date('Y-m-d H:i:s');
                $PostData['slide_start'] = Check::data((string)($PostData['slide_start'] ?? ''));
                $PostData['slide_end'] = (!empty($SlideEnd) ? Check::data($SlideEnd) : null);
                $PostData['slide_status'] = (!empty($PostData['slide_status']) ? $PostData['slide_status'] : '0');

                if (!empty($Image)) {
                    if (
                        !empty($CurrentImage)
                        && file_exists('../../uploads/' . $CurrentImage)
                        && !is_dir('../../uploads/' . $CurrentImage)
                    ) {
                        unlink('../../uploads/' . $CurrentImage);
                    }
                    $Upload = new Upload('../../uploads/');
                    $Upload->image($Image, Check::name($PostData['slide_title'] ?? 'slide'), SLIDE_W, 'slides');
                    $PostData['slide_image'] = $Upload->getResult();
                }

                $Update->exeUpdate(DB_SLIDES, $PostData, 'WHERE slide_id = :id', 'id=' . $SlideId);
                $jSON['trigger'] = Check::ajaxErro(
                    sprintf(
                        "<b class='icon-checkmark'>Tudo certo %s</b>: O conteúdo em destaque foi atualizado com sucesso. E será exibido nas datas cadastradas!",
                        $_SESSION['userLogin']['user_name']
                    )
                );
            }

            break;

        // DELETA
        case 'delete':
            $SlideId = (int)($PostData['del_id'] ?? 0);
            $Read->fullRead('SELECT slide_image FROM ' . DB_SLIDES . ' WHERE slide_id = :id', 'id=' . $SlideId);
            $SlideImage = $Read->getResult()[0]['slide_image'] ?? null;
            if ($SlideImage && file_exists('../../uploads/' . $SlideImage) && !is_dir('../../uploads/' . $SlideImage)) {
                unlink('../../uploads/' . $SlideImage);
            }

            $Delete->exeDelete(DB_SLIDES, 'WHERE slide_id = :id', 'id=' . $SlideId);
            $jSON['success'] = true;

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
