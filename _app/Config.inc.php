<?php

declare(strict_types=1);

// Compatibilidade com scripts legados: carrega o autoload do Composer,
// que registra o PSR-4 (App\) e executa o bootstrap (ConfigLoader::boot()).
require_once __DIR__ . '/../vendor/autoload.php';
