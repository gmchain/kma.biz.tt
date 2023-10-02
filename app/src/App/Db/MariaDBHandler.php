<?php

namespace App\Db;

class MariaDBHandler implements HandlerInterface
{
    private \PDO $connection;

    public function __construct(
        string $host,
        string $database,
        string $user,
        string $password
    ) {
        $this->connection = new \PDO("mysql:host=$host;port=3306;dbname=$database", $user, $password);
    }

    public function insert(string $url, int $length, int $timestamp): void
    {
        $query = $this->connection->prepare(<<<SQL

            INSERT INTO urls (url, length, date)
            VALUE (:url, :length, FROM_UNIXTIME(:time))

        SQL);

        $query->execute([':url' => $url, ':length' => $length, ':time' => $timestamp]);
    }

    public function getStats(): \Generator
    {
        $query = $this->connection->query(<<<SQL

            SELECT 
                FROM_UNIXTIME(UNIX_TIMESTAMP(date) - UNIX_TIMESTAMP(date) % 60) AS minute, 
                COUNT(id) AS request_count, 
                AVG(length) AS average_length,
                MIN(date) AS min_date, 
                MAX(date) AS max_date
            FROM urls 
            GROUP BY minute 
            ORDER BY minute;
            
        SQL, \PDO::FETCH_OBJ);

        while ($row = $query->fetch()) {
            yield $row;
        }
    }
}