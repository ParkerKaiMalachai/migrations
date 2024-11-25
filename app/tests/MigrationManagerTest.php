<?php

declare(strict_types=1);

require 'app/autoload.php';
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use src\classes\MigrationManager;

final class MigrationManagerTest extends TestCase
{
    public function getInitParam(): array
    {
        $migrationsFolder = sprintf('%s%s', str_replace(
            '\\',
            DIRECTORY_SEPARATOR,
            realpath(dirname(__DIR__))
        ), '/src/migrations/*.php');

        $allFiles = glob($migrationsFolder);

        return $allFiles;
    }
    public function testGetMigrations(): void
    {
        $files = $this->getInitParam();
        $migrations = ["CreateEmployeeMigration", "CreateOrderMigration", "CreateProductMigration", "CreateUserMigration"];

        $mock = Mockery::mock(PDO::class);

        $manager = new MigrationManager($mock, $files);

        $this->assertSame($migrations, $manager->getMigrations());
    }

    public function testGetPendingMigrations(): void
    {
        $files = $this->getInitParam();

        $migrations = ["CreateEmployeeMigration", "CreateOrderMigration", "CreateProductMigration", "CreateUserMigration"];

        $mockPDOFetch = Mockery::mock(PDOStatement::class);

        $mock = Mockery::mock(PDO::class);

        $mock->shouldReceive('query')->andReturn($mockPDOFetch);

        $mockPDOFetch->shouldReceive('fetchAll')->andReturn([]);


        $manager = new MigrationManager($mock, $files);

        $this->assertSame($migrations, $manager->getPendingMigrations());
    }

    public function testRunPendingMigrations(): void
    {
        $files = $this->getInitParam();

        $migrations = ["CreateEmployeeMigration", "CreateOrderMigration", "CreateProductMigration", "CreateUserMigration"];

        $mockPDOFetch = Mockery::mock(PDOStatement::class);

        $mock = Mockery::mock(PDO::class);

        $mock->shouldReceive('query')->andReturn($mockPDOFetch);

        $mockPDOFetch->shouldReceive('fetchAll')->andReturn([]);

        $mock->shouldReceive('prepare')->with("INSERT INTO migrations (name, executed_at) VALUES (?, CURRENT_TIMESTAMP())")
            ->andReturn($mockPDOFetch);

        foreach ($migrations as $migration) {
            $mockPDOFetch->shouldReceive('execute')->with(array($migration))->andReturn(true);
        }

        $manager = new MigrationManager($mock, $files);

        $this->assertTrue($manager->runPendingMigration());
    }

    public function testRunPendingMigrationsWithEmptyList(): void
    {
        $migrationsCompleted = [
            ['name' => 'CreateEmployeeMigration', 0 => "CreateEmployeeMigration"],
            ['name' => "CreateOrderMigration", 0 => "CreateOrderMigration"],
            ['name' => "CreateProductMigration", 0 => "CreateProductMigration"],
            ['name' => "CreateUserMigration", 0 => "CreateUserMigration"]
        ];

        $files = $this->getInitParam();

        $mockPDOFetch = Mockery::mock(PDOStatement::class);

        $mock = Mockery::mock(PDO::class);

        $mock->shouldReceive('query')->andReturn($mockPDOFetch);

        $mockPDOFetch->shouldReceive('fetchAll')->andReturn($migrationsCompleted);

        $manager = new MigrationManager($mock, $files);

        $this->assertFalse($manager->runPendingMigration());
    }
}