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
define('APP_URL_ASSETS', APP_ENV === 'production' ? 'https://assets.amazing-usa-canada.com' : 'http://assets.amazing-usa-canada.local:8080');
define('APP_URL_API', APP_ENV === 'production' ? 'https://api.amazing-usa-canada.com' : 'http://api.amazing-usa-canada.local:8080');
define('APP_URL_DASHBOARD', APP_ENV === 'production' ? 'https://dashboard.amazing-usa-canada.com' : 'http://dashboard.amazing-usa-canada.local:8080');
define('APP_URL_MAINTENANCE', APP_ENV === 'production' ? 'https://maintenance.amazing-usa-canada.com' : 'http://maintenance.amazing-usa-canada.local:8080');

define('ROOT_PATH', dirname(__DIR__, 2));
define('STORAGE_PATH', ROOT_PATH . '/STORAGE');
define('LOGS_PATH', ROOT_PATH . '/LOGS');