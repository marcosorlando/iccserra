<?php

use App\Conn\Read;
use App\Helpers\Check;

?>
<!--<section class="wc_videos">-->
<div class="content">
    <article class="htmlchars">
        <header class="site_header">
            <h1>
                <b>Instituição Comunitária de Crédito</b> <?= SITE_NAME; ?> - BANCO DO POVO
            </h1>
            <p>Somos uma Organização da Sociedade Civil de Interesse Público – OSCIP.</p>
        </header>

        <h2>Nosso negócio</h2>
        <p>A Instituição Comunitária de Crédito da Serra -
            <strong>ICC SERRA </strong>popularmente conhecida como
            <strong>Banco do Povo</strong> é uma organização sem fins lucrativos, com principal objetivo de facilitar o
            acesso ao crédito para pessoas que trabalham por conta própria (empreendedores informais), MEIs, micros e
            pequenos empresários. Concedemos acesso ao crédito de forma simples criando oportunidade e fornecendo
            orientação para fortalecer e fazer o seu negócio crescer. O Ministério da Justiça qualifica a
            <b>ICC SERRA - Banco do Povo</b> como Organização da Sociedade Civil de Interesse Público – OSCIP de
            microcrédito apta a conceder crédito para Capital Fixo e Capital de Giro. Tem seu funcionamento autorizado e
            regulamentado pelo Ministério da Justiça através da Lei n.º 9.790.
        </p>
        <p>Foi fundada em 16 de dezembro de 2001 com data de registro em 05 de abril de 2002, tendo abrangência
            Estadual. A Sede da matriz da instituição fica localizada na Rua Ernesto Alves, 2654, Bairro São Pelegrino,
            na cidade de Caxias do Sul-RS.</p>

        <p>Em 2010, visando aumentar a sua área de abrangência o Banco do Povo buscou parcerias com associações
            municipais e prefeituras da região serrana do Rio Grande do Sul mais preisamente no Corede - Serra.</p>

        <p>A partir de então, inicia-se um processo sólido e progressivo de expansão. Com o crescimento da Agência
            Matriz em Caxias do Sul e devido a demanda regional abriu os postos de atendimento em Vacaria, Cotiporã,
            Cambará do Sul, Garibaldi, Casca, Marau, Passo Fundo. Em 2016 instalando-se em Bento Gonçalves com uma nova
            sede. Continuando em 2017 com as parcerias de desenvolvimento nos municípios de Lajeado, Encantado, Estrela,
            Serafina Correa, Veranópolis, Guaporé e Canela.
        </p>
        <p>A ICC Serra cumpre, ano a ano, sua Missão com eficiência, proporcionando um atendimento personalizado aos
            seus clientes, contribuindo para o fomento da economia e a melhoria da qualidade de vida no Sul do Brasil,
            ajudando seus clientes a alcançarem seus objetivos e metas de crescimento.</p>

        <h2>Nosso perfil de clientes</h2>
        <p>
            <strong>Microcrédito:</strong> Comerciantes, empresários, agricultores, empreendedores formais ou informais,
            que tenham no mínimo seis meses atuação na mesma atividade.
        </p>
        <!--<p><strong>Crédito Social:</strong> aposentados, pensionistas, assalariados em geral. </p>-->
        <h2>Nossas vantagens</h2>
        <p>O Agente de Crédito sempre visita o tomador do empréstimo e faz a análise do crédito acerca das necessidades
            do cliente. Muitas vezes o cliente solicita um empréstimo acima do necessário, e isso é detectado na visita.
            Em um banco tradicional, se o cliente tem capacidade de pagamento, o empréstimo é efetivado, não levando em
            consideração a real necessidade do cliente.</p>
    </article>
    <div class="clear"></div>
    <?php
        require REQUIRE_PATH . '/inc/docs.php'; ?>
</div>
<?php
    require REQUIRE_PATH . '/inc/cta.inc.php'; ?>
<!--</section>-->
<section class="wc_bio" id="a">

    <div class="content">
        <header class="site_header">
            <h1>Conheça nossa
                <b>cultura</b>
            </h1>
            <p>Identificou-se com a cultura da ICC Serra, venha-nos fazer uma visita, nossa equipe estará pronta para
                atendê-lo(a)!</p>
        </header>

        <div class="bio_logo">
            <img title="<?= SITE_NAME; ?> Sobre Microcrédito ICC Serra"
                 alt="<?= SITE_NAME; ?> Missão, Visão, Princípios e Valores"
                 src="<?= INCLUDE_PATH; ?>/images/iccserra-logo-b.png"/>
        </div>

        <div class="bio_content">

            <h3>MISSÃO:</h3>
            <p>"Atuar como uma Instituição de Microcrédito parceira no desenvolvimento de pequenos empreendedores tanto
                formais como informais, no sul do Brasil, fomentando a economia regional, levando qualidade de vida às
                pessoas, e integrando socialmente esses empreendedores, sem acesso ao crédito em bancos comerciais e
                sermos uma instituição auto sustentável."</p>

            <h3>VISÃO:</h3>
            <p>"Ser uma Instituição de Microcrédito, reconhecida em todo território brasileiro, principalmente no sul do
                país, como parceira de desenvolvimento a pequenos empreendedores."</p>

            <h3>VALORES</h3>
            <p>"Ética, transparência, cooperativismo, valorização do ser humano, inovação, agilidade, comprometimento e
                determinação."</p>
        </div>
    </div>
    <div class="clear"></div>
    
    <?php
        require REQUIRE_PATH . '/inc/partners.inc.php'; ?>

</section>


<article class="wc_social">
    <div class="content">
        <header class="site_header">
            <h1>Siga o
                <b><?= SITE_SOCIAL_NAME; ?></b> no Facebook
            </h1>
            <p>Acompanhe as publicações periódicas do
                <b>ICC SERRA</b> nas mídias sociais
            </p>
        </header>

        <div class="lead_social_face">
            <div class="fb-like" style="z-index: 9; max-width: 100%; overflow: hidden;"
                 data-href="https://facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>" data-layout="standard" data-action="like"
                 data-show-faces="true" data-share="true" data-width="600"></div>
        </div>

        <div class="clear"></div>
    </div>
</article>


<section class="wc_more" id="c">
    <div class="content">
        <header class="site_header">
            <h1>
                <b>Linhas de Crédito.</b> Tudo que você precisa está ao seu alcance.
            </h1>
            <p>Separamos exclusivamente para você, diferentes linhas de crédito clique em
                <b><strong>LER MAIS</strong></b> para ver os benefícios!
            </p>
        </header>
        
        <?php
            $Read ??= new Read();
            $OffsetpostGet = (!empty($Offsetpost) ? implode(', ', $Offsetpost) : 0);
            $Read->exeRead(
                DB_POSTS,
                sprintf(
                    'WHERE post_id NOT IN(%s) AND post_status = 1 AND post_date <= NOW() AND post_category = 2 '
                    . 'ORDER BY post_date DESC LIMIT :limit OFFSET :offset',
                    $OffsetpostGet
                ),
                'offset=0&limit=8'
            );
            if ($Read->getResult()) {
                foreach ($Read->getResult() as $Bora) {
                    extract($Bora);
                    $BOX = 4;
                    require REQUIRE_PATH . '/inc/post.php';
                }
            } else {
                echo Check::erro(
                    "<div style='text-align: center; display: block; width: 100%;'>"
                    . 'Ainda não existem posts aqui! Volte mais tarde :)</div>',
                    E_USER_NOTICE
                );
            }
        ?>
        <div class="clear"></div>
    </div>
</section>
<!--Inserir include instagram-->
<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
