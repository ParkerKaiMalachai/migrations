<?php

declare(strict_types=1);

namespace src\interfaces;

use src\classes\Migration;

interface MigrationManagerInterface
{
    public function getMigrations();

    public function getPendingMigrations();

    public function setPendingMigrations();

    public function runPendingMigration();

    public function runMigration(Migration $migration, string $migrationName);

    public function writeNewMigration(string $name);

    public function rollbackMigration();

    public function getLatestMigration();

    public function removeLatestMigration(string $name);
}