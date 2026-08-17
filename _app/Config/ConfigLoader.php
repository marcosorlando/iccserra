<?php

    declare(strict_types=1);

    namespace App\Config;

    use App\Bootstrap\ErrorHandler;
    use App\Conn\Read;
    use Closure;
    use Dotenv\Dotenv;
    use RuntimeException;

    final class ConfigLoader
    {
        private static bool $booted = false;

        public static function boot(): void
        {

            if (self::$booted) {
                return;
            }

            self::$booted = true;

            self::loadEnv();
            self::validateRequiredEnv();
            self::defineDatabaseTableConstants();
            self::defineCacheConfig();
            self::defineDatabaseCredentials();
            self::loadConfigFromDatabase();
            self::defineAgencyConstants();
            self::defineClientConstants();
            self::defineBasePaths();
            self::defineAdminDefaults();
            self::defineMediaDefaults();
            self::defineApplicationModules();
            self::defineLevelPermissions();
            self::defineSegmentationDefaults();
            self::defineLinkAndAccountConfig();
            self::configureErrorHandling();
        }

        private static function loadEnv(): void
        {

            $envDir = __DIR__;

            if (file_exists($envDir . '/.env')) {
                $dotenv = Dotenv::createImmutable($envDir);
                $dotenv->safeLoad();
            }
        }

        private static function defineAgencyConstants(): void
        {

            /** @var array<string, \Closure|scalar> $agency */
            $agency = require __DIR__ . '/Agency.inc.php';
            self::defineConstants(self::withEnvOverrides($agency));
        }

        /**
         * @param array<string, \Closure|scalar> $constants
         */
        private static function defineConstants(array $constants): void
        {

            foreach ($constants as $name => $value) {
                self::defineIfNotDefined($name, $value);
            }
        }

        /**
         * @param null|\Closure|scalar $value
         */
        private static function defineIfNotDefined(string $name, $value): void
        {

            if (defined($name)) {
                return;
            }

            if ($value instanceof Closure) {
                $value = $value();
            }

            define($name, $value);
        }

        private static function defineClientConstants(): void
        {

            /** @var array<string, \Closure|scalar> $client */
            $client = require __DIR__ . '/Client.inc.php';
            self::defineConstants(self::withEnvOverrides($client));
        }

        private static function defineBasePaths(): void
        {

            $isLocal = self::isLocalRequest();

            // Local: https://localhost/<APP_LOCALHOST>  |  Produção: https://<APP_DOMAIN>
            $localPath = trim((string)self::env('APP_LOCALHOST', ''), '/');
            $localBase = 'https://localhost' . ('' !== $localPath ? '/' . $localPath : '');

            $productionDomain = trim((string)self::env('APP_DOMAIN', ''), '/');
            $productionBase = '' !== $productionDomain
                ? 'https://' . $productionDomain
                : self::baseFromCurrentHost();

            self::defineIfNotDefined(
                'BASE',
                $isLocal ? $localBase : $productionBase
            );

            if (!defined('THEME')) {
                define('THEME', self::resolveTheme());
            }

            self::defineIfNotDefined('INCLUDE_PATH', BASE . '/themes/' . THEME);
            self::defineIfNotDefined('REQUIRE_PATH', 'themes/' . THEME);
        }

        /**
         * Resolve o tema ativo na ordem: sessão (troca via ?wctheme=) > APP_THEME do .env >
         * ws_config > 'zen'. Só aceita um candidato cuja pasta exista de fato em themes/ —
         * assim uma sessão antiga apontando para tema removido não derruba o site.
         */
        private static function resolveTheme(): string
        {

            $candidates = [];

            $sessionTheme = $_SESSION['WC_THEME'] ?? null;
            if (is_string($sessionTheme)) {
                $candidates[] = $sessionTheme;
            }

            $candidates[] = (string)self::env('APP_THEME', '');
            $candidates[] = 'zen';

            foreach ($candidates as $candidate) {
                if (self::themeExists($candidate)) {
                    return $candidate;
                }
            }

            // Nenhum candidato existe: devolve o primeiro configurado para que a mensagem
            // de erro do index.php aponte o tema realmente esperado.
            foreach ($candidates as $candidate) {
                if ('' !== trim($candidate)) {
                    return trim($candidate);
                }
            }

            return 'zen';
        }

        /**
         * Confere se themes/<tema> existe. O nome é restrito a [A-Za-z0-9_-] para
         * impedir que um ?wctheme= malicioso vire path traversal nos require do index.php.
         */
        public static function themeExists(string $theme): bool
        {

            $theme = trim($theme);

            if ('' === $theme || 1 !== preg_match('/^[A-Za-z0-9_-]+$/', $theme)) {
                return false;
            }

            return is_dir(dirname(__DIR__, 2) . '/themes/' . $theme);
        }

        /**
         * Último recurso para BASE quando APP_DOMAIN não está configurado: deriva do host da requisição.
         */
        private static function baseFromCurrentHost(): string
        {

            $host = self::currentHost();

            return '' === $host ? '' : 'https://' . $host;
        }

        /**
         * Host da requisição, sem porta e em minúsculas. Vazio em CLI.
         */
        private static function currentHost(): string
        {

            $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');

            if (!is_string($host) || '' === $host) {
                return '';
            }

            // Remove a porta ('localhost:8080'), preservando IPv6 entre colchetes.
            if (!str_starts_with($host, '[')) {
                $host = preg_replace('/:\d+$/', '', $host) ?? $host;
            }

            return strtolower(trim($host));
        }

        private static function defineAdminDefaults(): void
        {

            $defaults = [
                'ADMIN_NAME' => self::resolveEnvValue('ADMIN_NAME', 'Zen Control'),
                'ADMIN_DESC' => self::resolveEnvValue(
                    'ADMIN_DESC',
                    'O Zen Control é um sistema de gestão de conteúdo profissional gerido pela Zen Agência Web, '
                    . 'desenvolvimento web e marketing digital.'
                ),
                'ADMIN_MODE' => self::resolveEnvValue('ADMIN_MODE', 1),
                'ADMIN_WC_CUSTOM' => self::resolveEnvValue('ADMIN_WC_CUSTOM', 1),
                'ADMIN_MAINTENANCE' => self::resolveEnvValue('ADMIN_MAINTENANCE', 0),
                'ADMIN_VERSION' => self::resolveEnvValue('ADMIN_VERSION', '3.5.0'),
                'MAIL_HOST' => self::resolveEnvValue('MAIL_HOST', 'smtp.gmail.com'),
                'MAIL_PORT' => self::resolveEnvValue('MAIL_PORT', '587'), // 587 sem autenticação ou 465 autenticado
                'MAIL_USER' => self::resolveEnvValue('MAIL_USER', ''),
                'MAIL_SMTP' => self::resolveEnvValue('MAIL_SMTP', ''),
                'MAIL_PASS' => self::resolveEnvValue('MAIL_PASS', ''),
                'MAIL_SENDER' => self::resolveEnvValue('MAIL_SENDER', 'ICC SERRA - Banco do Povo'),
                'MAIL_MODE' => self::resolveEnvValue(
                    'MAIL_MODE',
                    self::resolveEnvValue('MAIL_ENCRYPTION', 'tls')
                ),
                'MAIL_TESTER' => self::resolveEnvValue('MAIL_TESTER', 'testes@zen.ppg.br'),
            ];

            self::defineConstants($defaults);
        }

        private static function defineMediaDefaults(): void
        {

            $defaults = [
                'IMAGE_W' => 1200,
                'IMAGE_H' => 628,
                'THUMB_W' => 800,
                'THUMB_H' => 800,
                'AVATAR_W' => 500,
                'AVATAR_H' => 500,
                'SLIDE_W' => 1920,
                'SLIDE_H' => 900,
                'VIDEO_W' => 1280,
                'VIDEO_H' => 720,
            ];

            self::defineConstants(self::withEnvOverrides($defaults));
        }

        private static function defineApplicationModules(): void
        {

            $defaults = [
                'APP_POSTS' => 1,
                'APP_POSTS_AMP' => 0,
                'APP_POSTS_INSTANT_ARTICLE' => 0,
                'APP_SEARCH' => 1,
                'APP_PAGES' => 1,
                'APP_COMMENTS' => 1,
                'APP_SLIDE' => 1,
                'APP_USERS' => 1,
                'APP_VIDEOS' => 0,
                'APP_MATERIALS' => 1,
                'APP_DEPOSITIONS' => 1,
                'APP_ALBUMS' => 0,
                'APP_CV' => 1,
                'APP_OUVIDORIA' => 1,
                'APP_HELLO' => 1,
                'APP_CTAS' => 1,
                'APP_LANDING_PAGES' => 1,
                'APP_THANKYOU_PAGES' => 1,
                'APP_LEADS' => 1,
                'APP_PARTNERS' => 1,
                'APP_LINKTREE' => 1,
                /*@TODO MÓDULOS PARA MIGRAR*/
                'APP_DOCUMENTS' => 1,
                'APP_SERVICES_STATIONS' => 1,
                'APP_DEBUG' => self::env('APP_DEBUG'),
            ];

            self::defineConstants(self::withEnvOverrides($defaults));
        }

        private static function defineLevelPermissions(): void
        {

            $levels = [
                'LEVEL_WC_CTAS' => 6,
                'LEVEL_WC_DOCUMENTS' => 6,
                'LEVEL_WC_SERVICES_STATIONS' => 6,

                'LEVEL_WC_POSTS' => 6,
                'LEVEL_WC_COMMENTS' => 6,
                'LEVEL_WC_LINKTREE' => 6,
                'LEVEL_WC_PAGES' => 9,
                'LEVEL_WC_SLIDES' => 9,
                'LEVEL_WC_REPORTS' => 9,
                'LEVEL_WC_USERS' => 9,
                'LEVEL_WC_VIDEOS' => 9,
                'LEVEL_WC_DEPOSITIONS' => 9,
                'LEVEL_WC_PARTNERS' => 9,
                'LEVEL_WC_ALBUMS' => 9,
                'LEVEL_WC_CV' => 9,
                'LEVEL_WC_OUVIDORIA' => 9,
                'LEVEL_WC_HELLO' => 9,
                'LEVEL_WC_LANDING_PAGES' => 9,
                'LEVEL_WC_THANKYOU_PAGES' => 9,
                'LEVEL_WC_LEADS' => 9,
                'LEVEL_WC_CONFIG_MASTER' => 10,
                'LEVEL_WC_CONFIG_API' => 10,
                'LEVEL_WC_CONFIG_CODES' => 10,
            ];

            self::defineConstants(self::withEnvOverrides($levels));
        }

        private static function defineSegmentationDefaults(): void
        {

            $defaults = [
                'SEGMENT_FB_PAGE_ID' => '',
                'SEGMENT_FB_PIXEL_ID' => '751568271701309',
                'SEGMENT_WC_USER' => 1,
                'SEGMENT_WC_BLOG' => 1,
                'SEGMENT_GL_ANALYTICS' => 'G-PH03ZEG60N',
                'SEGMENT_GL_TAGMANAGER' => 'GTM-TG94CFR',
                'SEGMENT_GL_ADWORDS_ID' => 'AW-821458129',
                'SEGMENT_GL_ADWORDS_LABEL' => ''
            ];

            self::defineConstants(self::withEnvOverrides($defaults));
        }

        private static function defineLinkAndAccountConfig(): void
        {

            $defaults = [
                'APP_LINK_POSTS' => 1,
                'APP_LINK_PAGES' => 1,
                'APP_LINK_PRODUCTS' => 1,
                'APP_LINK_PROPERTIES' => 1,
                'ACC_MANAGER' => 1,
                'ACC_TAG' => 'Minha Conta',
                'COMMENT_MODERATE' => 1,
                'COMMENT_ON_POSTS' => 1,
                'COMMENT_ON_PAGES' => 0,
                'COMMENT_ON_PRODUCTS' => 0,
                'COMMENT_SEND_EMAIL' => 1,
                'COMMENT_ORDER' => 'DESC',
                'COMMENT_RESPONSE_ORDER' => 'ASC'
            ];

            self::defineConstants(self::withEnvOverrides($defaults));
        }

        private static function defineCacheConfig(): void
        {

            self::defineIfNotDefined('SIS_CACHE_TIME', self::resolveEnvValue('SIS_CACHE_TIME', 10));
            self::defineIfNotDefined('SIS_CONFIG_WC', self::resolveEnvValue('SIS_CONFIG_WC', 1));
            self::defineIfNotDefined('DB_AUTO_TRASH', self::resolveEnvValue('DB_AUTO_TRASH', 1));
            self::defineIfNotDefined('DB_AUTO_PING', self::resolveEnvValue('DB_AUTO_PING', 0));
        }

        private static function defineDatabaseTableConstants(): void
        {

            $tables = [
                'DB_CONF' => 'ws_config',
                'DB_USERS' => 'ws_users',
                'DB_USERS_ADDR' => 'ws_users_address',
                'DB_USERS_NOTES' => 'ws_users_notes',
                'DB_POSTS' => 'ws_posts',
                'DB_POSTS_IMAGE' => 'ws_posts_images',
                'DB_CATEGORIES' => 'ws_categories',
                'DB_SEARCH' => 'ws_search',
                'DB_PAGES' => 'ws_pages',
                'DB_PAGES_IMAGE' => 'ws_pages_images',
                'DB_COMMENTS' => 'ws_comments',
                'DB_COMMENTS_LIKES' => 'ws_comments_likes',
                'DB_HELLO' => 'ws_hellobar',
                'DB_SLIDES' => 'ws_slides',
                'DB_VIEWS_VIEWS' => 'ws_siteviews_views',
                'DB_VIEWS_ONLINE' => 'ws_siteviews_online',
                'DB_WC_API' => 'workcontrol_api',
                'DB_WC_CODE' => 'workcontrol_code',
                'DB_YOUTUBE' => 'ws_youtube',
                'DB_MATERIAIS' => 'ws_materiais',
                'DB_MATCATEGORIES' => 'ws_matcategories',
                'DB_LEADS' => 'ws_leads',
                'DB_ALBUMS' => 'ws_albums',
                'DB_ALBUMS_IMAGE' => 'ws_albums_images',
                'DB_DEPOSITIONS' => 'ws_depositions',
                'DB_PARTNERS' => 'ws_partners',
                'DB_LANDING_PAGES' => 'ws_landingpages',
                'DB_LANDING_PAGES_IMAGES' => 'ws_landingpages_images',
                'DB_THANKYOU_PAGES' => 'ws_thankyoupages',
                'DB_PDT_TRAVI' => 'ws_products_travi',
                'DB_PDT_IMAGE_TRAVI' => 'ws_products_images_travi',
                'DB_PDT_IMAGE_CAT_TRAVI' => 'ws_products_images_cat_travi',
                'DB_PDT_GALLERY_TRAVI' => 'ws_products_gallery_travi',
                'DB_PDT_CATS_TRAVI' => 'ws_products_categories_travi',
                'DB_PDT_PROCESS_TRAVI' => 'ws_products_processes_travi',
                'DB_PDT_FORMAT_TRAVI' => 'ws_products_formats_travi',
                'DB_SVC' => 'ws_services',
                'DB_SVC_IMAGE' => 'ws_services_images',
                'DB_SVC_GALLERY' => 'ws_services_gallery',
                'DB_SEG' => 'ws_segments',
                'DB_SEG_IMAGE' => 'ws_segments_images',
                'DB_SEG_GALLERY' => 'ws_segments_gallery',
                'DB_CERT' => 'ws_certifications',
                'DB_CERT_IMAGE' => 'ws_certifications_images',
                'DB_REPRESENTATIVES' => 'ws_representatives',
                'DB_STATES' => 'states',
                'DB_CITIES' => 'cities',
                'DB_CURIOSITIES' => 'ws_curiosities',
                'DB_CV' => 'ws_job_candidates',
                'DB_OUVIDORIA' => 'ws_ouvidoria',
                'DB_CARD_USER' => 'trv_card_user',
                'DB_CTAS' => 'ws_ctas',
                'DB_HOMEPAGE' => 'ws_home',
                'DB_PORTIFOLIO' => 'ws_portifolio',
                'DB_PORTIFOLIO_IMAGES' => 'ws_portifolio_images',
                'DB_SKILLSPAGE' => 'ws_skills',
                'DB_ABOUTPAGE' => 'ws_about',
                'DB_CATEGORIES_PORTIFOLIO' => 'ws_categories_portifolio',
                /*IMPORTADOS*/
                'DB_DOCUMENTS_CATEGORIES' => 'ws_docs_categories',
                'DB_DOCS_CATEGORIES' => 'ws_docs_categories',
                'DB_DOCUMENTS' => 'ws_docs',
                'DB_SERVICES_STATIONS' => 'ws_services_stations',
            ];

            self::defineConstants(self::withEnvOverrides($tables));
        }

        private static function defineDatabaseCredentials(): void
        {

            $isLocal = self::isLocalRequest();

            if ($isLocal) {
                self::defineIfNotDefined('SIS_DB_HOST', self::env('DB_HOST_DEV', 'localhost'));
                self::defineIfNotDefined('SIS_DB_USER', self::env('DB_USER_DEV', 'root'));
                self::defineIfNotDefined('SIS_DB_PASS', self::env('DB_PASSWORD_DEV', ''));
                self::defineIfNotDefined('SIS_DB_NAME', self::env('DB_NAME_DEV', ''));
            } else {
                self::defineIfNotDefined('SIS_DB_HOST', self::env('DB_HOST_PRODUCTION', 'localhost'));
                self::defineIfNotDefined('SIS_DB_USER', self::env('DB_USER_PRODUCTION', ''));
                self::defineIfNotDefined('SIS_DB_PASS', self::env('DB_PASSWORD_PRODUCTION', ''));
                self::defineIfNotDefined('SIS_DB_NAME', self::env('DB_NAME_PRODUCTION', ''));
            }
        }

        private static function env(string $key, mixed $default = null): mixed
        {

            if (isset($_ENV[$key])) {
                return $_ENV[$key];
            }

            // getenv() devolve false (não null) quando a variável não existe,
            // por isso o ?? sozinho nunca chegaria ao $default.
            $value = getenv($key);

            return false !== $value ? $value : $default;
        }

        private static function validateRequiredEnv(): void
        {

            $commonRequired = ['APP_DOMAIN', 'APP_THEME'];
            if (self::isLocalRequest()) {
                $commonRequired[] = 'APP_LOCALHOST';
            }

            $dbRequired = ['DB_HOST_DEV', 'DB_NAME_DEV', 'DB_USER_DEV'];
            $mailRequired = ['MAIL_HOST', 'MAIL_PORT', 'MAIL_USER', 'MAIL_SMTP', 'MAIL_SENDER', 'MAIL_MODE'];

            $requiredKeys = array_merge($commonRequired, $dbRequired, $mailRequired);
            if (self::isProductionEnvironment()) {
                $requiredKeys = array_merge(
                    $requiredKeys,
                    [
                        'DB_HOST_PRODUCTION',
                        'DB_NAME_PRODUCTION',
                        'DB_USER_PRODUCTION',
                        'DB_PASSWORD_PRODUCTION',
                        'MAIL_PASS'
                    ]
                );
            }

            $missingKeys = self::missingEnvKeys($requiredKeys);
            if ([] === $missingKeys) {
                return;
            }

            $message = 'Variáveis obrigatórias ausentes no .env: ' . implode(', ', $missingKeys);
            if (self::isProductionEnvironment()) {
                throw new RuntimeException($message);
            }

            trigger_error($message, E_USER_WARNING);
        }

        /**
         * @param array<int, string> $keys
         *
         * @return array<int, string>
         */
        private static function missingEnvKeys(array $keys): array
        {

            $missing = [];
            foreach ($keys as $key) {
                $value = self::env($key, null);
                if (!is_scalar($value) || '' === trim((string)$value)) {
                    $missing[] = $key;
                }
            }

            return $missing;
        }

        private static function isProductionEnvironment(): bool
        {

            $appEnv = strtolower((string)self::env('APP_ENV', 'local'));

            return in_array($appEnv, ['production', 'prod'], true)
                || ('' !== self::currentHost() && !self::isLocalRequest());
        }

        /**
         * Detecta ambiente local: APP_ENV de desenvolvimento, host local
         * (com ou sem porta) ou domínio de teste.
         */
        private static function isLocalRequest(): bool
        {

            $appEnv = strtolower((string)self::env('APP_ENV', ''));
            if (in_array($appEnv, ['local', 'development', 'dev'], true)) {
                return true;
            }

            $host = self::currentHost();
            if ('' === $host) {
                return false;
            }

            if (in_array($host, ['localhost', '127.0.0.1', '::1', '[::1]'], true)) {
                return true;
            }

            foreach (['.local', '.test', '.localhost'] as $suffix) {
                if (str_ends_with($host, $suffix)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Permite sobrescrever qualquer constante pelo .env, respeitando o tipo do valor padrão.
         *
         * @param array<string, \Closure|scalar> $constants
         *
         * @return array<string, mixed>
         */
        private static function withEnvOverrides(array $constants): array
        {

            foreach ($constants as $name => $value) {
                if (!self::hasEnvValue($name)) {
                    // Sem override no .env, o valor original é mantido intacto.
                    // Closures NÃO podem ser avaliadas aqui: elas dependem de constantes
                    // definidas antes na mesma lista (ex.: SITE_ADDR_WHATS_TRIM lê
                    // SITE_ADDR_WHATS), então quem avalia é o defineIfNotDefined(), em ordem.
                    continue;
                }

                $constants[$name] = self::resolveEnvValue(
                    $name,
                    $value instanceof Closure ? null : $value
                );
            }

            return $constants;
        }

        private static function resolveEnvValue(string $name, mixed $default): mixed
        {

            $value = self::env($name, null);
            if (null === $value) {
                return $default;
            }

            if (is_bool($default)) {
                return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default;
            }

            if (is_int($default)) {
                return is_numeric($value) ? (int)$value : $default;
            }

            if (is_float($default)) {
                return is_numeric($value) ? (float)$value : $default;
            }

            if (
                is_scalar($value) && '' === trim((string)$value) && is_scalar($default) && '' !== trim(
                    (string)$default
                )
            ) {
                return $default;
            }

            return is_scalar($value) ? (string)$value : $default;
        }

        private static function loadConfigFromDatabase(): void
        {

            $sisConfig = (int)(defined('SIS_CONFIG_WC') ? constant('SIS_CONFIG_WC') : 0);
            $isCli = PHP_SAPI === 'cli' || PHP_SAPI === 'cli-server';
            $disableDb = '1' === getenv('DISABLE_DB_ON_BOOT');

            if (0 === $sisConfig || $isCli || $disableDb) {
                return;
            }

            if (!defined('DB_CONF') || '' === trim((string)constant('DB_CONF'))) {
                return;
            }

            $read = new Read();
            $read->fullRead('SELECT conf_key, conf_value FROM ' . DB_CONF);
            $configs = $read->getResult();
            if (!is_array($configs) || [] === $configs) {
                return;
            }

            foreach ($configs as $configRow) {
                if (!is_array($configRow)) {
                    continue;
                }

                $constantName = self::stringValue($configRow['conf_key'] ?? null);
                if (null === $constantName || !self::isDatabaseOverridableConstant($constantName)) {
                    continue;
                }

                // O tema do .env (APP_THEME) e o da sessão (?wctheme=) vencem o valor gravado no banco.
                if (
                    'THEME' === $constantName
                    && (self::hasEnvValue('APP_THEME')
                        || (isset($_SESSION['WC_THEME']) && '' !== $_SESSION['WC_THEME']))
                ) {
                    continue;
                }

                if (self::hasEnvValue($constantName) || defined($constantName)) {
                    continue;
                }

                $value = $configRow['conf_value'] ?? null;
                if (null === $value) {
                    define($constantName, '');

                    continue;
                }

                $resolvedValue = self::resolveDatabaseConstantValue($constantName, $value);
                if (is_scalar($resolvedValue)) {
                    define($constantName, $resolvedValue);
                }
            }
        }

        private static function resolveDatabaseConstantValue(string $constantName, mixed $value): mixed
        {

            if (!is_scalar($value)) {
                return null;
            }

            $raw = trim((string)$value);
            if (self::isIntegerLikeConstant($constantName)) {
                $booleanInt = self::booleanLikeToInt($raw);
                if (null !== $booleanInt) {
                    return $booleanInt;
                }

                if (is_numeric($raw)) {
                    return (int)$raw;
                }
            }

            return (string)$value;
        }

        private static function isIntegerLikeConstant(string $constantName): bool
        {

            if (
                in_array(
                    $constantName,
                    ['ADMIN_MODE', 'ADMIN_WC_CUSTOM', 'ADMIN_MAINTENANCE', 'SITE_SOCIAL_FB'],
                    true
                )
            ) {
                return true;
            }

            if (in_array($constantName, ['SEGMENT_WC_USER', 'SEGMENT_WC_BLOG'], true)) {
                return true;
            }

            $integerPrefixes = [
                'APP_',
                'COMMENT_',
                'ACC_',
                'LEVEL_WC_',
                'IMAGE_',
                'THUMB_',
                'AVATAR_',
                'SLIDE_',
                'VIDEO_'
            ];
            foreach ($integerPrefixes as $prefix) {
                if (str_starts_with($constantName, $prefix)) {
                    return true;
                }
            }

            return false;
        }

        private static function booleanLikeToInt(string $value): ?int
        {

            if ('' === $value) {
                return null;
            }

            $normalized = strtolower($value);
            if (in_array($normalized, ['1', 'true', 'on', 'yes', 'sim'], true)) {
                return 1;
            }

            if (in_array($normalized, ['0', 'false', 'off', 'no', 'nao', 'não'], true)) {
                return 0;
            }

            return null;
        }

        private static function isDatabaseOverridableConstant(string $constantName): bool
        {

            if ('THEME' === $constantName) {
                return true;
            }

            $allowedPrefixes = [
                'APP_',
                'COMMENT_',
                'ACC_',
                'SEGMENT_',
                'IMAGE_',
                'THUMB_',
                'AVATAR_',
                'SLIDE_',
                'VIDEO_',
                'SITE_',
                'AGENCY_',
                'LEVEL_WC_',
                'ADMIN_',
            ];

            foreach ($allowedPrefixes as $prefix) {
                if (str_starts_with($constantName, $prefix)) {
                    return true;
                }
            }

            return false;
        }

        private static function hasEnvValue(string $key): bool
        {

            $value = self::env($key, null);

            return is_scalar($value) && '' !== trim((string)$value);
        }

        private static function stringValue(mixed $value): ?string
        {

            if (null === $value) {
                return null;
            }

            if (is_string($value)) {
                $trimmed = trim($value);

                return '' === $trimmed ? null : $trimmed;
            }

            if (is_numeric($value)) {
                return (string)$value;
            }

            return null;
        }

        private static function configureErrorHandling(): void
        {

            if (PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg') {
                ErrorHandler::register(self::shouldRenderJsonErrors());
            } else {
                $currentReporting = error_reporting();
                error_reporting($currentReporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            }
        }

        /**
         * Identifica contextos AJAX/API para resposta de erro em JSON.
         */
        private static function shouldRenderJsonErrors(): bool
        {

            $requestUri = (string)($_SERVER['REQUEST_URI'] ?? '');
            $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
            $requestedWith = (string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
            $contentType = (string)($_SERVER['CONTENT_TYPE'] ?? '');

            if ('' !== $requestUri && (str_contains($requestUri, '/ajax/') || str_contains($requestUri, '.ajax.php'))) {
                return true;
            }

            if ('' !== $requestedWith && 'xmlhttprequest' === strtolower($requestedWith)) {
                return true;
            }

            if ('' !== $accept && str_contains(strtolower($accept), 'application/json')) {
                return true;
            }

            if ('' !== $contentType && str_contains(strtolower($contentType), 'application/json')) {
                return true;
            }

            return false;
        }
    }
