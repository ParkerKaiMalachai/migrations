<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use src\classes\MigrationManager;

$pdo = new PDO("mysql:host=db;dbname=seeds_db;charset=utf8", "seeds_user", "seeds");

$manager = new MigrationManager($pdo);

$migrateValue = getenv('MIGRATE');

switch ($migrateValue) {
    case 'up': {
        $manager->runPendingMigration();
        break;
    }
    case 'down': {
        $manager->rollbackMigration();
        break;
    }
}
