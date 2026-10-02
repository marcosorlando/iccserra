<?php

use App\Conn\Create;
use App\Conn\Read;
use App\Helpers\Check;

$AdminLevel = LEVEL_WC_SERVICES_STATIONS;
if (!APP_SERVICES_STATIONS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
$Read ??= new Read();

// AUTO INSTANCE OBJECT CREATE
$Create ??= new Create();

$StationId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($StationId) {
    $Read->exeRead(DB_SERVICES_STATIONS, 'WHERE id = :id', 'id=' . $StationId);
    if ($Read->getResult()) {
        $FormData = array_map(
            static fn($value) => Check::safeHtmlChars(is_scalar($value) ? (string)$value : null),
            $Read->getResult()[0]
        );
        extract($FormData);
    } else {
        $_SESSION['trigger_controll'] = sprintf(
            '<b>OPPSS %s</b>, você tentou editar um posto de atendimento que não existe ou que foi removido recentemente!',
            $Admin['user_name']
        );
        header('Location: dashboard.php?wc=postos/home');

        exit;
    }
} else {
    $StationCreate = [
        'created_at' => date('Y-m-d H:i:s'),
        'status' => 0,
    ];
    $Create->exeCreate(DB_SERVICES_STATIONS, $StationCreate);
    if (!$Create->getResult()) {
        $_SESSION['trigger_controll'] = sprintf(
            '<b>OPPSS %s</b>, não foi possível criar o posto de atendimento. Tente novamente!',
            $Admin['user_name']
        );
        header('Location: dashboard.php?wc=postos/home');

        exit;
    }
    header('Location: dashboard.php?wc=postos/create&id=' . $Create->getResult());

    exit;
}
?>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-location"><?= !empty($ss_name) ? $ss_name : 'Novo Posto de Atendimento'; ?></h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="Postos de Atendimento" href="dashboard.php?wc=postos/home">Postos de Atendimento</a>
			<span class="crumb">/</span>
			Gerenciar Posto
		</p>
	</div>

	<div class="dashboard_header_search">
		<a title="Ver Postos" href="dashboard.php?wc=postos/home" class="btn btn_blue icon-eye">Ver</a>
		<a title="Novo Posto" href="dashboard.php?wc=postos/create" class="btn btn_green icon-plus">Adicionar</a>
	</div>
</header>

<div class="dashboard_content">
	<form class="auto_save" name="station_create" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="callback" value="Postos"/>
		<input type="hidden" name="callback_action" value="manager"/>
		<input type="hidden" name="id" value="<?= $StationId; ?>"/>

		<div class="box box70">
			<div class="panel_header default">
				<h2 class="icon-location">Dados do Posto de Atendimento</h2>
			</div>

			<div class="panel">
				<label class="label">
					<span class="legend">Nome:</span>
					<input class="font_medium" type="text" name="ss_name" value="<?= !empty($ss_name) ? $ss_name : '';
                    ?>"
					       placeholder="Nome do posto:" required/>
				</label>

				<div class="label_50">
					<label class="label">
						<span class="legend">Telefone:</span>
						<input type="text" class="formPhone" name="ss_phone" value="<?= !empty($ss_phone) ? $ss_phone :
                            ''; ?>"
						       placeholder="(54) 99999-9999:" maxlength="13" required/>
					</label>
					<label class="label">
						<span class="legend">E-mail:</span>
						<input type="email" name="ss_email" value="<?= !empty($ss_email) ? $ss_email : ''; ?>"
						       placeholder="E-mail do posto:" required/>
					</label>
				</div>

				<div class="label_50">
					<label class="label">
						<span class="legend">CEP:</span>
						<input type="text" class="formCep" name="ss_zipcode" value="<?= !empty($ss_zipcode) ?
                            $ss_zipcode :
                            ''; ?>"
						       placeholder="00000-000:" maxlength="9" required/>
					</label>

					<label class="label">
						<span class="legend">Estado (UF):</span>
						<input type="text" class="" name="ss_state" value="<?= !empty($ss_state) ? $ss_state : '';
                        ?>"
						       placeholder="RS:" maxlength="2" required/>
					</label>
				</div>

				<label class="label">
					<span class="legend">Endereço:</span>
					<input type="text" name="ss_street" value="<?= !empty($ss_street) ? $ss_street : ''; ?>"
					       placeholder="Rua, avenida ou logradouro:" required/>
				</label>

				<div class="label_50">
					<label class="label">
						<span class="legend">Número:</span>
						<input type="text" name="ss_number" value="<?= !empty($ss_number) ? $ss_number : ''; ?>"
						       placeholder="Número:" maxlength="10" required/>
					</label>

					<label class="label">
						<span class="legend">Complemento: (opcional)</span>
						<input type="text" name="ss_complement"
						       value="<?= !empty($ss_complement) ? $ss_complement : ''; ?>"
						       placeholder="Sala, bairro, referência:" maxlength="50"/>
					</label>
				</div>

				<label class="label">
					<span class="legend">Cidade:</span>
					<input type="text" name="ss_city" value="<?= !empty($ss_city) ? $ss_city : ''; ?>"
					       placeholder="Cidade:" required/>
				</label>

				<label class="label">
					<span class="legend">Pin do mapa: (opcional)</span>
					<input type="text" name="ss_pinmap" value="<?= !empty($ss_pinmap) ? $ss_pinmap : ''; ?>"
					       placeholder="URL, coordenadas ou embed do mapa:"/>
				</label>
				<div class="clear"></div>
			</div>
		</div>

		<div class="box box30">
			<div class="panel_header default">
				<h2>Status</h2>
			</div>

			<div class="panel">
				<p class="m_bottom">Controle se este posto será considerado ativo no cadastro.</p>
				<div class="wc_actions" style="text-align: center; margin-bottom: 10px;">
					<label class="label_check label_publish <?= (!empty($status) && $status == 1 ? 'active' : ''); ?>"><input
								style="margin-top: -1px;" type="checkbox" value="1"
								name="status" <?= (!empty($status) && $status == 1 ? 'checked' : ''); ?>> Ativo</label>
					<button name="public" value="1" class="btn btn_green icon-share">ATUALIZAR</button>
					<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!"
					     title="Enviando Requisição!" src="_img/load.gif"/>
				</div>
			</div>
		</div>
	</form>
</div>
