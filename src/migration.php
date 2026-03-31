<?php

use App\Routing\Router;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/init.php';

/**
 * @var $container DI\Container
 * @var $db \PDO
 */

$db = $container->get("DB");

$db->exec("
    CREATE TABLE IF NOT EXISTS tables (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        table_number integer NOT NULL,
        capacity integer NOT NULL,
        is_active BOOLEAN NOT NULL,
        CONSTRAINT uc_table_number UNIQUE (table_number)
    ) ENGINE=InnoDB;
");

$db->exec("
    CREATE TABLE IF NOT EXISTS bookings (
        id INT(11)  unsigned AUTO_INCREMENT PRIMARY KEY,
        table_id INT(11) UNSIGNED DEFAULT NULL,
        guest_name varchar(100) DEFAULT NULL,
        guest_phone varchar(20) DEFAULT NULL,
        booking_date date NOT NULL,
        start_time time NOT NULL,
        end_time time NOT NULL,
        guests_count integer DEFAULT NULL,
        status varchar(10) default 'confirmed',
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (table_id)  REFERENCES tables (id)
    ) ENGINE=InnoDB;
");