<?php

use App\Conn\Create;
use App\Helpers\Check;
use App\Models\Email;
use App\View\Template;

require_once __DIR__ . '/../../../vendor/autoload.php';

// DEFINE O CALLBACK E RECUPERA O POST
	$jSON = [];
	$CallBack = 'Ouvidoria';
	$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	if (null === $PostData || false === $PostData) {
		$PostData = [];
	}

// VALIDA AÇÃO
	if (isset($PostData['callback_action'], $PostData['callback']) && $PostData['callback'] === $CallBack) {
		//PREPARA OS DADOS
		$Case = $PostData['callback_action'];
		unset($PostData['callback'], $PostData['callback_action']);

		$humanA = $PostData['checkHuman_a'] ?? null;
		$humanB = $PostData['checkHuman_b'] ?? null;
		$humanCheck = $PostData['senderHuman'] ?? null;
		$human = (null !== $humanCheck && (int)$humanCheck === (int)$humanA + (int)$humanB);
		unset($PostData['checkHuman_a'], $PostData['checkHuman_b'], $PostData['senderHuman'], $humanA, $humanB);

		//ELIMINA CÓDIGOS
		$PostData = array_map(static fn($v) => is_string($v) ? strip_tags($v) : $v, $PostData);

		//SELECIONA AÇÃO©
		switch ($Case) {
			//CAPTURA DE ACORDO COM CALLBACK-ACTION
			case 'manager':

				if (array_search('', $PostData) &&
					array_search('', $PostData) != 'first_name' &&
					array_search('', $PostData) != 'last_name'

				) {
					$jSON['trigger'] = Check::ajaxErro('<b class="fal fa-info-circle"> OOPS! </b> Preencha todos os campos obrigatórios e Envie novamente!',
						E_USER_NOTICE);

				} elseif (!Check::email($PostData['email']) || !filter_var($PostData['email'], FILTER_VALIDATE_EMAIL)) {
					$jSON['trigger'] = Check::ajaxErro('<b class="fal fa-envelope"> OOPS! </b> E-mail informado não é válido!',
						E_USER_ERROR);

				} elseif ($human == false) {
					$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Para enviar sua <b>' . $PostData['sector'] .
						'</b>, realize a soma dos números corretamente.', E_USER_ERROR);
					$jSON['foco'] = 'senderHuman';

				} else {
					$PostData['privacy'] = $PostData['privacy'] ? 1 : 0;
					$PostData['status'] = 1;

					/*REGISTRA NO BANCO DE DADOS*/
					$Create = new Create();
					$Create->exeCreate(DB_OUVIDORIA, $PostData);

					if ($Create->getResult()) {
						$jSON['trigger'] = Check::ajaxErro("Registro enviado com sucesso!");
					}

					$PostData['full_name'] = !empty($PostData['first_name']) ? "{$PostData['first_name']} {$PostData['last_name']}" : 'ANÔNIMO(A)';

					/*ENVIA E-MAILS PARA 2 PONTAS*/
					$arrayData = $PostData;
					$arrayData['INCLUDE_PATH'] = INCLUDE_PATH;
					$arrayData['SITE_NAME'] = SITE_NAME;
					$arrayData['SITE_ADDR_NAME'] = SITE_ADDR_NAME;
					$arrayData['SITE_ADDR_PHONE_A'] = SITE_ADDR_PHONE_A;
					$arrayData['SITE_ADDR_SITE'] = SITE_ADDR_SITE;
					$arrayData['BASE'] = BASE;
					$arrayData['saudacao'] = Check::salutation();

					if ($PostData['sector'] == 'DENÚNCIA') {
						$arrayData['SITE_ADDR_EMAIL'] = SITE_ADDR_EMAIL;
					} else {
						$arrayData['SITE_ADDR_EMAIL'] = SITE_ADDR_EMAIL;
					}

					$MailContent = Template::setTemplate(Template::getTemplate('ouvidoria_mail.html',
						__DIR__ . '/../templates/'),
						$arrayData);

					$Email = new Email();
					$Email->enviarMontando(
						"Nova {$PostData['sector']} recebida",
						$MailContent,
						$PostData['full_name'],
						$PostData['email'],
						SITE_ADDR_NAME,
						$arrayData['SITE_ADDR_EMAIL']);

					if (!$Email->getError()) {
						$MailConfirmation = Template::setTemplate(Template::getTemplate('ouvidoria_return_mail.html',
							__DIR__ . '/../templates/'), $arrayData);

						$ResponseEmail = new Email();

						$ResponseEmail->enviarMontando(
							'Confirmação de recebimento',
							$MailConfirmation,
							MAIL_SENDER,
							MAIL_USER,
							$PostData['full_name'],
							$PostData['email']);

					} else {
						$jSON['trigger'] = Check::ajaxErro("Desculpe, não foi possível enviar sua {$PostData['sector']}. Entre em contato via E-mail: " . $arrayData['SITE_ADDR_EMAIL'],
							E_USER_ERROR);
					}

				}
				break;

		}

		//RETORNA O CALLBACK
		if ($jSON) {
			echo json_encode($jSON);
		} else {
			$jSON['trigger'] = Check::ajaxErro('<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
				E_USER_ERROR);
			echo json_encode($jSON);
		}
	} else {
		//ACESSO DIRETO
		die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
	}
