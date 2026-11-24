#Work Control php@8.X.X Changelog
# 23/11/2026 - LPs de Destino para campanhas Google

+ NEW módulo `gadslps` no admin (Nova LP, Ver LPs) para gerir as páginas de destino das campanhas Google Ads, sem formulário: os CTAs levam ao `/simulador` (fim do funil);
+ NEW tags Google dinâmicas por página (GA4 `G-`, Google Ads `AW-` + rótulo de conversão, GTM `GTM-`) injetadas no `<head>` da LP, com fallback para as tags globais do site;
+ NEW conversão do Google Ads disparada na etapa final do simulador (simulação concluída, em `scripts.js`), usando o `send_to` da LP de origem (cookie `gads_conv`, 90 dias); o CTA só repassa `gclid` e UTMs na URL;
+ NEW conteúdo da LP editável por campos no admin (topo, selos, dores, passos, detalhes e condições, prova social, FAQ, chamada final, rodapé legal, WhatsApp) com repetidores e upload da imagem do topo; salvo em `page_data` (JSON) e `uploads/gadslps/`;
+ NEW `themes/wc_icc/gads-lp.php` (documento próprio, fora do layout do site) e `themes/wc_icc/gads/inc/lp-body.php` (estrutura fixa, igual em todas as LPs), assets em `themes/wc_icc/gads/`; menu e rodapé listam as LPs publicadas;
+ NEW `database/2026-11-23_gads_lps_seed.sql` popula as 5 LPs sem precisar de PHP (idempotente, pelo slug);
+ NEW `bin/import-gads-lps.php` lê os HTMLs de `lp/` e preenche os campos estruturados e a imagem do topo;
+ NEW constantes `APP_GADS_LPS`, `LEVEL_WC_GADS_LPS`, `DB_GADS_LPS`; SQL em `database/2026-11-23_gads_lps.sql`.

# 05/10/2026 - Folha única do tema e formulários padronizados

+ NEW `themes/wc_icc/css/style.css` é a folha única do tema: tokens (`tokens.css`) e legado do WC (`wc-legacy.css`) incorporados, organizada por seções numeradas (00 Tokens … 14 Impressão) e por página (Home, Blog, Simulador, Trabalhe conosco, Ouvidoria, Institucional);
+ NEW bloco único de formulários (seção 03.4) com variáveis `--form-*` e `--field-*`: legenda mais perto do campo, campos com 44px, espaçamento menor; vale para todos os forms do front e para as buscas;
+ FIX formulário do Parceiro passa a usar linhas (`.flex` com `.grow0`/`.grow2`) em vez de empilhar 16 campos; botões de envio centralizados em todos os formulários;
+ FIX `<br>` soltos nos formulários e o estilo de citação do blog no texto do simulador;
+ DEL `tokens.css`, `wc-legacy.css` e seus `.min`; regras sem uso (`.panel`, `.dashboard`, `.course_segment`, `.wc_conversion_content`, utilitários `bg_*`/`border_color_*` sem uso). Backup em `css/_backup/2026-10-05/*.bak`.

# 02/10/2026 - CRM do Simulador

+ NEW módulo `crm` no admin (Funil, Etapas, Mensagens) com quadro Kanban e drag and drop entre etapas;
+ NEW simulações do site gravadas em `ws_crm_clients` + `ws_crm_simulations` (cliente único por e-mail);
+ NEW etapas do funil editáveis (adicionar, renomear, cor, reordenar, remover movendo os leads);
+ NEW status do atendimento (Em aberto / Convertido / Perdido) e linha do tempo por cliente;
+ NEW envio de e-mail e abertura do WhatsApp com mensagens pré-definidas e variáveis ({nome}, {valor}...);
+ NEW `App\Models\Crm`, `admin/_ajax/Crm.ajax.php`, constantes `APP_CRM`, `LEVEL_WC_CRM`, `DB_CRM_*`;
+ FIX menu do admin com Documentos, Postos de Atendimento e CTAs triplicados.

# 02/10/2026 - Correções de módulos do admin

+ FIX `_app/Config.inc.php` e `_app/Config/Config.inc.php` carregam `vendor/autoload.php` (fatal `Class "App\Config\ConfigLoader" not found`);
+ FIX `Postos.ajax.php`, `Documents.ajax.php` e `Slides.ajax.php` migrados para o padrão PHP 8.5 (namespaces, `Check::ajaxErro()`, chaves);
+ FIX imports faltando (`Check`, `Read`, `Update`, `Upload`, `Create`) em Codes, Config, Curriculos, Ouvidoria, Albums, `_sis/config/codes`, `_sis/posts/update`, `_sis/slide/home`, `_siswc/albums`;
+ FIX parceiros: `exit` após redirect no create, busca com variável indefinida, auto-trash, drag and drop apontando para `Depositions`, caminho da imagem antiga e validação do upload;
+ FIX `exit` após `header('Location')` em depositions, hello, materiais, videos, pages e posts; create de postos/documentos/parceiros não entra mais em loop se o rascunho falhar;
+ FIX `Upload::image()` e helpers `Googl`/`Instagram`/`Sitemap`: removidos `imagedestroy()`/`curl_close()` (descontinuados no PHP 8.5; o ErrorHandler convertia o aviso em exceção e todo upload de imagem dava 500);
+ FIX `workcontrol.js`: handler global de `ajaxError` encerra o loader e exibe o erro (antes ficava girando indefinidamente).

# WC - Version 3.5.0

## POST FIXES:
+ FIX post design,
+ FIX post Actions,
+ FIX Instant Articles Alert,
+ FIX Category Navigation,
+ FIX TAG Navigation
