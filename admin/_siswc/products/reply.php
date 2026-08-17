<?php

use App\Conn\Create;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;

$AdminLevel = LEVEL_WC_PRODUCTS_TRAVI;
if (!APP_PRODUCTS_TRAVI || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

// AUTO INSTANCE OBJECT CREATE
$Create ??= new Create();

// AUTO INSTANCE OBJECT UPDATE
if (empty($Update)) {
    $Update = new Update();
}

$PdtId = \filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Read->exeRead(DB_PDT_TRAVI, 'WHERE pdt_id = :id', 'id='.$PdtId);
$ProductCreate = $Read->getResult()[0];

// var_dump($ProductCreate);

unset($ProductCreate['pdt_id'], $ProductCreate['pdt_cover'], $ProductCreate['pdt_scene']);

$ProductCreate['pdt_parent'] = $PdtId;
$ProductCreate['pdt_created'] = \date('Y-m-d H:i:s');
$ProductCreate['pdt_status'] = 0;
$Create->exeCreate(DB_PDT_TRAVI, $ProductCreate);

$PDTCRTUPDATE = [
    'pdt_name' => Check::name($ProductCreate['pdt_title']).('-'.$Create->getResult()),
];
$Update->exeUpdate(DB_PDT_TRAVI, $PDTCRTUPDATE, 'WHERE pdt_id = :id', 'id='.$Create->getResult());

\header('Location: dashboard.php?wc=products/create&id='.$Create->getResult());

exit;
