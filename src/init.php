<?php

use App\Contracts\Repositories\TableRepository;
use App\Repositories\TableRepositoryDb;
use \App\Contracts\Services\TableServiceContract;
use App\Services\TableService;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV["MYSQL_HOST"];
$dbName = $_ENV["MYSQL_DB"];
$dbUserName = $_ENV["MYSQL_USER"];
$dbPassword = $_ENV["MYSQL_PASSWORD"];

$db = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $dbUserName, $dbPassword, array(PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
        "DB" => $db,
        TableServiceContract::class =>  \DI\get(TableService::class),
        TableRepository::class =>  \DI\get(TableRepositoryDb::class),

])->useAttributes(true);
$container = $builder->build();
