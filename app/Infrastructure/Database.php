<?php

namespace App\Infrastructure;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $conexao = null;

    public static function getConexao(): PDO
    {
        if (is_null(self::$conexao)) {
            $host = '127.0.0.1';
            $db   = 'projeto_final_lpg3';
            $user = 'gustavo';
            $pass = 'secret';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$conexao = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$conexao;
    }
}