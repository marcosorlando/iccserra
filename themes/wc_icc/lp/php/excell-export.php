<?php

use App\Conn\Read;

require_once __DIR__ . '/../../../../vendor/autoload.php';

$Admin = filter_input(INPUT_GET, 'a', FILTER_VALIDATE_INT);
$AdminLevel = 10;
if (empty($Admin) || $Admin < $AdminLevel) {
    exit(
        '<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400;'
        . ' background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b>'
        . ' Você não esta logado<br>ou não tem permissão para acessar essa página!</div>'
    );
}

$Read = new Read();
$Read->exeRead(DB_LEADS, 'ORDER BY lead_name DESC');
$Leads = $Read->getResult() ?? [];

// Cabeçalho do arquivo para download.
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment;filename="base-de-leads-iccserra.csv"');
header('Cache-Control: max-age=0');

$output = fopen('php://output', 'w');

// BOM UTF-8: faz o Excel reconhecer os acentos ao abrir o CSV.
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, ['Nome', 'E-mail', 'Profissão', 'Cidade', 'Conversão', 'Data'], ';');

foreach ($Leads as $Lead) {
    fputcsv(
        $output,
        [
            ucwords((string)$Lead['lead_name']),
            (string)$Lead['lead_email'],
            (string)$Lead['lead_job_title'],
            (string)$Lead['lead_city'],
            (string)$Lead['lead_conversion'],
            !empty($Lead['lead_date']) ? date('d/m/Y', strtotime((string)$Lead['lead_date'])) : '',
        ],
        ';'
    );
}

fclose($output);

exit;
