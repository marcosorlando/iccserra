#Work Control php@8.X.X Changelog
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
