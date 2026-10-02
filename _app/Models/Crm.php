<?php

declare(strict_types=1);

namespace App\Models;

use App\Conn\Create;
use App\Conn\Read;
use App\Conn\Update;

use function array_keys;
use function array_values;
use function date;
use function explode;
use function htmlspecialchars;
use function http_build_query;
use function mb_strtolower;
use function nl2br;
use function number_format;
use function sprintf;
use function str_replace;
use function strtotime;
use function strtoupper;
use function trim;
use function ucwords;

/**
 * Crm [ MODEL ]
 * Regras do micro CRM dos leads do simulador de crédito: registro de simulações,
 * etapas do funil, linha do tempo e montagem das mensagens pré-definidas.
 * @copyright (c) 2026, Marcos Orlando - ZEN AGÊNCIA WEB
 */
class Crm
{
    public const int STATUS_OPEN = 1;

    public const int STATUS_WON = 2;

    public const int STATUS_LOST = 3;

    public const array STATUS = [
        self::STATUS_OPEN => 'Em aberto',
        self::STATUS_WON => 'Convertido',
        self::STATUS_LOST => 'Perdido',
    ];

    public const array HISTORY_ICONS = [
        'simulation' => 'icon-calculator',
        'stage' => 'icon-shuffle',
        'status' => 'icon-flag',
        'email' => 'icon-envelop',
        'whatsapp' => 'icon-bubbles4',
        'note' => 'icon-pencil2',
        'update' => 'icon-user-check',
    ];

    public const array CREDIT_LINES = [
        'MICROCREDITO' => 'Microcrédito Produtivo',
        'MULHER' => 'Microcrédito Mulher',
        'REFORMA' => 'Crédito para Reforma',
        'AGRICOLA' => 'Crédito Agrícola',
        'BANRISUL' => 'Microcrédito Banrisul',
        'PESSOAL' => 'Crédito Pessoal',
    ];

    public const array CAPITALS = [
        'GIRO' => 'Capital de Giro',
        'FIXO' => 'Capital Fixo',
    ];

    public const array FRAMEWORKS = [
        'mei' => 'MEI',
        'pj' => 'Microempresário',
    ];

    private Read $read;

    private Create $create;

    private Update $update;

    public function __construct()
    {

        $this->read = new Read();
        $this->create = new Create();
        $this->update = new Update();
    }

    /**
     * Grava a simulação feita no site. Reaproveita o cliente pelo e-mail (atualizando contato)
     * ou cria um novo na primeira etapa do funil.
     *
     * @param array<string, mixed> $sim dados já validados e calculados pelo Simulador.ajax.php
     *
     * @return null|int client_id
     */
    public function registerSimulation(array $sim): ?int
    {

        $email = mb_strtolower(trim((string)($sim['email'] ?? '')));
        if ('' === $email) {
            return null;
        }

        $contact = [
            'client_name' => trim((string)($sim['nome'] ?? '')),
            'client_phone' => trim((string)($sim['telefone'] ?? '')),
            'client_city' => trim((string)($sim['cidade'] ?? '')),
            'client_state' => trim((string)($sim['estado'] ?? '')),
            'client_framework' => trim((string)($sim['cb_enquadramento'] ?? '')),
        ];

        $this->read->exeRead(DB_CRM_CLIENTS, 'WHERE client_email = :email', http_build_query(['email' => $email]));
        $client = $this->read->getResult()[0] ?? null;

        if ($client) {
            $clientId = (int)$client['client_id'];
            // cliente perdido/convertido que volta a simular reabre o atendimento
            if ((int)$client['client_status'] !== self::STATUS_OPEN) {
                $contact['client_status'] = self::STATUS_OPEN;
            }
            $this->update->exeUpdate(DB_CRM_CLIENTS, $contact, 'WHERE client_id = :id', 'id=' . $clientId);
        } else {
            $contact['client_email'] = $email;
            $contact['stage_id'] = $this->firstStageId();
            $contact['client_status'] = self::STATUS_OPEN;
            $this->create->exeCreate(DB_CRM_CLIENTS, $contact);
            $clientId = (int)$this->create->getResult();
            if (!$clientId) {
                return null;
            }
        }

        $this->create->exeCreate(DB_CRM_SIMULATIONS, [
            'client_id' => $clientId,
            'sim_framework' => $contact['client_framework'],
            'sim_capital' => (string)($sim['modalidade'] ?? ''),
            'sim_credit_line' => (string)($sim['tipo_credito'] ?? ''),
            'sim_amount' => (float)($sim['valor'] ?? 0),
            'sim_installments' => (int)($sim['parcelas'] ?? 0),
            'sim_installment_value' => (float)($sim['valor_parcela'] ?? 0),
            'sim_tac' => (float)($sim['tac'] ?? 0),
            'sim_city' => $contact['client_city'],
            'sim_state' => $contact['client_state'],
            'sim_ip' => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
        ]);

        $this->log(
            $clientId,
            'simulation',
            sprintf(
                'Nova simulação pelo site: %s de %s em %dx de %s.',
                self::creditLine((string)($sim['tipo_credito'] ?? '')),
                self::money((float)($sim['valor'] ?? 0)),
                (int)($sim['parcelas'] ?? 0),
                self::money((float)($sim['valor_parcela'] ?? 0))
            ) . ($client && (int)$client['client_status'] !== self::STATUS_OPEN ? ' Atendimento reaberto.' : '')
        );

        return $clientId;
    }

