<?php

declare(strict_types=1);

function myAutoloader(string $className): void
{
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    $fileName = __DIR__ . '/' . $className . '.php';
    
    if (file_exists($fileName)) {
        require $fileName;
    }
}

spl_autoload_register('myAutoloader');