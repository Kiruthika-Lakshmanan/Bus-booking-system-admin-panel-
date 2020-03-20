<?php

// Constants for DB
define('DB_HOST', empty(getenv('DATABASE_HOST')) ? '127.0.0.1' : getenv('DATABASE_HOST'));
define('DB_NAME', 'booking');
define('DB_USER_NAME', 'ss4u');
define('DB_PASSWORD', '123456');
define('ADMIN_EMAIL', 'info@softsolutions4u.info');
define('DB_PREFIX', 'st_');

define('DEBUG_MODE', 1);

$domainName = !empty($_SERVER['HTTP_HOST'])
    ? $_SERVER['HTTP_HOST'] : (!empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '');
define('DOMAIN_NAME', $domainName);
define('PROTOCOL', (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off' ? 'http' : 'https'));

define(
    'VALIDATOR_REGEX_EMAIL',
    '[a-zäàáâöôüûñéè0-9!\#\$\%\&\'\*\+\/\=\?\^_\`\{\|\}\~-]+(?:\.[a-zäàáâöôüûñéè0-9!\#\$\%\&\'\*\+\/\=\?\^_\`\{\|\}\~-]+)*@(?:[a-zäàáâöôüûñéè0-9](?:[a-zäàáâöôüûñéè0-9-]*[a-zäàáâöôüûñéè0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?'
);
