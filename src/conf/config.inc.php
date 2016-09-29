<?php

date_default_timezone_set('UTC');

$CONFIG_INI_FILE = dirname(__FILE__) . '/config.ini';
if (!file_exists($CONFIG_INI_FILE)) {
  trigger_error('Application not configured. Run pre-install script.');
  exit(-1);
}

$CONFIG = parse_ini_file($CONFIG_INI_FILE);

if (!isset($NO_DB) || $NO_DB !== true) {
  $DB = new PDO($CONFIG['DB_DSN'], $CONFIG['DB_USER'], $CONFIG['DB_PASS']);
  $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
