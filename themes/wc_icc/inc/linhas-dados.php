<?php

declare(strict_types=1);

/**
 * Condições das linhas de crédito usadas no comparador da home (inc/linhas-comparativo.php).
 *
 * Os valores espelham os partials inc/linha_*.php (ainda usados no simulador e em parceiro/simulação).
 * Ao mudar uma condição, atualize os dois lugares até os partials antigos serem aposentados.
 *
 * @return list<array{id: string, simulador: string, nome: string, destaque: string, para: string,
 *                    limite: string, prazo: string, taxa: string, taxa_ano: string, garantia: string,
 *                    tac: string, extra_icone: string, extra_rotulo: string, extra: string, link: string}>
 */
return [
    [
        'id' => 'produtivo',
        'simulador' => 'microcredito',
        'nome' => 'Microcrédito Produtivo',
        'destaque' => 'Para o seu negócio',
        'para' => 'Para quem já tem negócio, mesmo informal: compra de mercadorias, matéria-prima, equipamentos ou capital de giro.',
        'limite' => 'R$ 600 a R$ 20 mil',
        'prazo' => '3 a 24 meses',
        'taxa' => 'até 3,99% a.m.',
        'taxa_ano' => 'até 56,45% a.a.',
        'garantia' => 'Pode ser solicitado avalista com renda compatível',
        'tac' => 'TAC de 3%',
        'extra_icone' => 'icc-icon-tac',
        'extra_rotulo' => 'IOF',
        'extra' => 'Sem cobrança de IOF',
        'link' => '/artigo/microcredito-produtivo',
    ],
    [
        'id' => 'mulher',
        'simulador' => 'mulher',
        'nome' => 'Microcrédito Mulher',
        'destaque' => 'Para mulheres empreendedoras',
        'para' => 'Linha para mulheres empreendedoras: mercadorias, matéria-prima, equipamentos ou capital de giro.',
        'limite' => 'R$ 600 a R$ 20 mil',
        'prazo' => '3 a 24 meses',
        'taxa' => 'até 3,99% a.m.',
        'taxa_ano' => 'até 56,45% a.a.',
        'garantia' => 'Pode ser solicitado avalista com renda compatível',
        'tac' => 'TAC de 3%',
        'extra_icone' => 'icc-icon-tac',
        'extra_rotulo' => 'IOF',
        'extra' => 'Sem cobrança de IOF',
        'link' => '/artigo/microcredito-mulher',
    ],
    [
        'id' => 'reforma',
        'simulador' => 'reforma',
        'nome' => 'Reforma e Ampliação',
        'destaque' => 'Para a casa ou o ponto comercial',
        'para' => 'Para reformar ou ampliar a moradia ou o estabelecimento comercial.',
        'limite' => 'R$ 600 a R$ 20 mil',
        'prazo' => '3 a 24 meses',
        'taxa' => 'até 3,99% a.m.',
        'taxa_ano' => 'até 56,45% a.a.',
        'garantia' => 'Pode ser solicitado avalista com renda compatível',
        'tac' => 'TAC de 3%',
        'extra_icone' => 'icc-icon-renda',
        'extra_rotulo' => 'Renda',
        'extra' => 'Parcela de até 30% da renda líquida comprovada',
        'link' => '/artigo/credito-para-reforma-ou-ampliacao',
    ],
    [
        'id' => 'agricola',
        'simulador' => 'agricola',
        'nome' => 'Crédito Agrícola',
        'destaque' => 'Para a agricultura familiar',
        'para' => 'Para pequenos produtores rurais: aquisição e conserto de máquinas e equipamentos agrícolas.',
        'limite' => 'R$ 600 a R$ 3 mil',
        'prazo' => '3 a 24 meses',
        'taxa' => 'até 3,99% a.m.',
        'taxa_ano' => 'até 56,45% a.a.',
        'garantia' => 'Pode ser solicitado avalista com renda compatível',
        'tac' => 'TAC de 3%',
        'extra_icone' => 'icc-icon-renda',
        'extra_rotulo' => 'Renda',
        'extra' => 'Parcela de até 20% da renda líquida comprovada',
        'link' => '/artigo/credito-agricola',
    ],
];