    public function firstStageId(): ?int
    {

        $this->read->fullRead('SELECT stage_id FROM ' . DB_CRM_STAGES . ' ORDER BY stage_order ASC, stage_id ASC LIMIT 1');

        return isset($this->read->getResult()[0]) ? (int)$this->read->getResult()[0]['stage_id'] : null;
    }

    public function log(int $clientId, string $type, string $desc, ?int $userId = null): void
    {

        $data = [
            'client_id' => $clientId,
            'history_type' => $type,
            'history_desc' => $desc,
        ];
        if (null !== $userId) {
            $data['user_id'] = $userId;
        }

        $this->create->exeCreate(DB_CRM_HISTORY, $data);
    }

    /**
     * Linha do tempo do cliente (mais recente primeiro) já em HTML.
     */
    public function historyHtml(int $clientId): string
    {

        $this->read->fullRead(
            'SELECT h.history_type, h.history_desc, h.history_created, u.user_name, u.user_lastname'
            . ' FROM ' . DB_CRM_HISTORY . ' h'
            . ' LEFT JOIN ' . DB_USERS . ' u ON u.user_id = h.user_id'
            . ' WHERE h.client_id = :id ORDER BY h.history_created DESC, h.history_id DESC',
            'id=' . $clientId
        );

        if (!$this->read->getResult()) {
            return "<p class='crm_empty'>Nenhum registro no histórico ainda.</p>";
        }

        $html = '';
        foreach ($this->read->getResult() as $item) {
            $icon = self::HISTORY_ICONS[$item['history_type']] ?? 'icon-info';
            $author = $item['user_name']
                ? htmlspecialchars(trim($item['user_name'] . ' ' . $item['user_lastname']), ENT_QUOTES, 'UTF-8')
                : 'Site';
            $html .= sprintf(
                "<article class='crm_history_item crm_history_%s'><span class='crm_history_icon %s'></span>"
                . "<div><p>%s</p><small>%s &bull; %s</small></div></article>",
                htmlspecialchars((string)$item['history_type'], ENT_QUOTES, 'UTF-8'),
                $icon,
                nl2br(htmlspecialchars((string)$item['history_desc'], ENT_QUOTES, 'UTF-8')),
                date('d/m/Y H\hi', (int)strtotime((string)$item['history_created'])),
                $author
            );
        }

        return $html;
    }

    /**
     * Substitui as variáveis {chave} de uma mensagem pré-definida.
     *
     * @param array<string, mixed> $client
     * @param null|array<string, mixed> $sim última simulação do cliente
     */
    public static function render(string $text, array $client, ?array $sim = null): string
    {

        $name = trim((string)($client['client_name'] ?? ''));

        $vars = [
            '{nome}' => $name,
            '{primeiro_nome}' => explode(' ', $name)[0] ?? $name,
            '{email}' => (string)($client['client_email'] ?? ''),
            '{telefone}' => (string)($client['client_phone'] ?? ''),
            '{cidade}' => ucwords(mb_strtolower((string)($client['client_city'] ?? ''))),
            '{valor}' => $sim ? self::money((float)$sim['sim_amount']) : '',
            '{parcelas}' => $sim ? (string)$sim['sim_installments'] : '',
            '{valor_parcela}' => $sim ? self::money((float)$sim['sim_installment_value']) : '',
            '{linha_credito}' => $sim ? self::creditLine((string)$sim['sim_credit_line']) : '',
            '{tipo_capital}' => $sim ? self::capital((string)$sim['sim_capital']) : '',
            '{data_simulacao}' => $sim ? date('d/m/Y', (int)strtotime((string)$sim['sim_created'])) : '',
            '{site_nome}' => SITE_NAME,
            '{site_telefone}' => SITE_ADDR_PHONE_A,
            '{site_email}' => SITE_ADDR_EMAIL,
        ];

        return str_replace(array_keys($vars), array_values($vars), $text);
    }

    /**
     * Envolve o texto puro de um template num HTML simples de e-mail.
     */
    public static function emailHtml(string $text): string
    {

        $body = nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));

        return "<div style='font-family: Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #333; max-width: 640px;'>"
            . $body
            . "<hr style='border: 0; border-top: 1px solid #ddd; margin: 25px 0 10px;'>"
            . "<p style='font-size: 12px; color: #888;'>" . SITE_NAME . ' &bull; ' . SITE_ADDR_PHONE_A . ' &bull; ' . SITE_ADDR_EMAIL . '</p>'
            . '</div>';
    }

    public static function money(float $value): string
    {

        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    public static function status(int $status): string
    {

        return self::STATUS[$status] ?? self::STATUS[self::STATUS_OPEN];
    }

    public static function creditLine(string $line): string
    {

        return self::CREDIT_LINES[strtoupper($line)] ?? $line;
    }

    public static function capital(string $capital): string
    {

        return self::CAPITALS[strtoupper($capital)] ?? $capital;
    }

    public static function framework(?string $framework): string
    {

        return self::FRAMEWORKS[(string)$framework] ?? (string)$framework;
    }
}
