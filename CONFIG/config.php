<?php

define("APP_ENV", 'development');
define("APP_DEBUG", APP_ENV === "development");

define('DB_HOST', 'mariadb');
define('DB_PORT', '3306');
define('DB_NAME', 'amazingusacanada');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

define('APP_NAME', 'Amazing USA Canada');
define('APP_URL', APP_ENV === 'production' ? 'https://amazing-usa-canada.com' : 'http://amazing-usa-canada.local:8080');

define('ROOT_PATH', dirname(__DIR__, 2));
define('STORAGE_PATH', ROOT_PATH . '/STORAGE');
define('LOGS_PATH', ROOT_PATH . '/LOGS');