<?php

namespace App\Classes;

use PDO;
use Exception;

class PDOConnection {
  function __construct() {
    $DB_CONNECTION = env('DB_CONNECTION');
    $DB_HOST = env('DB_HOST');
    $DB_PORT = env('DB_PORT');
    $DB_DATABASE = env('DB_DATABASE');
    $DB_USERNAME = env('DB_USERNAME');
    $DB_PASSWORD = env('DB_PASSWORD');

    // dsn é apenas um acrônimo de database source name
    $DSN = "$DB_CONNECTION:host=$DB_HOST:$DB_PORT;dbname=$DB_DATABASE;charset=utf8mb4";

    $options = [
      PDO::ATTR_EMULATE_PREPARES => false, // desativa a execução emulada de prepared statements
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // ativa o modo de erros para lançar exceções    
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // altera o modo padrão do método fetch para FETCH_ASSOC
    ];

    try {
      $pdo = new PDO($DSN, $DB_USERNAME, $DB_PASSWORD, $options);
    } catch (Exception $e) {
      throw new Exception('PDOConnection error: ' . $e->getMessage());
    }
    return $pdo;
  }
}
