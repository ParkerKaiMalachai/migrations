<?php

declare(strict_types=1);

namespace src\migrations;

use src\classes\Migration;

final class CreateProductMigration extends Migration
{
    public function up(): string
    {
        return $this->createTable(
            'products',
            ['id' => 'int AUTO_INCREMENT', 'name' => 'varchar(255)'],
            ['id' => 'PRIMARY KEY']
        );
    }

    public function down(): string
    {
        return $this->dropTable('products');
    }
}