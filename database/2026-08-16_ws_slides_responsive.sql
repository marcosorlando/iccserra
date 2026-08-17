-- =============================================================================
-- ws_slides: alinha a tabela ao widget _cdn/widgets/slide/slide.wc.php
--            e ao admin _sis/slide/create.php
--
-- MOTIVO: o widget faz
--   SELECT slide_image_mobile, slide_image_tablet, slide_image_desktop,
--          slide_headline, slide_category, slide_product, slide_link_pdt,
--          slide_link_pdt_btn, slide_link_cat, slide_link_cat_btn,
--          show_headline, show_desc ...
-- e nenhuma dessas colunas existe hoje, o que derruba a home com
-- "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'slide_image_mobile'".
--
-- COMPATIBILIDADE: sintaxe padrão (MySQL 5.7+ e MariaDB 10+).
--                  Rodar UMA vez — não há IF NOT EXISTS por portabilidade.
-- APLICAR EM: banco de desenvolvimento e de produção.
--
-- Data: 2026-08-16
-- =============================================================================

-- -----------------------------------------------------------------------------
-- 0) CONFERÊNCIA PRÉVIA — deve listar as 10 colunas antigas, sem as novas.
-- -----------------------------------------------------------------------------
-- SHOW COLUMNS FROM `ws_slides`;
-- SELECT COUNT(*) AS total, SUM(slide_status = 1) AS ativos FROM `ws_slides`;

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- 1) IMAGENS RESPONSIVAS
--    O widget monta um <picture> com três fontes. slide_image (legado) é
--    mantida: admin/_ajax/Slides.ajax.php ainda lê essa coluna para apagar o
--    arquivo antigo no upload.
-- -----------------------------------------------------------------------------
ALTER TABLE `ws_slides`
    ADD COLUMN `slide_image_desktop` varchar(255) DEFAULT NULL AFTER `slide_image`,
    ADD COLUMN `slide_image_tablet`  varchar(255) DEFAULT NULL AFTER `slide_image_desktop`,
    ADD COLUMN `slide_image_mobile`  varchar(255) DEFAULT NULL AFTER `slide_image_tablet`;

-- -----------------------------------------------------------------------------
-- 2) TEXTOS E FLAGS DE EXIBIÇÃO
--    show_headline / show_desc = 0 preserva o visual atual: os registros
--    existentes não têm headline preenchida e renderizariam <h1></h1> vazio.
-- -----------------------------------------------------------------------------
ALTER TABLE `ws_slides`
    ADD COLUMN `slide_headline` varchar(255) DEFAULT NULL AFTER `slide_title`,
    ADD COLUMN `show_headline`  tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_headline`,
    ADD COLUMN `show_desc`      tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_desc`;

-- -----------------------------------------------------------------------------
-- 3) BOTÕES DE PRODUTO E CATEGORIA
--    slide_product / slide_category são os liga-desliga de cada botão;
--    *_btn guarda o rótulo exibido.
-- -----------------------------------------------------------------------------
ALTER TABLE `ws_slides`
    ADD COLUMN `slide_product`      tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_link`,
    ADD COLUMN `slide_link_pdt`     varchar(255) DEFAULT NULL AFTER `slide_product`,
    ADD COLUMN `slide_link_pdt_btn` varchar(100) DEFAULT NULL AFTER `slide_link_pdt`,
    ADD COLUMN `slide_category`     tinyint(1) NOT NULL DEFAULT 0 AFTER `slide_link_pdt_btn`,
    ADD COLUMN `slide_link_cat`     varchar(255) DEFAULT NULL AFTER `slide_category`,
    ADD COLUMN `slide_link_cat_btn` varchar(100) DEFAULT NULL AFTER `slide_link_cat`;

-- -----------------------------------------------------------------------------
-- 4) MIGRAÇÃO DOS DADOS
--    Sem isto os banners já cadastrados ficam sem imagem: o widget lê
--    slide_image_desktop, não slide_image. Tablet e mobile seguem NULL de
--    propósito — o widget cai para a imagem desktop quando estão vazios.
-- -----------------------------------------------------------------------------
UPDATE `ws_slides`
SET `slide_image_desktop` = `slide_image`
WHERE `slide_image` IS NOT NULL
  AND `slide_image` <> ''
  AND (`slide_image_desktop` IS NULL OR `slide_image_desktop` = '');

-- -----------------------------------------------------------------------------
-- 5) ÍNDICE DA CONSULTA DO WIDGET
--    O widget filtra por slide_status + janela de datas e ordena por slide_date.
-- -----------------------------------------------------------------------------
ALTER TABLE `ws_slides`
    ADD INDEX `idx_slide_status_periodo` (`slide_status`, `slide_start`, `slide_end`);

COMMIT;

-- -----------------------------------------------------------------------------
-- 6) CONFERÊNCIA FINAL
-- -----------------------------------------------------------------------------
-- SHOW COLUMNS FROM `ws_slides`;
-- SELECT slide_id, slide_image, slide_image_desktop, slide_status
--   FROM `ws_slides` ORDER BY slide_id;


-- =============================================================================
-- OPCIONAL — PADRONIZAR CHARSET
-- A tabela está em utf8mb3/utf8mb3_uca1400_ai_ci, mas o projeto usa utf8mb4
-- (DB_CHARSET no .env). Rode apenas se for padronizar; faça backup antes,
-- pois reescreve a tabela inteira.
-- =============================================================================
-- ALTER TABLE `ws_slides` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- =============================================================================
-- ROLLBACK — desfaz este script (os dados das colunas novas são perdidos;
-- slide_image permanece intacta).
-- =============================================================================
-- ALTER TABLE `ws_slides`
--     DROP INDEX `idx_slide_status_periodo`,
--     DROP COLUMN `slide_image_desktop`,
--     DROP COLUMN `slide_image_tablet`,
--     DROP COLUMN `slide_image_mobile`,
--     DROP COLUMN `slide_headline`,
--     DROP COLUMN `show_headline`,
--     DROP COLUMN `show_desc`,
--     DROP COLUMN `slide_product`,
--     DROP COLUMN `slide_link_pdt`,
--     DROP COLUMN `slide_link_pdt_btn`,
--     DROP COLUMN `slide_category`,
--     DROP COLUMN `slide_link_cat`,
--     DROP COLUMN `slide_link_cat_btn`;
