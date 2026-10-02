# MARCOS ORLANDO 02/10/2026

## -- CRM do Simulador (admin/_siswc/crm)

Script completo, com etapas iniciais, mensagens pré-definidas e rollback:
`database/2026-10-02_crm_simulador.sql`

Leads do simulador de crédito passam a ser gravados (antes só iam por e-mail).
Cliente único por e-mail; cada simulação fica vinculada a ele. Constantes:
`DB_CRM_STAGES`, `DB_CRM_CLIENTS`, `DB_CRM_SIMULATIONS`, `DB_CRM_HISTORY`, `DB_CRM_TEMPLATES`.

```
CREATE TABLE IF NOT EXISTS `ws_crm_stages` (
  `stage_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(100) NOT NULL,
  `stage_color` char(7) NOT NULL DEFAULT '#5d6d7e',
  `stage_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `stage_created` timestamp NULL DEFAULT current_timestamp(),
  `stage_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`stage_id`),
  KEY `idx_stage_order` (`stage_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ws_crm_clients` (
  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stage_id` int(10) unsigned DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `client_phone` varchar(30) DEFAULT NULL,
  `client_city` varchar(255) DEFAULT NULL,
  `client_state` char(2) DEFAULT NULL,
  `client_framework` varchar(10) DEFAULT NULL COMMENT 'mei | pj',
  `client_status` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '1 em aberto | 2 convertido | 3 perdido',
  `client_notes` text DEFAULT NULL,
  `client_last_contact` datetime DEFAULT NULL,
  `client_created` timestamp NULL DEFAULT current_timestamp(),
  `client_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `uq_client_email` (`client_email`),
  KEY `idx_client_stage_status` (`stage_id`, `client_status`),
  CONSTRAINT `fk_crm_client_stage` FOREIGN KEY (`stage_id`)
    REFERENCES `ws_crm_stages` (`stage_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ws_crm_simulations` (
  `sim_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `sim_framework` varchar(10) DEFAULT NULL COMMENT 'mei | pj',
  `sim_capital` varchar(10) DEFAULT NULL COMMENT 'giro | fixo',
  `sim_credit_line` varchar(30) DEFAULT NULL COMMENT 'microcredito | mulher | reforma | agricola',
  `sim_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sim_installments` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sim_installment_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sim_tac` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sim_city` varchar(255) DEFAULT NULL,
  `sim_state` char(2) DEFAULT NULL,
  `sim_ip` varchar(45) DEFAULT NULL,
  `sim_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sim_id`),
  KEY `idx_sim_client` (`client_id`, `sim_created`),
  CONSTRAINT `fk_crm_sim_client` FOREIGN KEY (`client_id`)
    REFERENCES `ws_crm_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ws_crm_history` (
  `history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'NULL = sistema/site',
  `history_type` varchar(20) NOT NULL COMMENT 'simulation | stage | status | email | whatsapp | note | update',
  `history_desc` text DEFAULT NULL,
  `history_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`history_id`),
  KEY `idx_history_client` (`client_id`, `history_created`),
  CONSTRAINT `fk_crm_history_client` FOREIGN KEY (`client_id`)
    REFERENCES `ws_crm_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ws_crm_templates` (
  `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_type` varchar(10) NOT NULL COMMENT 'email | whatsapp',
  `template_title` varchar(150) NOT NULL,
  `template_subject` varchar(255) DEFAULT NULL COMMENT 'só para e-mail',
  `template_body` text NOT NULL,
  `template_status` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `template_created` timestamp NULL DEFAULT current_timestamp(),
  `template_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`template_id`),
  KEY `idx_template_type` (`template_type`, `template_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

> As etapas e mensagens iniciais são inseridas pelo script só se as tabelas estiverem vazias.

---

# MARCOS ORLANDO 16/08/2026

## -- ws_slides: colunas do slide responsivo (widget _cdn/widgets/slide)

Script completo, com migração de dados e rollback:
`database/2026-08-16_ws_slides_responsive.sql`

O widget consulta colunas que não existiam na tabela, derrubando a home com
`Column not found: 1054 Unknown column 'slide_image_mobile'`. Adiciona 12 colunas
e copia `slide_image` → `slide_image_desktop` (sem isso os banners já cadastrados
ficam sem imagem).

```
ALTER TABLE `ws_slides`
    ADD COLUMN `slide_image_desktop` varchar(255) DEFAULT NULL AFTER `slide_image`,
    ADD COLUMN `slide_image_tablet`  varchar(255) DEFAULT NULL AFTER `slide_image_desktop`,
    ADD COLUMN `slide_image_mobile`  varchar(255) DEFAULT NULL AFTER `slide_image_tablet`,
    ADD COLUMN `slide_headline`      varchar(255) DEFAULT NULL AFTER `slide_title`,
    ADD COLUMN `show_headline`       tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_headline`,
    ADD COLUMN `show_desc`           tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_desc`,
    ADD COLUMN `slide_product`       tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_link`,
    ADD COLUMN `slide_link_pdt`      varchar(255) DEFAULT NULL AFTER `slide_product`,
    ADD COLUMN `slide_link_pdt_btn`  varchar(100) DEFAULT NULL AFTER `slide_link_pdt`,
    ADD COLUMN `slide_category`      tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_link_pdt_btn`,
    ADD COLUMN `slide_link_cat`      varchar(255) DEFAULT NULL AFTER `slide_category`,
    ADD COLUMN `slide_link_cat_btn`  varchar(100) DEFAULT NULL AFTER `slide_link_cat`;

UPDATE `ws_slides` SET `slide_image_desktop` = `slide_image`
WHERE `slide_image` IS NOT NULL AND `slide_image` <> '';

ALTER TABLE `ws_slides`
    ADD INDEX `idx_slide_status_periodo` (`slide_status`, `slide_start`, `slide_end`);
```

> `slide_image` foi mantida: `admin/_ajax/Slides.ajax.php` ainda lê essa coluna
> para apagar o arquivo antigo no upload.
> Conferir se a coluna já existe antes de rodar em produção.

---

# MARCOS ORLANDO 04/10/2024

## -- Tabela de Usuário cartão linktree

```
CREATE TABLE `trv_card_user` (
  `carduser_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carduser_thumb` varchar(255) DEFAULT NULL,
  `carduser_name` varchar(255) DEFAULT NULL,
  `carduser_lastname` varchar(255) DEFAULT NULL,
  `carduser_url` varchar(255) DEFAULT NULL,
  `carduser_email` varchar(255) DEFAULT NULL,
  `carduser_phone` varchar(45) DEFAULT NULL,
  `carduser_cargo` varchar(155) DEFAULT NULL,
  `carduser_state` char(2) DEFAULT NULL,
  `carduser_city` varchar(255) DEFAULT NULL,
  `carduser_created` timestamp NULL DEFAULT current_timestamp(),
  `carduser_updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `carduser_status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`carduser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```
