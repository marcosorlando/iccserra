-- ============================================================================
-- LPs DE DESTINO PARA CAMPANHAS GOOGLE (admin/_siswc/gadslps)
-- MARCOS ORLANDO 23/11/2026
--
--   ws_gads_lps -> páginas de destino (textos em JSON + imagem do hero) + IDs das tags Google de cada página.
--                  Os CTAs levam ao simulador (fim do funil); não há formulário.
-- ============================================================================

CREATE TABLE IF NOT EXISTS `ws_gads_lps` (
  `page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL COMMENT '<title> e uso interno',
  `page_name` varchar(255) DEFAULT NULL COMMENT 'slug da URL',
  `page_status` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `page_description` varchar(320) DEFAULT NULL,
  `page_og_image` varchar(255) DEFAULT NULL,
  `page_schema` text DEFAULT NULL COMMENT 'JSON-LD',
  `page_data` mediumtext DEFAULT NULL COMMENT 'JSON com os textos e listas do corpo da LP, campos do form do admin',
  `page_hero_image` varchar(255) DEFAULT NULL COMMENT 'imagem do hero, relativa a uploads/gadslps/',
  `page_cta_url` varchar(255) DEFAULT NULL COMMENT 'destino dos CTAs, vazio = BASE/simulador',
  `page_gtag_id` varchar(30) DEFAULT NULL COMMENT 'GA4: G-XXXXXXXXXX',
  `page_ads_id` varchar(30) DEFAULT NULL COMMENT 'Google Ads: AW-XXXXXXXXX',
  `page_ads_label` varchar(60) DEFAULT NULL COMMENT 'rótulo da conversão, disparada ao concluir a simulação',
  `page_gtm_id` varchar(30) DEFAULT NULL COMMENT 'GTM-XXXXXXX',
  `page_views` int(10) unsigned NOT NULL DEFAULT 0,
  `page_date` datetime DEFAULT NULL,
  `page_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `uq_gads_page_name` (`page_name`),
  KEY `idx_gads_page_status` (`page_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
