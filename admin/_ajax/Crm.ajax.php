<?php

use App\Conn\Create;
use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Crm;
use App\Models\Email;

session_start();

require __DIR__ . '/../../vendor/autoload.php';
date_default_timezone_set('America/Sao_Paulo');
$NivelAcess = LEVEL_WC_CRM;

$jSON = [];
if (
    !APP_CRM
    || !isset($_SESSION['userLogin']['user_level'])
    || (int)$_SESSION['userLogin']['user_level'] < (int)$NivelAcess
) {
    $jSON['trigger'] = Check::ajaxErro(
        '<b>OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!',
        E_USER_ERROR
    );
    echo json_encode($jSON);

    exit;
}

usleep(50000);

// DEFINE O CALLBACK E RECUPERA O POST
$jSON = [];
$CallBack = 'Crm';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (null === $PostData || false === $PostData) {
    $PostData = [];
}

// VALIDA AÇÃO
if (isset($PostData['callback_action'], $PostData['callback']) && $PostData['callback'] === $CallBack) {
    // PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    $Read ??= new Read();

    // AUTO INSTANCE OBJECT CREATE
    $Create ??= new Create();

    // AUTO INSTANCE OBJECT UPDATE
    $Update ??= new Update();

    // AUTO INSTANCE OBJECT DELETE
    $Delete ??= new Delete();

    $Crm = new Crm();
    $UserId = (int)$_SESSION['userLogin']['user_id'];

    // ELIMINA CÓDIGOS
    $PostData = array_map(static fn($v) => is_string($v) ? trim(strip_tags($v)) : $v, $PostData);

    // CLIENTE ALVO DAS AÇÕES DE ATENDIMENTO
    $ClientId = (int)($PostData['client_id'] ?? 0);
    $Client = null;
    if ($ClientId) {
        $Read->exeRead(DB_CRM_CLIENTS, 'WHERE client_id = :id', 'id=' . $ClientId);
        $Client = $Read->getResult()[0] ?? null;
    }

    $clientActions = ['stage_move', 'client_pipeline', 'client_update', 'note_add', 'email_send', 'whatsapp_log'];
    if (in_array($Case, $clientActions, true) && !$Client) {
        $jSON['trigger'] = Check::ajaxErro(
            '<b>OPPSSS:</b> Cliente não encontrado. Ele pode ter sido removido recentemente!',
            E_USER_WARNING
        );
        echo json_encode($jSON);

        exit;
    }

    // SELECIONA AÇÃO
    switch ($Case) {
        // MOVE O CARD NO FUNIL (drag and drop do quadro)
        case 'stage_move':
        case 'client_pipeline':
            $StageId = (int)($PostData['stage_id'] ?? 0);
            $Read->exeRead(DB_CRM_STAGES, 'WHERE stage_id = :id', 'id=' . $StageId);
            $Stage = $Read->getResult()[0] ?? null;

            if (!$Stage) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Etapa do funil inválida!', E_USER_WARNING);

                break;
            }

            $Changes = [];
            if ((int)$Client['stage_id'] !== $StageId) {
                $Changes['stage_id'] = $StageId;
                $Crm->log($ClientId, 'stage', "Movido para a etapa \"{$Stage['stage_name']}\".", $UserId);
            }

            if ('client_pipeline' === $Case) {
                $Status = (int)($PostData['client_status'] ?? Crm::STATUS_OPEN);
                $Status = isset(Crm::STATUS[$Status]) ? $Status : Crm::STATUS_OPEN;
                if ((int)$Client['client_status'] !== $Status) {
                    $Changes['client_status'] = $Status;
                    $Crm->log($ClientId, 'status', 'Status alterado para "' . Crm::status($Status) . '".', $UserId);
                }
            }

            if ($Changes) {
                $Update->exeUpdate(DB_CRM_CLIENTS, $Changes, 'WHERE client_id = :id', 'id=' . $ClientId);
            }

            if ('client_pipeline' === $Case) {
                $jSON['trigger'] = Check::ajaxErro('<b>OK:</b> Funil do cliente atualizado!');
                $jSON['content'] = ['.j_crm_history' => $Crm->historyHtml($ClientId)];
            } else {
                $jSON['success'] = true;
            }

            break;

        // DADOS DE CONTATO DO CLIENTE
        case 'client_update':
            $Data = [
                'client_name' => $PostData['client_name'] ?? '',
                'client_email' => mb_strtolower((string)($PostData['client_email'] ?? '')),
                'client_phone' => $PostData['client_phone'] ?? '',
                'client_city' => mb_strtoupper((string)($PostData['client_city'] ?? '')),
                'client_state' => mb_strtoupper((string)($PostData['client_state'] ?? '')),
                'client_framework' => $PostData['client_framework'] ?? '',
                'client_notes' => $PostData['client_notes'] ?? '',
            ];

            if ('' === $Data['client_name'] || '' === $Data['client_email']) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Informe ao menos o nome e o e-mail do cliente!', E_USER_NOTICE);

                break;
            }

            if (!Check::email($Data['client_email'])) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> O e-mail informado não é válido!', E_USER_WARNING);

                break;
            }

            $Read->fullRead(
                'SELECT client_id FROM ' . DB_CRM_CLIENTS . ' WHERE client_email = :email AND client_id != :id',
                http_build_query(['email' => $Data['client_email'], 'id' => $ClientId])
            );
            if ($Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b>OPPSSS:</b> Já existe outro cliente no CRM com esse e-mail!',
                    E_USER_WARNING
                );

                break;
            }

            $Update->exeUpdate(DB_CRM_CLIENTS, $Data, 'WHERE client_id = :id', 'id=' . $ClientId);
            $Crm->log($ClientId, 'update', 'Dados do cliente atualizados.', $UserId);
            $jSON['trigger'] = Check::ajaxErro('<b>OK:</b> Dados do cliente atualizados!');
            $jSON['content'] = ['.j_crm_history' => $Crm->historyHtml($ClientId)];

            break;

        // ANOTAÇÃO NO HISTÓRICO
        case 'note_add':
            if (empty($PostData['history_desc'])) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Escreva a anotação antes de salvar!', E_USER_NOTICE);

                break;
            }

            $Crm->log($ClientId, 'note', $PostData['history_desc'], $UserId);
            $jSON['content'] = ['.j_crm_history' => $Crm->historyHtml($ClientId)];
            $jSON['inpuval'] = 'null';

            break;

        // ENVIO DE E-MAIL PARA O CLIENTE
        case 'email_send':
            $Subject = (string)($PostData['email_subject'] ?? '');
            $Body = (string)($PostData['email_body'] ?? '');

            if ('' === $Subject || '' === $Body) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Informe o assunto e a mensagem do e-mail!', E_USER_NOTICE);

                break;
            }

            if (!Check::email((string)$Client['client_email'])) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> O cliente não possui um e-mail válido!', E_USER_WARNING);

                break;
            }

            $Email = new Email();
            $Email->enviarMontando(
                $Subject,
                Crm::emailHtml($Body),
                SITE_ADDR_NAME,
                SITE_ADDR_EMAIL,
                $Client['client_name'],
                $Client['client_email']
            );

            if ($Email->getError()) {
                $jSON['trigger'] = Check::ajaxErro($Email->getError(), E_USER_ERROR);

                break;
            }

            $Crm->log($ClientId, 'email', "E-mail enviado: {$Subject}\n\n{$Body}", $UserId);
            $Update->exeUpdate(
                DB_CRM_CLIENTS,
                ['client_last_contact' => date('Y-m-d H:i:s')],
                'WHERE client_id = :id',
                'id=' . $ClientId
            );
            $jSON['trigger'] = Check::ajaxErro("<b>OK:</b> E-mail enviado para {$Client['client_email']}!");
            $jSON['content'] = ['.j_crm_history' => $Crm->historyHtml($ClientId)];

            break;

        // REGISTRA A MENSAGEM ABERTA NO WHATSAPP (o envio em si acontece no app)
        case 'whatsapp_log':
            $Message = (string)($PostData['message'] ?? '');
            if ('' === $Message) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Escreva a mensagem do WhatsApp!', E_USER_NOTICE);

                break;
            }

            $Crm->log($ClientId, 'whatsapp', "WhatsApp aberto com a mensagem:\n\n{$Message}", $UserId);
            $Update->exeUpdate(
                DB_CRM_CLIENTS,
                ['client_last_contact' => date('Y-m-d H:i:s')],
                'WHERE client_id = :id',
                'id=' . $ClientId
            );
            $jSON['content'] = ['.j_crm_history' => $Crm->historyHtml($ClientId)];

            break;

        // REMOVE O CLIENTE (simulações e histórico caem em cascata)
        case 'client_delete':
            $Delete->exeDelete(DB_CRM_CLIENTS, 'WHERE client_id = :id', 'id=' . (int)($PostData['del_id'] ?? 0));
            $jSON['redirect'] = 'dashboard.php?wc=crm/home';

            break;

        // ETAPAS DO FUNIL
        case 'stage_create':
            $Name = (string)($PostData['stage_name'] ?? '');
            $Read->fullRead('SELECT COALESCE(MAX(stage_order), 0) + 1 AS next_order FROM ' . DB_CRM_STAGES);
            $NextOrder = (int)$Read->getResult()[0]['next_order'];

            $Create->exeCreate(DB_CRM_STAGES, [
                'stage_name' => '' !== $Name ? $Name : "Etapa {$NextOrder}",
                'stage_color' => preg_match('/^#[0-9a-fA-F]{6}$/', (string)($PostData['stage_color'] ?? '')) ? $PostData['stage_color'] : '#5d6d7e',
                'stage_order' => $NextOrder,
            ]);
            $jSON['redirect'] = 'dashboard.php?wc=crm/etapas';

            break;

        case 'stage_update':
            $StageId = (int)($PostData['stage_id'] ?? 0);
            $Name = (string)($PostData['stage_name'] ?? '');
            if ('' === $Name) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Informe o nome da etapa!', E_USER_NOTICE);

                break;
            }

            $Update->exeUpdate(DB_CRM_STAGES, [
                'stage_name' => $Name,
                'stage_color' => preg_match('/^#[0-9a-fA-F]{6}$/', (string)($PostData['stage_color'] ?? '')) ? $PostData['stage_color'] : '#5d6d7e',
            ], 'WHERE stage_id = :id', 'id=' . $StageId);
            $jSON['trigger'] = Check::ajaxErro("<b>OK:</b> Etapa \"{$Name}\" atualizada!");

            break;

        case 'stage_up':
        case 'stage_down':
            $Read->fullRead('SELECT stage_id FROM ' . DB_CRM_STAGES . ' ORDER BY stage_order ASC, stage_id ASC');
            $Order = array_map('intval', array_column($Read->getResult() ?? [], 'stage_id'));
            $Pos = array_search((int)($PostData['key'] ?? 0), $Order, true);
            $Swap = (false === $Pos ? false : ('stage_up' === $Case ? $Pos - 1 : $Pos + 1));

            if (false !== $Pos && isset($Order[$Swap])) {
                [$Order[$Pos], $Order[$Swap]] = [$Order[$Swap], $Order[$Pos]];
                foreach ($Order as $Index => $Id) {
                    $Update->exeUpdate(DB_CRM_STAGES, ['stage_order' => $Index + 1], 'WHERE stage_id = :id', 'id=' . $Id);
                }
            }
            $jSON['redirect'] = 'dashboard.php?wc=crm/etapas';

            break;

        case 'stage_delete':
            $StageId = (int)($PostData['del_id'] ?? 0);
            $Read->fullRead(
                'SELECT stage_id, stage_name FROM ' . DB_CRM_STAGES . ' WHERE stage_id != :id ORDER BY stage_order ASC, stage_id ASC LIMIT 1',
                'id=' . $StageId
            );
            $Target = $Read->getResult()[0] ?? null;

            if (!$Target) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> O funil precisa ter pelo menos uma etapa!', E_USER_WARNING);

                break;
            }

            // CLIENTES DA ETAPA REMOVIDA VÃO PARA A PRIMEIRA ETAPA RESTANTE
            $Read->fullRead('SELECT client_id FROM ' . DB_CRM_CLIENTS . ' WHERE stage_id = :id', 'id=' . $StageId);
            foreach ($Read->getResult() ?? [] as $Moved) {
                $Crm->log((int)$Moved['client_id'], 'stage', "Etapa anterior removida. Movido para \"{$Target['stage_name']}\".", $UserId);
            }
            $Update->exeUpdate(DB_CRM_CLIENTS, ['stage_id' => (int)$Target['stage_id']], 'WHERE stage_id = :id', 'id=' . $StageId);
            $Delete->exeDelete(DB_CRM_STAGES, 'WHERE stage_id = :id', 'id=' . $StageId);

            // RENUMERA A ORDEM DAS ETAPAS RESTANTES
            $Read->fullRead('SELECT stage_id FROM ' . DB_CRM_STAGES . ' ORDER BY stage_order ASC, stage_id ASC');
            foreach ($Read->getResult() ?? [] as $Index => $Rest) {
                $Update->exeUpdate(DB_CRM_STAGES, ['stage_order' => $Index + 1], 'WHERE stage_id = :id', 'id=' . (int)$Rest['stage_id']);
            }
            $jSON['redirect'] = 'dashboard.php?wc=crm/etapas';

            break;

        // MENSAGENS PRÉ-DEFINIDAS
        case 'template_save':
            $TemplateId = (int)($PostData['template_id'] ?? 0);
            $Data = [
                'template_type' => 'email' === ($PostData['template_type'] ?? '') ? 'email' : 'whatsapp',
                'template_title' => (string)($PostData['template_title'] ?? ''),
                'template_subject' => (string)($PostData['template_subject'] ?? ''),
                'template_body' => (string)($PostData['template_body'] ?? ''),
                'template_status' => empty($PostData['template_status']) ? 0 : 1,
            ];

            if ('' === $Data['template_title'] || '' === $Data['template_body']) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Informe o título e o texto da mensagem!', E_USER_NOTICE);

                break;
            }

            if ('email' === $Data['template_type'] && '' === $Data['template_subject']) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Mensagens de e-mail precisam de assunto!', E_USER_NOTICE);

                break;
            }

            if ('whatsapp' === $Data['template_type']) {
                $Data['template_subject'] = null;
            }

            if ($TemplateId) {
                $Update->exeUpdate(DB_CRM_TEMPLATES, $Data, 'WHERE template_id = :id', 'id=' . $TemplateId);
            } else {
                $Create->exeCreate(DB_CRM_TEMPLATES, $Data);
            }
            $jSON['redirect'] = 'dashboard.php?wc=crm/mensagens';

            break;

        case 'template_delete':
            $Delete->exeDelete(DB_CRM_TEMPLATES, 'WHERE template_id = :id', 'id=' . (int)($PostData['del_id'] ?? 0));
            $jSON['redirect'] = 'dashboard.php?wc=crm/mensagens';

            break;
    }

    // RETORNA O CALLBACK
    if ([] !== $jSON) {
        echo json_encode($jSON);
    } else {
        $jSON['trigger'] = Check::ajaxErro(
            '<b>OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
            E_USER_ERROR
        );
        echo json_encode($jSON);
    }
} else {
    // ACESSO DIRETO
    exit('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
}
