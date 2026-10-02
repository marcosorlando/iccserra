-- ============================================================================
-- CRM DO SIMULADOR (admin/_siswc/crm)
-- MARCOS ORLANDO 02/10/2026
--
-- Pipeline de atendimento dos leads vindos do simulador de crédito.
--   ws_crm_stages      -> etapas do funil (editáveis, ordenáveis)
--   ws_crm_clients     -> cliente/lead (1 por e-mail) + etapa + status do CRM
--   ws_crm_simulations -> cada simulação feita no site, vinculada ao cliente
--   ws_crm_history     -> linha do tempo (simulações, mudanças, e-mails, whats, notas)
--   ws_crm_templates   -> mensagens pré-definidas de e-mail e WhatsApp
-- ============================================================================

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

-- ETAPAS INICIAIS (só se a tabela estiver vazia)
INSERT INTO `ws_crm_stages` (`stage_name`, `stage_color`, `stage_order`)
SELECT * FROM (
    SELECT 'Etapa 1' AS n, '#3498db' AS c, 1 AS o UNION ALL
    SELECT 'Etapa 2', '#9b59b6', 2 UNION ALL
    SELECT 'Etapa 3', '#f39c12', 3 UNION ALL
    SELECT 'Etapa 4', '#e67e22', 4 UNION ALL
    SELECT 'Etapa 5', '#27ae60', 5
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM `ws_crm_stages`);

-- MENSAGENS PRÉ-DEFINIDAS (só se a tabela estiver vazia)
-- Variáveis: {nome} {primeiro_nome} {email} {telefone} {cidade} {valor} {parcelas}
--            {valor_parcela} {linha_credito} {tipo_capital} {data_simulacao}
--            {site_nome} {site_telefone} {site_email}
INSERT INTO `ws_crm_templates` (`template_type`, `template_title`, `template_subject`, `template_body`)
SELECT * FROM (
    SELECT 'whatsapp' AS t, 'Primeiro contato' AS ti, NULL AS s,
           'Olá {primeiro_nome}, tudo bem? Aqui é da {site_nome}. Recebemos sua simulação de {linha_credito} no valor de {valor} em {parcelas} parcelas. Podemos conversar para dar andamento?' AS b
    UNION ALL
    SELECT 'whatsapp', 'Solicitar documentos', NULL,
           'Olá {primeiro_nome}! Para seguirmos com a análise do seu crédito de {valor}, precisamos de alguns documentos. Posso te enviar a lista por aqui?'
    UNION ALL
    SELECT 'whatsapp', 'Retomar contato', NULL,
           'Oi {primeiro_nome}, tudo bem? Estamos à disposição para dar continuidade à sua simulação de {valor}. Ficou alguma dúvida?'
    UNION ALL
    SELECT 'email', 'Primeiro contato', 'Sua simulação de crédito na {site_nome}',
           'Olá {nome},\n\nRecebemos sua simulação de {linha_credito} ({tipo_capital}) no valor de {valor}, em {parcelas} parcelas de {valor_parcela}.\n\nUm agente de crédito vai entrar em contato para dar andamento. Se preferir, fale com a gente pelo telefone {site_telefone}.\n\nAtenciosamente,\n{site_nome}'
    UNION ALL
    SELECT 'email', 'Documentos necessários', 'Documentos para análise do seu crédito',
           'Olá {nome},\n\nPara seguirmos com a análise do seu crédito de {valor}, precisamos dos documentos abaixo:\n\n- Documento de identidade (RG ou CNH) e CPF\n- Comprovante de residência\n- Comprovante da atividade (CNPJ/MEI quando houver)\n\nVocê pode responder este e-mail com os arquivos.\n\nAtenciosamente,\n{site_nome}'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM `ws_crm_templates`);

-- ROLLBACK
-- DROP TABLE IF EXISTS `ws_crm_history`, `ws_crm_simulations`, `ws_crm_clients`, `ws_crm_templates`, `ws_crm_stages`;
