<?php

declare(strict_types=1);

namespace src\interfaces;

interface MigrationInterface
{
    public function createTable(string $name, array $values, array $constraints);

    public function dropTable(string $name);

    public function up();

    public function down();
}