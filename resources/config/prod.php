<?php
$app['log.level'] = Monolog\Logger::ERROR;
$app['api.version1'] = "v1";
$app['api.version2'] = "v2";
$app['api.endpoint'] = "/api";

/**
 * SQLite database file
 */
$app['db.options'] = array(
    'driver' => 'pdo_sqlite',
    'path' => realpath(ROOT_PATH . '/app.db'),
);

/**
 * MySQL
 */
//$app['db.options'] = array(
//  "driver" => "pdo_mysql",
//  "user" => "root",
//  "password" => "root",
//  "dbname" => "prod_db",
//  "host" => "prod_host",
//);
