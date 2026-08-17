<?php

    declare(strict_types=1);

    return [
        // Site config
        'SITE_NAME' => 'ICC SERRA',
        'SITE_SUBNAME' => 'Banco do Povo',
        'SITE_DESC' => 'A ICC SERRA - Banco do Povo é uma OSCIP de Microcrédito, nosso negócio é ofertar crédito para micros e pequenos empreendedores que operam no Rio Grande do Sul.',
        'SITE_FONT_NAME' => 'Open Sans',
        'SITE_FONT_WHIGHT' => '300,400,600,700',

        // Dados do cliente
        'SITE_ADDR_NAME' => 'ICC SERRA - Banco do Povo',
        'SITE_ADDR_RS' => 'Instituição Comunitária de Crédito da Serra',
        'SITE_ADDR_EMAIL' => 'iccserra@iccserra.org.br',
        'SITE_ADDR_SITE' => 'iccserra.org.br',
        'SITE_ADDR_CNPJ' => '05.036.783/0002-68',
        'SITE_ADDR_IE' => 'ISENTO',
        'SITE_ADDR_PHONE_A' => '(54) 3217-8787',
        'SITE_ADDR_SAC' => '(54) 3217-8787',
        'SITE_ADDR_WHATS' => '5554999300824',
        // Número no formato do wa.me: só dígitos, com DDI 55 garantido (sem duplicar
        // quando SITE_ADDR_WHATS já vem com o DDI).
        'SITE_ADDR_WHATS_TRIM' => static function (): string {

            $digits = \preg_replace(
                '/\D/',
                '',
                defined('SITE_ADDR_WHATS') ? (string)SITE_ADDR_WHATS : ''
            ) ?? '';

            if ('' === $digits) {
                return '';
            }

            return \str_starts_with($digits, '55') ? $digits : '55' . $digits;
        },
        'SITE_ADDR_ADDR' => 'Rua Pinheiro Machado, 1707',
        'SITE_ADDR_CITY' => 'Caxias do Sul',
        'SITE_ADDR_DISTRICT' => 'Centro',
        'SITE_ADDR_UF' => 'RS',
        'SITE_ADDR_ZIP' => '95020-172',
        'SITE_ADDR_COUNTRY' => 'Brasil',

        // Social config
        'SITE_SOCIAL_NAME' => 'ICC SERRA - BANCO DO POVO',
        'SITE_SOCIAL_FB' => 1,
        'SITE_SOCIAL_FB_APP' => '',
        'SITE_SOCIAL_FB_AUTHOR' => 'microcreditoiccserra',
        'SITE_SOCIAL_FB_PAGE' => 'microcreditoiccserra',
        'SITE_SOCIAL_FB_PAGE_ID' => '',
        'SITE_SOCIAL_TWITTER' => 'ICC_Serra',
        'SITE_SOCIAL_YOUTUBE' => '',
        'SITE_SOCIAL_INSTAGRAM' => 'bancodopovo.official',
        'SITE_SOCIAL_LINKEDIN' => '25001836',
    ];
