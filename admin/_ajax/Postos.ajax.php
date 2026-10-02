<?php

use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;

session_start();

require __DIR__ . '/../../vendor/autoload.php';
$NivelAcess = LEVEL_WC_SERVICES_STATIONS;

$jSON = [];
if (
    !APP_SERVICES_STATIONS
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
$CallBack = 'Postos';
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

    // SELECIONA AÇÃO
    switch ($Case) {
        case 'manager':
            $StationId = (int)($PostData['id'] ?? 0);
            unset($PostData['id']);

            $RequiredFields = [
                'ss_name',
                'ss_phone',
                'ss_zipcode',
                'ss_email',
                'ss_street',
                'ss_number',
                'ss_state',
                'ss_city',
            ];
            foreach ($RequiredFields as $Field) {
                if (!isset($PostData[$Field]) || '' === trim((string)$PostData[$Field])) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b class="icon-warning">ERRO AO CADASTRAR:</b> Favor preencha todos os campos obrigatórios!',
                        E_USER_ERROR
                    );
                    $jSON['error'] = true;

                    break 2;
                }
            }

            if (!Check::email($PostData['ss_email']) || !filter_var($PostData['ss_email'], FILTER_VALIDATE_EMAIL)) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b class="icon-warning">OPPSSS:</b> O e-mail informado não é válido!',
                    E_USER_NOTICE
                );

                break;
            }

            $Read->exeRead(DB_SERVICES_STATIONS, 'WHERE id = :id', 'id=' . $StationId);
            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b class="icon-warning">OPPSSS:</b> O posto de atendimento informado não existe ou foi removido!',
                    E_USER_ERROR
                );

                break;
            }

            $PostData['ss_state'] = strtoupper(substr($PostData['ss_state'], 0, 2));
            $PostData['ss_complement'] = (!empty($PostData['ss_complement']) ? $PostData['ss_complement'] : null);
            $PostData['ss_pinmap'] = (!empty($PostData['ss_pinmap']) ? $PostData['ss_pinmap'] : null);
            $PostData['status'] = (!empty($PostData['status']) ? 1 : 0);
            $PostData['updated_at'] = date('Y-m-d H:i:s');
            unset($PostData['public']);

            $Update->exeUpdate(DB_SERVICES_STATIONS, $PostData, 'WHERE id = :id', 'id=' . $StationId);
            $jSON['trigger'] = Check::ajaxErro(
                sprintf(
                    "<b class='icon-checkmark'>Tudo certo %s</b>: O posto de atendimento foi atualizado com sucesso!",
                    $_SESSION['userLogin']['user_name']
                )
            );

            break;

        case 'delete':
            $StationId = (int)($PostData['del_id'] ?? 0);
            $Delete->exeDelete(DB_SERVICES_STATIONS, 'WHERE id = :id', 'id=' . $StationId);
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
