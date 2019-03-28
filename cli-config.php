<?php
declare(strict_types=1);
require __DIR__.'/config/bootstrap.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');

$databaseDriver = getenv('DATABASE_DRIVER');
$databasePath = __DIR__ . getenv('SQLITE_DATABASE_PATH');

$entityManager = \NAC\Infrastructure\Doctrine\EntityManagerFactory::create($databaseDriver, $databasePath);
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
