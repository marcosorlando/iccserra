<?php

use App\Conn\Create;
use App\Conn\Read;

/**
 * Replica um documento existente, gerando um novo registro em rascunho.
 */

$AdminLevel = LEVEL_WC_DOCUMENTS;
if (!APP_DOCUMENTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

// AUTO INSTANCE OBJECT CREATE
$Create ??= new Create();

$docId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Read->exeRead(DB_DOCUMENTS, 'WHERE doc_id = :id', 'id=' . $docId);

$docCreate = $Read->getResult()[0] ?? null;
if (!$docCreate) {
    $_SESSION['trigger_controll'] = sprintf(
        '<b>OPPSS %s</b>, você tentou replicar um documento que não existe ou que foi removido recentemente!',
        $Admin['user_name']
    );
    header('Location: dashboard.php?wc=documents/home');

    exit;
}

unset($docCreate['doc_id'], $docCreate['doc_pdf'], $docCreate['doc_cover']);

$docCreate['doc_create'] = date('Y-m-d H:i:s');
$docCreate['doc_status'] = 0;
$docCreate['doc_name'] = null;

$Create->exeCreate(DB_DOCUMENTS, $docCreate);

header('Location: dashboard.php?wc=documents/create&id=' . $Create->getResult());

exit;
