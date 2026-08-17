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
