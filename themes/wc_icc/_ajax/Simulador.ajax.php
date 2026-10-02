<?php

use App\Helpers\Check;
use App\Helpers\DateHelper;
use App\Models\Crm;
use App\Models\Email;

require_once __DIR__ . '/../../../vendor/autoload.php';

    $jSON = [];
    $CallBack = 'Simulador';
    $Simulacao = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if (null === $Simulacao || false === $Simulacao) {
        $Simulacao = [];
    }

    //VALIDA AÇÃO
    if (isset($Simulacao['callback_action'], $Simulacao['callback']) && $Simulacao['callback'] === $CallBack) {
        //PREPARA OS DADOS
        $Case = $Simulacao['callback_action'];

        $humanA = $Simulacao['checkHuman_a'] ?? null;
        $humanB = $Simulacao['checkHuman_b'] ?? null;
        $humanCheck = $Simulacao['senderHuman'] ?? null;
        $human = (null !== $humanCheck && (int)$humanCheck === (int)$humanA + (int)$humanB);
        unset(
            $Simulacao['callback'],
            $Simulacao['callback_action'],
            $Simulacao['checkHuman_a'],
            $Simulacao['checkHuman_b'],
            $Simulacao['senderHuman'],
            $humanA,
            $humanB
        );

        //SELECIONA AÇÃO
        switch ($Case) {
            //CAPTURA DE ACORDO COM CALLBACK-ACTION
            case 'simular':

                if (array_search('', $Simulacao)) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>Info:</b> Para realizar sua simulação, por favor preencha todos os campos!',
                        E_USER_NOTICE
                    );
                    $jSON['foco'] = array_search('', $Simulacao);

                } elseif (!Check::email($Simulacao['email']) || !filter_var($Simulacao['email'], FILTER_VALIDATE_EMAIL)) {
                    $jSON['trigger'] = Check::ajaxErro(
                        "<b>Alerta:</b> Desculpe, mas o e-mail (<b style='text-transform: lowercase'>{$Simulacao['email']}</b>) não tem um formato válido!",
                        E_USER_WARNING
                    );
                    $jSON['foco'] = 'email';

                } elseif ($Simulacao['estado'] != 'RS') {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>Alerta:</b> Atualmente atendemos somente o estado do <b>Rio Grande do Sul - RS</b>, procure por um agente de microcrédito em sua região. Obrigado!',
                        E_USER_WARNING
                    );
                    $jSON['foco'] = 'estado';

                } elseif (empty($Simulacao['cb_enquadramento'])) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>Info:</b> Escolha o seu Enquadramento Tributario.',
                        E_USER_NOTICE
                    );
                    $jSON['foco'] = 'cb_enquadramento';

                } elseif (empty($Simulacao['modalidade'])) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>Info:</b> Escolha o tipo de capital.',
                        E_USER_NOTICE
                    );
                    $jSON['foco'] = 'modalidade';

                } elseif (empty($Simulacao['tipo_credito'])) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>Info:</b> Escolha a Linha de Crédito.',
                        E_USER_NOTICE
                    );
                    $jSON['foco'] = 'tipo_credito';

                } elseif (empty($Simulacao['parcelas'])) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>Info:</b> O número de Parcelas que deseja simular.',
                        E_USER_NOTICE
                    );
                    $jSON['foco'] = 'parcelas';

                } elseif ($human == false) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>Erro: </b>' . $Simulacao['nome'] . ', para enviar sua SIMULAÇÃO, realize a soma dos números corretamente.',
                        E_USER_ERROR
                    );
                    $jSON['foco'] = 'senderHuman';

                } elseif (!isset($Simulacao['privacy'])) {
                    $jSON['trigger'] = Check::ajaxErro(
                        '<b>OPPSSS</b> Você deve estar em consentimento com nossa Política de Privacidade para Receber sua Simulação. Obrigado!',
                        E_USER_ERROR
                    );
                    $jSON['foco'] = 'privacy';

                } else {
                    $Simulacao = array_map('strip_tags', array_map('trim', $Simulacao));

                    date_default_timezone_set('America/Sao_Paulo');
                    // strftime()/utf8_encode() são deprecados; DateHelper usa ICU e já devolve UTF-8.
                    $date = DateHelper::human(
                        'now',
                        "EEEE, dd 'de' MMMM 'de' yyyy 'as' HH:mm:ss 'horas.'"
                    );

                    $Simulacao["nome"] = ucwords($Simulacao["nome"]);
                    $Simulacao["cidade"] = strtoupper($Simulacao["cidade"]);
                    $Simulacao["modalidade"] = strtoupper($Simulacao["modalidade"]);
                    $Simulacao["tipo_credito"] = strtoupper($Simulacao["tipo_credito"]);
                    $Simulacao['valor'] = (float)$Simulacao['valor'];
                    $Simulacao['parcelas'] = (int)$Simulacao['parcelas'];

                    //CRIA OS COOKIES
                    //Cria o mesmo cookie acima so que irá durar 30 dias
                    setcookie('nome', $Simulacao["nome"], (time() + (3 * 24 * 3600)), "/", ".iccserra.org.br");
                    setcookie('email', $Simulacao["email"], (time() + (3 * 24 * 3600)), "/", ".iccserra.org.br");
                    setcookie('telefone', $Simulacao["telefone"], (time() + (3 * 24 * 3600)), "/", ".iccserra.org.br");
                    setcookie('cidade', $Simulacao["cidade"], (time() + (3 * 24 * 3600)), "/", ".iccserra.org.br");
                    setcookie('cidade', $Simulacao["cidade"], (time() + (3 * 24 * 3600)), "/", ".iccserra.org.br");
                    setcookie(
                        'cb_enquadramento',
                        $Simulacao["cb_enquadramento"],
                        (time() + (3 * 24 * 3600)),
                        "/",
                        ".iccserra.org.br"
                    );

                    /* CALCULA VALOR DA PARCELA
                      pmt= PVi/ 1-(1/(1+i)n)
                      pmt -= parcela
                      i= taxa de juros
                      n= prazo
                      PV= valor financiado
                     */

                    extract($Simulacao);
                    /*************************************************************
                     * ***************** MICROCRÉDITO BANRISUL ********************
                     * *********************************************************** */
                    if ($tipo_credito == "BANRISUL") {

                        if (($modalidade == "giro" && $parcelas <= 10) || ($modalidade == "fixo" && $parcelas <= 15)) {

                            // Simulação de 30% do valor com taxa juros 0.797414%
                            $valorTrinta = ($valor * 0.3);
                            $tacTrinta = ($valorTrinta * 100 / 98) - $valorTrinta;
                            $jurosTrinta = 0.00797414;
                            $taxaTrinta = (1 / pow((1 + $jurosTrinta), $parcelas));
                            $valorTrinta = ($valorTrinta * 100) / 98;
                            $valorParcelaTrinta = (($valorTrinta * $jurosTrinta) / (1 - $taxaTrinta));
                            $valorSetenta = ($valor * 0.7);

                            // Simulação de 70% com taxa juros 3.20%
                            $tacSetenta = ($valorSetenta * 100 / 97) - $valorSetenta;
                            $jurosSetenta = 0.03200000;
                            $taxaSetenta = (1 / pow((1 + $jurosSetenta), $parcelas));
                            $valorSetenta = ($valorSetenta * 100) / 97;
                            $valorParcelaSetenta = (($valorSetenta * $jurosSetenta) / (1 - $taxaSetenta));

                            $tac = $tacTrinta + $tacSetenta;
                            $valor_parcela = $valorParcelaTrinta + $valorParcelaSetenta;

                        } elseif (($modalidade == "giro" && $parcelas <= 12) || ($modalidade == "fixo" && $parcelas <= 24)) {
                            // Simulação de 30% do valor com taxa juros 0.797414%
                            $valorTrinta = ($valor * 0.3);
                            $tacTrinta = ($valorTrinta * 100 / 98) - $valorTrinta;
                            $jurosTrinta = 0.00797414;
                            $taxaTrinta = (1 / pow((1 + $jurosTrinta), $parcelas));
                            $valorTrinta = ($valorTrinta * 100) / 98;
                            $valorParcelaTrinta = (($valorTrinta * $jurosTrinta) / (1 - $taxaTrinta));
                            $valorSetenta = ($valor * 0.7);

                            // Simulação de 70% com taxa juros 3.20%
                            $tacSetenta = ($valorSetenta * 100 / 97) - $valorSetenta;
                            $jurosSetenta = 0.03900000;
                            $taxaSetenta = (1 / pow((1 + $jurosSetenta), $parcelas));
                            $valorSetenta = ($valorSetenta * 100) / 97;
                            $valorParcelaSetenta = (($valorSetenta * $jurosSetenta) / (1 - $taxaSetenta));

                            $tac = $tacTrinta + $tacSetenta;
                            $valor_parcela = $valorParcelaTrinta + $valorParcelaSetenta;

                        }

                    /******************************************************************************************************* ***************** MICROCRÉDITO TAXA 3.99% + TAC 3% (microcredito || agricola || reforma || pessoal *********************************************************************************************************/
                    } elseif ($tipo_credito == "MICROCREDITO" || $tipo_credito == "AGRICOLA" || $tipo_credito == "REFORMA" || $tipo_credito == "PESSOAL") {

                        $tac = ($valor * 0.03); // 150
                        $juros = 0.039900; // (3.99 /100)
                        $taxa = (1 / pow((1 + $juros), $parcelas));
                        $valor += $tac;
                        $valor_parcela = (($valor * $juros) / (1 - $taxa));

                    } elseif ($tipo_credito == 'MULHER') {
                        $tac = ($valor * 0.03); // 150
                        $juros = 0.039900; // (3.99 /100)
                        $taxa = (1 / pow((1 + $juros), $parcelas));
                        $valor += $tac;
                        $valor_parcela = (($valor * $juros) / (1 - $taxa));

                    }

                    /* FORMATA NUMERO PARCELA */
                    $Simulacao['valor_parcela'] = number_format($valor_parcela, 2, '.', '');
                    $Simulacao['valor'] = number_format($Simulacao['valor'], 2, '.', '');
                    $Simulacao['tac'] = number_format($tac, 2, '.', '');

                    // REGISTRA NO CRM (falha no banco não pode impedir o envio da simulação)
                    if (APP_CRM) {
                        try {
                            (new Crm())->registerSimulation($Simulacao);
                        } catch (Throwable $e) {
                            error_log('[CRM] Falha ao registrar simulação: ' . $e->getMessage());
                        }
                    }

                    $meses = [
                        "1" => "Janeiro",
                        "2" => "Fevereiro",
                        "3" => "Março",
                        "4" => "Abril",
                        "5" => "Maio",
                        "6" => "Junho",
                        "7" => "Julho",
                        "8" => "Agosto",
                        "9" => "Setembro",
                        "10" => "Outubro",
                        "11" => "Novembro",
                        "12" => "Dezembro"
                    ];

                    /* RESET DA DATA E HORA */
                    $dia = date("d", time());
                    $mes = date("m", time());
                    $ano = date("Y", time());
                    $meses[$mes] = $mes;

                    //PREPARA ENVIOS DE E-MAILS
                    $Simulacao['assunto'] = "Simulação de Crédito Via Site";
                    require_once '.././simulador/simulacao.inc.php';
                    require_once '.././simulador/agradecimento.inc.php';

                    $Email = new Email();

                    $message = str_replace(
                        '<a href="" class="no-print" onclick="cont();" title="Imprimir"><img src="https://iccserra.org.br/themes/wc_icc/images/print.gif" alt="Imprimir"/></a>',
                        '',
                        $simulacao
                    );

                    $Email->enviarMontando(
                        $Simulacao['assunto'],
                        $message,
                        $Simulacao['nome'],
                        $Simulacao['email'],
                        SITE_ADDR_NAME,
                        SITE_ADDR_EMAIL
                    );

                    if (!$Email->getError()) {
                        $Email->enviarMontando(
                            'Simulação de Crédito ICC SERRA',
                            $autoResposta . $message,
                            SITE_ADDR_NAME,
                            SITE_ADDR_EMAIL,
                            $Simulacao['nome'],
                            $Simulacao['email']
                        );

                        $jSON['trigger'] = Check::ajaxErro(
                            "<b>{$Simulacao['nome']}</b>, sua simulação foi realizada com Sucesso enviamos uma cópia para seu e-mail! Em breve um Agente de Crédito vai entrar em contato para prosseguir com seu atendimento.Obrigado!"
                        );
                        $jSON['simulacao'] = $simulacao;
                        $jSON['tracking'] = [
                            'event' => 'form_success',
                            'form_id' => 'simulador-credito',
                            'value' => $Simulacao['valor'],
                            'currency' => 'BRL',
                            'cidade' => $Simulacao['cidade'],
                            'linha_credito' => $Simulacao['tipo_credito'], // MICROCREDITO, MULHER…
                            'tipo_capital' => $Simulacao['modalidade'],  // GIRO, FIXO
                            'parcelas' => $Simulacao['parcelas']

                        ];
                    } else {
                        $jSON['trigger'] = Check::ajaxErro(
                            "<b>ERRO:</b> Desculpe, não foi possível enviar sua simulação. Entre em contato via " .
                            SITE_ADDR_EMAIL . ". Obrigado!",
                            E_USER_ERROR
                        );
                    }
                }

                break;
        }
        //RETORNA O CALLBACK
        if ($jSON) {
            echo json_encode($jSON);
        } else {
            $jSON['trigger'] = Check::ajaxErro(
                '<b>ERRO:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
                E_USER_ERROR
            );
            echo json_encode($jSON);
        }
    } else {
        //ACESSO DIRETO
        exit('<br><br><br><h1 style="text-align: center;">Acesso Restrito!</h1>');
    }

