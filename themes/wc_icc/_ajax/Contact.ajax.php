<?php

use App\Conn\Create;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Email;
use App\Models\Upload;
use App\View\Template;

require_once __DIR__ . '/../../../vendor/autoload.php';

// DEFINE O CALLBACK E RECUPERA O POST
$jSON = [];
$CallBack = 'Contact';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (null === $PostData || false === $PostData) {
	$PostData = [];
}

// VALIDA AÇÃO
if (isset($PostData['callback_action'], $PostData['callback']) && $PostData['callback'] === $CallBack) {
	// PREPARA OS DADOS
	$Case = $PostData['callback_action'];
	unset($PostData['callback'], $PostData['callback_action']);

	$humanA = $PostData['checkHuman_a'] ?? null;
	$humanB = $PostData['checkHuman_b'] ?? null;
	$humanCheck = $PostData['senderHuman'] ?? null;
	$human = (null !== $humanCheck && (int)$humanCheck === (int)$humanA + (int)$humanB);
	unset($PostData['checkHuman_a'], $PostData['checkHuman_b'], $PostData['senderHuman'], $humanA, $humanB);

	// ELIMINA CÓDIGOS
	$PostData = array_map(static fn($v) => is_string($v) ? strip_tags($v) : $v, $PostData);

	//SELECIONA AÇÃO
	switch ($Case) {
		case 'contact':
			//VALIDAÇÃO
			if (array_search('', $PostData)) {
				$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Para enviar sua mensagem, por favor preencha todos os campos obrigatórios! (*)',
					E_USER_NOTICE);
				$jSON['foco'] = array_search('', $PostData);

			} elseif (!Check::email($PostData['email']) || !filter_var($PostData['email'], FILTER_VALIDATE_EMAIL)) {
				$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Desculpe, mas o e-mail que você informou não tem um formato válido!',
					E_USER_ERROR);
				$jSON['foco'] = 'email';

			} elseif (!isset($PostData['privacy'])) {
				$jSON['trigger'] = Check::ajaxErro('<b>ERRO:</b> Para enviar seu contato, você deve consentir com nossa Política de Privacidade.',
					E_USER_ERROR);
				$jSON['foco'] = 'privacy';

			} elseif ($human == false) {
				$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b>' . $PostData['nome'] . ', para enviar sua MENSAGEM, realize a soma dos números corretamente.',
					E_USER_ERROR);
				$jSON['foco'] = 'senderHuman';

				//EXECUTA ENVIOS
			} else {
				$MailContent = '
                        <table width="550" style="width:550px; font-family: Verdana, sans-serif">
                         <tr><td >
                            <p><b>Assunto: </b> ' . $PostData['assunto'] . ' </p>
                           <p><b>MENSAGEM:</b><br>' . $PostData['mensagem'] . '</p>
                           <p>Nome do Remetente: ' . $PostData['nome'] . ' </p>
                           <p>Residente em ' . $PostData['cidade'] . ' - ' . $PostData['estado'] . '</p>
                           <p>Telefone: ' . $PostData['telefone'] . ' </p>
                           <p>E-mail para resposta: ' . $PostData['email'] . ' </p>
                           
                           <p style="font-size: 1em">
                             *Contato gerado pelo formulário do Site em ' . date('d/m/Y à\s H:i:s') . '
                             <a href="' . BASE . '" title="Visitar site!"><img style="width=250px" src="' .
					INCLUDE_PATH .	'/images/iccserra-logo-h.png" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /></a>
                          </p>
                          <br>
                          </td></tr>
                        </table>
                        <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-bottom: 15px 0 !important;}</style>';

				$Saudacao = Check::salutation();

				$Agradecimento = '
                        <table width="550" style="width:550px; font-family: Verdana, sans-serif;">
                         <tr><td style="border 1px solid #cccccc !important; color: #222222 !important" >
                            <p>' . $Saudacao . ' <b>' . $PostData['nome'] . '</b></p>
                           <p><br>Em breve estaremos respondendo seu e-mail. <br> Obrigado! </p><br><br>
                          
                          <p style="font-size: 1em;">
                           <a href="' . BASE . '" title="Visitar site!"><img src="' . INCLUDE_PATH . '/images/iccserra-logo-h.png" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /></a>
                         
						<br><br>
                           ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
                           Visite nosso site: <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a>
                          </p>
                          </td></tr>
                        </table>
                        <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-bottom: 15px !important;}</style>';

				$Email = new Email();

				$Email->enviarMontando($PostData['assunto'], $MailContent, $PostData['nome'], $PostData['email'],
					SITE_ADDR_NAME, SITE_ADDR_EMAIL);

				if (!$Email->getError()) {
					$jSON['trigger'] = Check::ajaxErro("{$Saudacao} <b>{$PostData['nome']}</b>, sua mensagem foi recebida com sucesso. Obrigado!");
					$Email->enviarMontando("{$PostData['nome']}, recebemos sua mensagem!", $Agradecimento,
						SITE_ADDR_NAME, SITE_ADDR_EMAIL, $PostData['nome'], $PostData['email']);
				} else {
					$jSON['trigger'] = Check::ajaxErro("<b>{$PostData['nome']}</b>, não foi possível enviar sua mensagem. Entre em contato pelo E-mail" . SITE_ADDR_EMAIL . ". Obrigado!",
						E_USER_ERROR);
				}
			}
			break;

		case 'partner':

			$PostData['atividade_principal'] = $PostData['atividade_principal'] ?? 'Não informado';
			$PostData['atividades_secundarias'] = $PostData['atividades_secundarias'] ?? 'Não informado';
			$PostData['abertura'] = $PostData['abertura'] ?? 'Não informado';
			$PostData['data_situacao'] = $PostData['data_situacao'] ?? 'Não informado';
			$PostData['telefone'] = $PostData['telefone'] ?? 'Não informado';
			$PostData['qsa'] = $PostData['qsa'] ?? 'Não informado';
			$PostData['situacao'] = $PostData['situacao'] ?? 'Não informado';
			$PostData['capital_social'] = $PostData['capital_social'] ?? 'Não informado';
			$PostData['atividade_principal'] = $PostData['atividade_principal'] ?? 'Não informado';

			if (array_search('', $PostData)) {
				$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Para enviar sua <b>Solicitação de Parceria</b>, por favor preencha todos os campos!',
					E_USER_NOTICE);
				$jSON['foco'] = $PostData;

			} elseif (!isset($PostData['privacy'])) {
				$jSON['trigger'] = Check::ajaxErro('<b>ERRO:</b> Para enviar sua solicitação, você deve consentir com nossa Política de Privacidade.',
					E_USER_ERROR);
				$jSON['foco'] = 'privacy';

			} else {
				if (!Check::email($PostData['email']) || !filter_var($PostData['email'], FILTER_VALIDATE_EMAIL)) {
					$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> E-mail informado não é válido!', E_USER_NOTICE);
					$jSON['foco'] = 'email';

				} elseif (!Check::cpf($PostData['cpf'])) {
					$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> CPF informado não é válido!', E_USER_NOTICE);
					$jSON['foco'] = 'cpf';

				} elseif (!Check::cnpj($PostData['cnpj'])) {
					$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> CNPJ  informado não é válido!', E_USER_NOTICE);
					$jSON['foco'] = 'cnpj';

				} elseif ($human == false) {
					$jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b>' . $PostData['name'] . ', para enviar sua MENSAGEM, realize a soma dos números corretamente.',
						E_USER_ERROR);
					$jSON['foco'] = 'senderHuman';

					//EXECUTA ENVIOS
				} else {
					//$Create = new Create();
					//$Create->exeCreate(DB_LEADS, $PostData);

					$Saudacao = Check::salutation();
					//Converte String para Arrays
					$atividadePrincipal = explode(",", $PostData['atividade_principal']);
					$atividadesSecundarias = explode("},{", $PostData['atividades_secundarias']);
					$quadroSocial = explode("},{", $PostData['qsa']);
					$PostData['name'] = ucwords(strtolower($PostData['name']));

					//DISPARA OS ALERTAS POR E-MAIL
					$MailContent = "
                        <table width=\"550\" cellspacing=\"0\" rules=\"none\">
                            <tr>
                                <td>
                                    <p>Olá! Meu nome é <b> {$PostData['name']}</b> meu CPF é <b>{$PostData['cpf']}</b>, desejo ser um parceiro estratégico da ICC SERRA no município de {$PostData['city']}.<br><br>
                                    {$PostData['message']} </p><br>
                                 </td>
                            </tr>
                            
                            <tr>
                            <table width='600px' cellpadding='5px' border='1' rules='none' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 5px;'>
                            <tr style='background-color: #CCCCCC; text-align: center' ><th colspan=\"3\">DADOS PARA CONTATO</th></tr>
                             <tr style='text-align: center'>
                                <td colspan=\"2\">E-mail: {$PostData['email']}</td>
                                <td>Fone: <b>{$PostData['telephone']}</b></td>
                            </tr>
                    
                            <tr style='background-color: #CCCCCC; text-align: center' ><th colspan=\"3\">DADOS DA EMPRESA</th></tr>
                    
                            <tr style='border: 1px solid #CCCCCC'><td colspan=\"3\"><b>Nome da Empresa:</b> {$PostData['company']} </td></tr>
                            <tr style='border: 1px solid #CCCCCC'><td colspan=\"3\"><b>Telefone:</b> {$PostData['telefone']}</td></tr>
                            <tr style='border: 1px solid #CCCCCC'><td colspan=\"3\"><b>CNPJ:</b> {$PostData['cnpj']}</td></tr>
                            <tr style='border: 1px solid #CCCCCC'><td colspan=\"3\"><b>CEP:</b> {$PostData['cep']}</td></tr>
                            <tr style='border: 1px solid #CCCCCC'><td colspan=\"3\"><b>Endereço:</b> {$PostData['address']}, {$PostData['number']} - {$PostData['complement']}</td></tr>
                            <tr style='border: 1px solid #CCCCCC'><td colspan=\"3\"><b>Bairro:</b> {$PostData['neigthborhood']}</td></tr>
                            <tr style='border: 1px solid #CCCCCC'><td colspan=\"3\"><b>Cidade:</b> {$PostData['city']} - {$PostData['state']}</td></tr>
                    
                            <tr style='background-color: #CCCCCC; text-align: center' >
                                <th>DATA DA ABERTURA:</th>
                                <th>SITUAÇÃO:</th>
                                <th>CAPITAL SOCIAL:</th>
                            </tr>
                            <tr style='text-align: center'>
                                <td style='border: 1px solid #CCCCCC'>{$PostData['abertura']}</td>
                                <td style='border: 1px solid #CCCCCC'>{$PostData['situacao']}</td>
                                <td style='border: 1px solid #CCCCCC'>{$PostData['capital_social']}</td>
                            </tr>
                    
                            <tr style='background-color: #CCCCCC; text-align: center' > <th colspan=\"3\">QUADRO SOCIAL</th> </tr>";

					foreach ($quadroSocial as $Socios) {
						$MailContent .= "<tr><td colspan='3'>" . str_replace([
								'qual":"',
								'nome":"',
								'[{',
								'}]',
								'"'
							], ['<b>&nbsp;</b>', '<b>'], $Socios) . "</b></td></tr>";
					}

					$MailContent .= " <tr style='background-color: #CCCCCC; text-align: center' > <th colspan=\"3\">ATIVIDADES PRIMÁRIAS</th> </tr> ";

					foreach ($atividadePrincipal as $Principal) {
						$MailContent .= "<tr><td colspan='3'>" . str_replace([
								'"text":"',
								'"code":"',
								'[{',
								'}]',
								'"'
							], ['<b>Descrição: </b>', '<b>CNAE:&nbsp;</b>'], $Principal) . "</td></tr>";
					}

					$MailContent .= " <tr style='background-color: #CCCCCC; text-align: center' > <th colspan=\"3\">ATIVIDADES SECUNDÁRIAS</th> </tr> ";
					foreach ($atividadesSecundarias as $Secundaria) {
						$MailContent .= "<tr style='border 1px solid #cccccc !important; color: #222222 !important'><td colspan='3'>" . str_replace([
								'"text":"',
								'","code":"',
								'[{',
								'}]',
								'"'
							], ['<b>Descrição: </b>', ' <br> <b>CNAE: </b>'], $Secundaria) . "</td></tr>";
					}
					$MailContent .= "</table></tr></table>";

					$Agradecimento = '
                            <table width="550" style="font-family: Tahoma, sans-serif;">
                                <tr><td >
                                    <p>' . $Saudacao . ' <b>' . $PostData['name'] . '</b></p>
                                    <p><br>A  ICC SERRA agradece pelo interesse em se tornar um parceiro comercial em sua cidade.  Em breve estaremos respondendo sua solicitação.  Esse e-mail é para confirmar que recebemos sua solicitação e estamos analizando os dados, aguarde!<br><br> Obrigado! </p><br>
                                    <p style="font-size: 0.9em;">
                                        <img src="' . INCLUDE_PATH . '/images/mail/iccserra-logo-h.png" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" />
                                        <br><br>
                                        ' . SITE_ADDR_NAME . '<br><b>Telefone:</b> ' . SITE_ADDR_PHONE_A . '<br><b>E-mail:</b> ' . SITE_ADDR_EMAIL . '<br><br>
                                        Visite nosso site: <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a>
                                    </p>
                                </td></tr>
                            </table>
                        <style>body{max-width: 200px !important; height: auto !important;} img{max-width: 200px !important; height: auto !important;} p{margin-bottom: 15px 0 !important;}</style>';

					$Email = new Email();
					$Email->enviarMontando('Quero ser um Parceiro ICC Serra', $MailContent, $PostData['name'],
						$PostData['email'], SITE_ADDR_NAME, SITE_ADDR_EMAIL);

					if (!$Email->getError()) {
						$Email->enviarMontando(SITE_ADDR_NAME . ' - Confirmação de recebimento', $Agradecimento,
							SITE_ADDR_NAME, SITE_ADDR_EMAIL, $PostData['name'], $PostData['email']);
						$jSON['trigger'] = Check::ajaxErro(" <b>Obrigado!</b> {$PostData[ 'name' ]}  Sua solicitação  foi recebida com <i class='icon icon-checkmark'></i> Sucesso!");
					} else {
						$jSON['trigger'] = Check::ajaxErro("<b>Erro:</b> Desculpe, não foi possível enviar sua mensagem. Entre em contato via por E - mail: " . SITE_ADDR_EMAIL . " . Obrigado!",
							E_USER_ERROR);
					}
				}
			}

			break;

		case 'curriculum':

			if (in_array('', $PostData)) {
				$jSON['trigger'] = Check::ajaxErro('<b>Info:</b> Preencha todos os campos e adicione um anexo em PDF!',
					E_USER_NOTICE);

			} elseif (!Check::email($PostData['email']) || !filter_var($PostData['email'], FILTER_VALIDATE_EMAIL)) {
				$jSON['trigger'] = Check::ajaxErro('<b class="fal fa-envelope">ERRO:</b> E-mail informado não é válido!',
					E_USER_ERROR);

			} elseif (empty($_FILES['cv_pdf']) || $_FILES['cv_pdf']['type'] != 'application/pdf') {
				$jSON['trigger'] = Check::ajaxErro("<b class='fal fa-file-pdf'> ERRO AO ENVIAR ARQUIVO:</b> Selecione um arquivo em PDF para anexar com seu Currículo.",
					E_USER_ERROR);
				$jSON['foco'] = 'cv_pdf';

			} elseif (!isset($PostData['privacy'])) {
				$jSON['trigger'] = Check::ajaxErro('<b>ERRO:</b> Para enviar seu currículo, você deve consentir com nossa Política de Privacidade.',
					E_USER_ERROR);
				$jSON['foco'] = 'privacy';

			} elseif ($human == false) {
				$jSON['trigger'] = Check::ajaxErro('<b>ERRO:</b> Para enviar seu currículo, realize a soma dos números corretamente.',
					E_USER_ERROR);
				$jSON['foco'] = 'senderHuman';

			} else {
				$PostData['full_name'] = Check::getCapilalize($PostData['full_name']);
				$phoneMask = $PostData['phone'];
				$PostData['phone'] = Check::clearNumber($PostData['phone']);
				$PostData['email'] = mb_strtolower($PostData['email']);
				$PostData['privacy'] = $PostData['privacy'] ? 1 : 0;
				$PostData['status'] = 1;

				//UPLOAD PDF
				if (!empty($_FILES['cv_pdf'])) {
					$File = $_FILES['cv_pdf'];

					$Read = new Read();
					$Read->exeRead(DB_CV, " WHERE email = :email ORDER BY id DESC LIMIT :limit",
						"email={$PostData['email']}&limit=1");

					if ($Read->getResult()) {
						$ThisCandidate = $Read->getResult()[0];
						$OldFile = __DIR__ . "/../../../uploads/{$ThisCandidate['cv_pdf']}";
						if ($ThisCandidate['cv_pdf'] && file_exists($OldFile) && !is_dir($OldFile)) {
							unlink($OldFile);
						}

						$Upload = new Upload(__DIR__ . '/../../../uploads/');
						$Upload->file($File, Check::name($PostData['full_name']) . '-cv', 'curriculos', 5);

						if ($Upload->getResult()) {
							$PostData['cv_pdf'] = $Upload->getResult();

						} else {
							$jSON['trigger'] = Check::ajaxErro("<b class='fal fa-file-pdf'> ERRO AO ENVIAR ARQUIVO:</b> Olá, selecione um arquivo em PDF para anexar.",
								E_USER_WARNING);
							echo json_encode($jSON);
							return;
						}

						$Update = new Update();
						$Update->exeUpdate(DB_CV, $PostData, "WHERE email = :email", "email={$PostData['email']}");

						if ($Update->getResult()) {
							$jSON['trigger'] = Check::ajaxErro("<b class='fal fa-user-astronaut'> Currículo atualizado com sucesso!</b>");
						}

					} else {
						$Upload = new Upload(__DIR__ . '/../../../uploads/');
						$Upload->file($File, Check::name($PostData['full_name']) . '-cv', 'curriculos', 5);

						if ($Upload->getResult()) {
							$PostData['cv_pdf'] = $Upload->getResult();
						} else {
							$jSON['trigger'] = Check::ajaxErro("<b class='fal fa-file-pdf'> ERRO AO ARQUIVO PDF</b> Olá, selecione um arquivo em PDF para anexar.",
								E_USER_WARNING);
							echo json_encode($jSON);
							return;
						}

						$Create = new Create();
						$Create->exeCreate(DB_CV, $PostData);

						if ($Create->getResult()) {
							$jSON['trigger'] = Check::ajaxErro("<b> Currículo cadastrado com sucesso!</b>");
						}
					}

					$anexo = __DIR__ . '/../../../uploads/' . $PostData['cv_pdf'];

					$arrayData = $PostData;
					$arrayData['INCLUDE_PATH'] = INCLUDE_PATH;
					$arrayData['SITE_NAME'] = SITE_NAME;
					$arrayData['SITE_ADDR_NAME'] = SITE_ADDR_NAME;
					$arrayData['SITE_ADDR_PHONE_A'] = SITE_ADDR_PHONE_A;
					$arrayData['SITE_ADDR_EMAIL'] = MAIL_TESTER;
					$arrayData['SITE_ADDR_SITE'] = SITE_ADDR_SITE;
					$arrayData['BASE'] = BASE;
					$arrayData['phone_mask'] = $phoneMask;
					$arrayData['saudacao'] = Check::salutation();

					$MailContent = Template::setTemplate(
						Template::getTemplate('curriculum_mail.html', __DIR__ . '/../templates/'),
						$arrayData
					);

					$Email = new Email();
					$Email->addFile($anexo);
					$Email->enviarMontando(
						"{$PostData['area']}: Novo currículo recebido",
						$MailContent,
						$PostData['full_name'],
						$PostData['email'],
						SITE_ADDR_NAME,
						SITE_ADDR_EMAIL);

					if (!$Email->getError()) {
						$MailConfirmation = Template::setTemplate(Template::getTemplate('curriculum_return_mail.html',
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
						$jSON['trigger'] = Check::ajaxErro('<b>Erro:</b> Desculpe, não foi possível enviar sua mensagem. Entre em contato via E-mail: ' . SITE_ADDR_EMAIL . ' Obrigado!',
							E_USER_ERROR);
					}

				} else {
					unset($PostData['cv_pdf']);
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
