<?php

declare(strict_types=1);

require 'autoload.php';

use src\classes\MigrationManager;

$pdo = new PDO("mysql:host=db;dbname=seeds_db;charset=utf8", "seeds_user", "seeds");

$migrationsFolder = sprintf('%s%s', str_replace(
    '\\',
    DIRECTORY_SEPARATOR,
    realpath(dirname(__DIR__))
), '/src/migrations/*.php');
$allFiles = glob($migrationsFolder);


$manager = new MigrationManager($pdo, $allFiles);

$params = getopt("", ['direction:']);

switch ($params['direction']) {
    case 'up': {
        $manager->runPendingMigration();
        break;
    }
    case 'down': {
        $manager->rollbackMigration();
        break;
    }
}