<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use src\classes\MigrationManager;

$pdo = new PDO("mysql:host=db;dbname=seeds_db;charset=utf8", "seeds_user", "seeds");

$migrationsFolder = sprintf('%s%s', str_replace(
    '\\',
    DIRECTORY_SEPARATOR,
    realpath(dirname(__FILE__))
), '/src/migrations/*.php');
$allFiles = glob($migrationsFolder);


$manager = new MigrationManager($pdo, $allFiles);
