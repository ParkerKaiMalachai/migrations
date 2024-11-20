<?php

declare(strict_types=1);

namespace src\classes;

use src\interfaces\MigrationInterface;

abstract class Migration implements MigrationInterface
{
    public function createTable(string $name, array $values, array $constraints): string
    {
        $query = "";

        foreach ($values as $key => $value) {
            $query .= $key . " " . $value . ", ";
        }
        if (count($constraints)) {
            foreach ($constraints as $value => $constraint) {
                $query .= $constraint . " " . "($value)";
            }
        }
        return "CREATE TABLE IF NOT EXISTS $name ($query)";
    }

    public function dropTable(string $name): string
    {
        return "DROP TABLE IF EXISTS $name";
    }
    abstract public function up();

    abstract public function down();
}
