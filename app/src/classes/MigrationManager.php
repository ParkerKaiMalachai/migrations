<?php

declare(strict_types=1);

namespace src\classes;

use \PDO;
use \src\classes\Migration;

final class MigrationManager
{
    private array $allMigrations = [];

    private array $pendingMigrations = [];

    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $migrationsFolder = str_replace('\\', DIRECTORY_SEPARATOR, realpath(dirname(__DIR__)));
        $allFiles = glob($migrationsFolder . '/migrations/*.php');
        foreach ($allFiles as $file) {
            $this->allMigrations[] = pathinfo($file, PATHINFO_FILENAME);
        }

        $this->connection = $connection;
    }
    public function getMigrations(): array|bool
    {
        return $this->allMigrations;
    }

    public function getPendingMigrations(): array
    {
        $this->setPendingMigrations();

        return $this->pendingMigrations;
    }

    public function setPendingMigrations(): void
    {
        $data = $this->connection->query("SELECT name FROM migrations");

        foreach ($data as $row) {
            $completedMigrations[] = $row['name'];
        }

        $this->pendingMigrations = array_diff($this->allMigrations, $completedMigrations);
    }

    public function runPendingMigration(): void
    {
        $this->getPendingMigrations();
        if (count($this->pendingMigrations) > 0) {
            foreach ($this->pendingMigrations as $migrationClassName) {
                $className = "src\\migrations\\" . $migrationClassName;
                $migration = new $className();
                $this->runMigration($migration, $migrationClassName);
            }
        }
    }

    public function runMigration(Migration $migration, string $migrationName): void
    {
        $this->connection->query($migration->up());

        $this->writeNewMigration($migrationName);
    }

    public function writeNewMigration(string $name): void
    {
        $stmt = $this->connection->prepare("INSERT INTO migrations (name, executed_at) VALUES (?, CURRENT_TIMESTAMP())");

        $stmt->execute(array($name));
    }

    public function rollbackMigration(): void
    {
        $migrationArray = $this->getLatestMigration();

        if (count($migrationArray)) {

            $this->connection->query($migrationArray['migration']->down());

            $this->removeLatestMigration($migrationArray['migrationName']);
        }
    }

    public function getLatestMigration(): array
    {
        $data = $this->connection->query("SELECT * FROM migrations WHERE executed_at=(SELECT MAX(executed_at) FROM migrations)");

        foreach ($data as $row) {
            $latestMigrationName = $row['name'];
        }
        if (isset($latestMigrationName)) {
            $className = "src\\migrations\\" . $latestMigrationName;
            $migration = new $className();

            return ['migration' => $migration, 'migrationName' => $latestMigrationName];
        } else {
            return [];
        }
    }

    public function removeLatestMigration(string $name): void
    {
        $stmt = $this->connection->prepare("DELETE FROM migrations WHERE name=?");

        $stmt->execute(array($name));
    }
}

