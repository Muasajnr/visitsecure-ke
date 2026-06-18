<?php
/**
 * app/core/DB.php
 * Thin convenience wrapper around the shared $pdo PDO instance.
 * Keeps controllers/models free of repetitive prepare/execute boilerplate.
 */

class DB
{
    private static ?PDO $conn = null;

    public static function conn(): PDO
    {
        global $pdo;
        if (self::$conn === null) {
            self::$conn = $pdo;
        }
        return self::$conn;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::conn()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function one(string $sql, array $params = []): array|false
    {
        return self::query($sql, $params)->fetch();
    }

    public static function all(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function insert(string $sql, array $params = []): string
    {
        self::query($sql, $params);
        return self::conn()->lastInsertId();
    }

    public static function run(string $sql, array $params = []): int
    {
        return self::query($sql, $params)->rowCount();
    }

    public static function beginTransaction(): bool
    {
        return self::conn()->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::conn()->commit();
    }

    public static function rollBack(): bool
    {
        return self::conn()->rollBack();
    }
}
