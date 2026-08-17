# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## O que é este projeto

Site do ICC Serra rodando sobre o **WorkControl / Zen Control** — CMS PHP proprietário da Zen Agência Web, sem framework. O código foi migrado de PHP 7.x para **PHP 8.5** (autoload PSR-4 via Composer, `declare(strict_types=1)`, namespaces `App\`), mas **convive com código legado não migrado** (ver seção "Legado vs. migrado").

Não existe framework, router, ORM ou template engine. Tudo é `require` de arquivo PHP + classes CRUD próprias sobre PDO.

## Comandos

O `php` do PATH é **7.1** — nunca use `php` direto. Os scripts do Composer apontam explicitamente para `/opt/homebrew/opt/php@8.3/bin/php`. Para rodar o código-alvo (8.5), use `/opt/homebrew/opt/php@8.5/bin/php`.

**Atenção:** as dependências de dev (PHPStan, Rector, PHPCS, PHPUnit, php-cs-fixer, parallel-lint) e todos os scripts de qualidade estão em **`composerDEV.json`**, não em `composer.json`. O `composer.json` ativo é enxuto (só runtime) e o `vendor/bin` está vazio. Para usar o tooling, troque temporariamente `composer.json` pelo `composerDEV.json` e rode `composer install`.

```sh
# lint de sintaxe de todo o projeto
composer lint

# análise estática / estilo / refactor
composer phpstan
composer phpcs
composer phpcs:compat     # PHPCompatibility contra a versão-alvo
composer phpcbf           # corrige PSR-12 automaticamente
composer cs:fixer
composer rector
composer test             # PHPUnit (não há suíte de testes no repo hoje)

# regenera _app/Config/constants_stub.php (stub das constantes globais p/ IDE/PHPStan)
composer stub
```

Lint de um arquivo isolado:

```sh
/opt/homebrew/opt/php@8.5/bin/php -l caminho/do/arquivo.php
```

Configs de qualidade (`phpstan.neon.dist`, `phpcs.xml`, `phpunit.xml`, `.php-cs-fixer.php`) estão no `.gitignore` — podem não existir localmente.

## Arquitetura

### Bootstrap e constantes

Tudo começa em `vendor/autoload.php`, que carrega `_app/Config/bootstrap.php` → `App\Config\ConfigLoader::boot()`. Esse boot define **centenas de constantes globais** que o projeto inteiro usa direto (`BASE`, `THEME`, `INCLUDE_PATH`, `REQUIRE_PATH`, `DB_*`, `APP_*`, `LEVEL_WC_*`, `SITE_*`, `SIS_DB_*`).

Ordem e precedência (importante):

1. `.env` em `_app/Config/.env` (via vlucas/phpdotenv) — só credenciais de banco e e-mail são lidas de lá.
2. `_app/Config/Agency.inc.php` e `_app/Config/Client.inc.php` — arrays `chave => valor` (valores podem ser `Closure` para lazy eval). Aqui ficam `SITE_NAME`, dados do cliente, redes sociais.
3. Defaults hardcoded no próprio `ConfigLoader` (`BASE`, mapa de tabelas `DB_*`, módulos `APP_*`, níveis `LEVEL_WC_*`).
4. Tabela `ws_config` do banco (`loadConfigFromDatabase`).

**O passo 4 só preenche o que ainda não foi definido** — `defineIfNotDefined` / `defined()` fazem o primeiro a definir vencer. Ou seja: alterar `ws_config` no banco **não** sobrescreve `BASE`, `SITE_NAME` etc. se já estiverem no código. Para mudar `BASE`, edite `ConfigLoader::defineBasePaths()`.

`DISABLE_DB_ON_BOOT=1` e SAPI CLI pulam o acesso ao banco no boot.

### Camada de dados (`_app/Conn/`)

Quatro classes sobre PDO — `Read`, `Create`, `Update`, `Delete` — mais `Conn` (singleton da conexão). Padrão de uso em toda a base:

```php
$Read ??= new Read();
$Read->exeRead(DB_POSTS, 'WHERE post_status = :st ORDER BY post_date DESC', 'st=1');
$Read->getResult();          // array assoc ou null
$Read->fullRead('SELECT ... FROM ' . DB_USERS, 'user_id=' . $id);   // query livre
```

Os *places* são passados como **querystring** (`'key=valor&outra=valor'`), parseados com `parse_str` e bindados com tipo inferido; `limit`/`offset` são forçados a `PDO::PARAM_INT`. Nunca interpole valores no SQL — sempre use placeholders `:nome` + parse string. Nomes de tabela vêm sempre das constantes `DB_*`, nunca literais.

### Roteamento do site (front-end)

`.htaccess` manda tudo que não é arquivo/diretório para `index.php?url=...`.

`index.php` (raiz) é monolítico: abre sessão, valida `$_SESSION['userLogin']`, monta todo o `<head>` (SEO via `App\Models\Seo`), e resolve `$URL = explode('/', $url)` na seguinte ordem, contra a pasta do tema (`themes/<THEME>/`):

1. módulos especiais (posts/AMP, landing pages, thankyou pages, cartões de contato) — comparando `$URL[0]` com listas vindas do banco;
2. `inc/header.php`;
3. página do banco (`ws_pages`) → `page-<slug>.php` se existir, senão `pagina.php`;
4. `<URL[0]>.php` no tema (ex.: `artigos.php`, `artigo.php`, `pesquisa.php`);
5. `<URL[0]>/<URL[1]>.php`;
6. `404.php`;
7. `inc/footer.php`, depois os "WC Codes" (scripts injetados do banco) e os `<script>` do tema.

Há **dois blocos praticamente idênticos** de roteamento no fim do `index.php`: um usando `themes/{$_SESSION['WC_THEME']}` e outro usando `$_SESSION['REQUIRE_PATH']`. Ao mexer no roteamento, altere os dois — senão o comportamento diverge conforme a sessão.

O tema pode ser trocado em runtime por `?wctheme=<nome>` (grava em `$_SESSION['WC_THEME']`).

### Tema (`themes/wc_icc/`)

- `inc/` — partials (`header.php`, `footer.php`, `menu.php`, `sidebar.php`, formulários reaproveitados).
- `page-*.php` — override por slug de página do banco; `pagina.php` é o fallback genérico.
- `templates/*.html` — templates de e-mail com placeholders `{chave}`, populados por `App\View\View` / `_app_capture/class/Template.class.php`.
- `_ajax/` — endpoints AJAX do front (contato, leads, ouvidoria, simulador).
- `lp/`, `simulador/` — mini-apps com CSS/JS/ajax próprios.
- Assets referenciados por `INCLUDE_PATH` (URL) e `REQUIRE_PATH` (caminho relativo para `require`).

Front-end é jQuery + Bootstrap + owl.carousel + slick, tudo em `_cdn/` (raiz) e `themes/wc_icc/js/`.

### Admin (`admin/`)

`admin/index.php` é o login; `admin/dashboard.php` é o shell. Roteamento por querystring `?wc=<modulo>/<view>`:

- primeiro tenta `admin/_siswc/<wc>.php` (**módulos customizados do projeto** — leads, ouvidoria, currículos, portfólios, homepage, etc.);
- senão `admin/_sis/<wc>.php` (**módulos do core** — posts, pages, users, comments, slide, config, reports);
- o menu customizado vive em `admin/_siswc/wc_menu.php`.

Cada view de módulo começa checando permissão:

```php
$AdminLevel = LEVEL_WC_LANDING_PAGES;
if (!APP_LANDING_PAGES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div ...>ACESSO NEGADO...</div>');
}
```

Ou seja: `APP_<MODULO>` liga/desliga o módulo e `LEVEL_WC_<MODULO>` define o nível mínimo — ambos vindos do `ConfigLoader`.

### Dispatchers AJAX

Padrão em `admin/_ajax/<Modulo>.ajax.php` (e `themes/wc_icc/_ajax/`): o form envia `callback` (nome do arquivo) + `callback_action` (o case). O arquivo:

1. `session_start()` + `require vendor/autoload.php`;
2. valida `$_SESSION['userLogin']['user_level'] >= LEVEL_WC_*` (nos do admin);
3. confere `$PostData['callback'] === $CallBack`;
4. instancia CRUD com `$Read ??= new Read();`;
5. `array_map` com `strip_tags` no post;
6. `switch ($Case)` com os cases;
7. devolve `json_encode($jSON)` com chaves `trigger` (HTML de erro via `Check::ajaxErro()`), `foco`, `redirect`.

Ao criar um endpoint novo, copie a estrutura de `admin/_ajax/Leads.ajax.php` (já migrado) — não a dos endpoints do tema, que são legado.

### Outros pontos de entrada

- `tim.php` — servidor de imagens (League/Glide + Flysystem). Aceita `?src=&w=&h=&fit=` sobre três mounts: `uploads://`, `themes://` (imagens do tema ativo), `admin://`. Cache em `cache/`.
- `_api/post.php` — API JSON de posts, autenticada por `key`/`token` contra `workcontrol_api`.
- `rss.php`, `sitemap.xml` (gerado 1x/dia pelo `dashboard.php`), `maintenance.php`.
- `_cdn/cronjob.php` — carregado pelo dashboard.
- `project-tree.php` — gera árvore de diretórios.

## Legado vs. migrado

Coexistem dois estilos. Identifique antes de editar:

| | Migrado (PHP 8.5) | Legado |
|---|---|---|
| Bootstrap | `require vendor/autoload.php` | `require_once '../../../_app/Config.inc.php'` |
| Classes | `App\Helpers\Check::email()` (namespace, camelCase) | `Check::Email()`, funções soltas `AjaxErro()`, `Erro()` |
| Sintaxe | chaves, `declare(strict_types=1)`, tipagem | sintaxe alternativa `if: endif;`, sem tipos |

Exemplos de legado ainda ativo: `themes/wc_icc/_ajax/*.ajax.php`, `themes/wc_icc/_app_capture/`, partes do `index.php` e do `dashboard.php`.

**Ao tocar em arquivo legado, migre-o para o padrão novo** (namespaces, `Check::` em camelCase, chaves, `strict_types`) — é o sentido da migração documentada em `UPGRADE.md`. Não introduza código legado novo.

## Convenções

- PSR-12; `declare(strict_types=1)` em todo arquivo novo de `_app/`.
- Nomes de tabela **sempre** por constante `DB_*`; nunca string literal.
- URLs sempre por `BASE` / `INCLUDE_PATH`; `require` de tema sempre por `REQUIRE_PATH`.
- Mensagens de erro/UI em português, com o padrão visual do WC (`<b>OPPSSS:</b> ...`, `Check::erro()` / `Check::ajaxErro()`).
- Variáveis de instância CRUD nos scripts usam maiúscula inicial por convenção histórica (`$Read`, `$Create`, `$Update`, `$Delete`, `$Admin`, `$PostData`).
- Novas tabelas devem ser registradas em `ConfigLoader::defineDatabaseTableConstants()` **e** documentadas em `DATABASELOG.md` (com o `CREATE TABLE`); mudanças relevantes vão no `CHANGELOG.md`.
- `DATABASE.sql` na raiz é o dump de referência do schema.

## Segurança / operação

- `index.php` e `admin/index.php` ainda têm `error_reporting(E_ALL)` + `display_errors` ligados no topo, marcados com `// REMOVER DEPOIS DE ATUALIZAR`. Não deixe isso ativo em produção.
- `_app/Config/.env` e `deploy-ftp.env` contêm segredos e estão no `.gitignore`.
- Deploy é por FTP (`deploy-ftp.env`: `FTP_HOST`, `FTP_USER`, `FTP_PASS`, `FTP_REMOTE_DIR`).
- Ambiente é detectado por `$_SERVER['HTTP_HOST'] === 'localhost'` (escolhe `DB_*_DEV` vs `DB_*_PRODUCTION`).
