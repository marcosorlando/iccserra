<?php

use App\Conn\Create;
use App\Helpers\Check;
use App\Models\Email;
use App\View\Template;

require_once __DIR__ . '/../../../../vendor/autoload.php';

// DEFINE O CALLBACK E RECUPERA O POST
$jSON = [];
$CallBack = 'Lp';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (null === $PostData || false === $PostData) {
	$PostData = [];
}

// VALIDA AÇÃO
if (isset($PostData['callback_action'], $PostData['callback']) && $PostData['callback'] === $CallBack) {

	//PREPARA OS DADOS
	$Case = $PostData['callback_action'];

    //Válida Captcha humanCheck
	$senderHuman = (int)($PostData['senderHuman'] ?? 0);
	$a = (int)($PostData['checkHuman_a'] ?? 0);
	$b = (int)($PostData['checkHuman_b'] ?? 0);
	$human = $senderHuman === ($a + $b);

	unset($PostData['callback'], $PostData['callback_action'],$PostData['checkHuman_a'],$PostData['checkHuman_b']);
	//ELIMINA CÓDIGOS
	$PostData = array_map(static fn($v) => is_string($v) ? strip_tags($v) : $v, $PostData);

	//SELECIONA AÇÃO
	switch ($Case) {
		//CAPTURA DE ACORDO COM CALLBACK-ACTION
		case 'manage':

			if (array_search('', $PostData)) {
				$jSON['trigger'] = Check::ajaxErro(
					'<b>Info:</b> Favor preencha todos os campos!',
					E_USER_NOTICE
				);
				$jSON['foco'] = array_search('', $PostData);

			} elseif (!Check::email($PostData['lead_email']) || !filter_var(
					$PostData['lead_email'],
					FILTER_VALIDATE_EMAIL
				)) {
				$jSON['trigger'] = Check::ajaxErro(
					'<b>Alerta: </b>' . $PostData['lead_name'] . ' o e-mail: <b>' . $PostData['lead_email'] . '</b> informado não é válido!',
					E_USER_WARNING
				);
				$jSON['foco'] = 'lead_email';

			} elseif (!isset($PostData['privacy'])){
				$jSON['trigger'] = Check::ajaxErro(
					'<b>Info: </b> Para receber seu material, você deve estar em Consentimento com nossas Políticas de Privacidade.',
					E_USER_NOTICE
				);
				$jSON['foco'] = 'privacy';

			} elseif ($human == false) {
				$jSON['trigger'] = Check::ajaxErro(
					'<b>Erro: </b>' . $PostData['lead_name'] . ', para receber seu material, realize a soma dos números corretamente',
					E_USER_ERROR
				);
				$jSON['foco'] = 'senderHuman';

			} else {

				$socialMedia = ' ';
				$socialMedia .= SITE_SOCIAL_FB ? "<a target='_blank' title='" . SITE_NAME . " no Facebook' href='https://www.facebook.com/" . SITE_SOCIAL_FB_PAGE . "'><img src='".BASE."/themes/".THEME."/images/mail/facebook.png' alt='Facebook'></a>" : '';
				$socialMedia .= SITE_SOCIAL_INSTAGRAM ? "<a target='_blank' title='" . SITE_NAME . " no Instagram' href='https://www.instagram.com/" . SITE_SOCIAL_INSTAGRAM . "'><img src='".BASE."/themes/".THEME."/images/mail/instagram.png' alt='Instagram'></a>" : '';
				$socialMedia .= SITE_SOCIAL_LINKEDIN ? "<a target='_blank' title='" . SITE_NAME . " no Linkedin' href='https://www.linkedin.com/company/" . SITE_SOCIAL_LINKEDIN . "'><img src='".BASE."/themes/".THEME."/images/mail/linkedin.png' alt='Linkedin'></a>" : '';
				$socialMedia .= SITE_SOCIAL_TWITTER ? "<a target='_blank' title='" . SITE_NAME . " no X' href='https://x.com/" . SITE_SOCIAL_TWITTER . "'><img src='".BASE."/themes/".THEME."/images/mail/x.png' alt='X'></a>" :
					'';
				$socialMedia .= SITE_SOCIAL_YOUTUBE ? "<a target='_blank' title='" . SITE_NAME . " no YouTube' href='https://www.youtube.com/@" . SITE_SOCIAL_YOUTUBE . "?sub_confirmation=1'><img src='".BASE."/themes/".THEME."/images/mail/x.png' alt=''></a>" : '';

				$mailData = [
					'domain' => BASE,
					'theme' => THEME,
					'site_name' => SITE_ADDR_NAME,
					'site_email' => SITE_ADDR_EMAIL,
					'site_city' => SITE_ADDR_CITY,
					'site_uf' => SITE_ADDR_UF,
					'site_country' => SITE_ADDR_COUNTRY,
					'page_destino' => $PostData['page_destino'],
					'page_mockup' => $PostData['page_mockup'],
					'page_conversion' => $PostData['lead_conversion'],
					'social_media' => $socialMedia,
					'lead_name' => $PostData['lead_name'],
					'lead_email' => $PostData['lead_email']
				];

				unset(
					$PostData['senderHuman'],
					$PostData['page_mockup'],
					$PostData['page_destino'],
					$PostData['privacy']
				);
				$PostData['lead_status'] = 1;
				$Create = new Create();
				$Create->exeCreate(DB_LEADS, $PostData);

				$MailContent = Template::setTemplate(Template::getTemplate('lp_mail.html', __DIR__ . '/../templates/'), $mailData);

				$Email = new Email();
				$Email->enviarMontando(
					'Conteúdo: ' . $mailData['page_conversion'],
					$MailContent,
					SITE_ADDR_NAME,
					SITE_ADDR_EMAIL,
					$PostData['lead_name'],
					$PostData['lead_email']
				);

				$jSON['trigger'] = Check::ajaxErro("<b>Obrigado!</b> Um link foi enviado para seu e-mail para salvar seu material!");
				$jSON['redirect'] = $mailData['page_destino'];

			}
			break;
	}

	//RETORNA O CALLBACK
	if ($jSON) {
		echo json_encode($jSON);
	} else {
		$jSON['trigger'] = Check::ajaxErro(
			'<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
			E_USER_ERROR
		);
		echo json_encode($jSON);
	}

} else {
	//ACESSO DIRETO
	exit('<br><br><br><h1 style="text-align: center;">Acesso Restrito!</h1>');
}
