<?php
session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_SERVICES_STATIONS;

if (!APP_SERVICES_STATIONS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Postos';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;

    // AUTO INSTANCE OBJECT UPDATE
    if (empty($Update)):
        $Update = new Update;
    endif;

    // AUTO INSTANCE OBJECT DELETE
    if (empty($Delete)):
        $Delete = new Delete;
    endif;

    $PostData = array_map('strip_tags', $PostData);

    //SELECIONA AÇÃO
    switch ($Case):
        case 'manager':
            $StationId = (!empty($PostData['id']) ? (int) $PostData['id'] : null);
            unset($PostData['id']);

            $RequiredFields = ['ss_name', 'ss_phone', 'ss_zipcode', 'ss_email', 'ss_street', 'ss_number', 'ss_state', 'ss_city'];
            foreach ($RequiredFields as $Field):
                if (!isset($PostData[$Field]) || $PostData[$Field] === ''):
                    $jSON['trigger'] = AjaxErro('<b class="icon-warning">ERRO AO CADASTRAR:</b> Favor preencha todos os campos obrigatórios!', E_USER_ERROR);
                    $jSON['error'] = true;
                    break 2;
                endif;
            endforeach;

            if (!Check::Email($PostData['ss_email']) || !filter_var($PostData['ss_email'], FILTER_VALIDATE_EMAIL)):
                $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> O e-mail informado não é válido!', E_USER_NOTICE);
                break;
            endif;

            $Read->ExeRead(DB_SERVICES_STATIONS, "WHERE id = :id", "id={$StationId}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> O posto de atendimento informado não existe ou foi removido!', E_USER_ERROR);
                break;
            endif;

            $PostData['ss_state'] = strtoupper(substr($PostData['ss_state'], 0, 2));
            $PostData['ss_complement'] = (!empty($PostData['ss_complement']) ? $PostData['ss_complement'] : null);
            $PostData['ss_pinmap'] = (!empty($PostData['ss_pinmap']) ? $PostData['ss_pinmap'] : null);
            $PostData['status'] = (!empty($PostData['status']) ? 1 : 0);
            $PostData['updated_at'] = date('Y-m-d H:i:s');

            $Update->ExeUpdate(DB_SERVICES_STATIONS, $PostData, "WHERE id = :id", "id={$StationId}");
            $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>Tudo certo {$_SESSION['userLogin']['user_name']}</b>: O posto de atendimento foi atualizado com sucesso!");
            break;

        case 'delete':
            $StationId = (int) $PostData['del_id'];
            $Delete->ExeDelete(DB_SERVICES_STATIONS, "WHERE id = :id", "id={$StationId}");
            $jSON['success'] = true;
            break;
    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
