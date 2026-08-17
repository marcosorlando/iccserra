<?php
    /**
     * Created by PhpStorm.
     * User: marcosmoreira
     * Date: 11/04/19
     * Time: 15:17
     */
    
 $AdminLevel = LEVEL_WC_DOCUMENTS;
if (!APP_DOCUMENTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

// AUTO INSTANCE OBJECT UPDATE
if (empty($Update)):
    $Update = new Update;
endif;

$docId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Read->ExeRead(DB_DOCUMENTS, "WHERE doc_id = :id", "id={$docId}");
$docCreate = $Read->getResult()[0];

unset($docCreate['doc_id'], $docCreate['doc_pdf'], $docCreate['doc_cover']);

$docCreate['doc_create'] = date('Y-m-d H:i:s');
$docCreate['doc_status'] = 0;
$docCreate['doc_name'] = null;


$Create->ExeCreate(DB_DOCUMENTS, $docCreate);

/*$docRepplyUpdate = ['doc_name' => Check::Name($docCreate['doc_title']) . "-{$Create->getResult()}"];
$Update->ExeUpdate(DB_DOCUMENTS, $docRepplyUpdate, "WHERE doc_id = :id", "id={$Create->getResult()}");*/

header("Location: dashboard.php?wc=documents/create&id={$Create->getResult()}");
exit;
