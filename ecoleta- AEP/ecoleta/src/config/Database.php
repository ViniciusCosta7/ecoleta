<?php
declare(strict_types=1);

class Database
{
    private static ?PDO $conexao = null;

    public static function conectar(): PDO
    {
        if (self::$conexao === null) {
            $host = 'localhost';
            $banco = 'ecoleta';
            $usuario = 'root';
            $senha = '';

            $dsn = "mysql:host={$host};dbname={$banco};charset=utf8mb4";
            self::$conexao = new PDO($dsn, $usuario, $senha, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        }

        return self::$conexao;
    }
}
