<?php


namespace Jggurgel\Pext\Lib;


use PDO;

class Database
{
    private $conn;

    function __construct(
        private $servername = "localhost",
        private $username = "root",
        private $password = "root",
        private $database = 'myDB'
    ) {
        $this->conn = new PDO("mysql:host={$this->servername};dbname={$this->database}", $this->username, $this->password);
    }

    public function run(string $query): void
    {
        $this->conn->query($query);
    }

    public function query(string $query, array $params = [], int $mode = PDO::FETCH_OBJ): array
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);
        return $sth->fetchAll($mode);
    }

    public function first(string $query, array $params = [])
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);
        $data = $sth->fetch(PDO::FETCH_OBJ);
        return $data ?: null;
    }

    public function insert(string $table, array $data): bool
    {
        $columns = array_keys($data);
        $columnsSeparedByComma = implode(', ', $columns);
        $questionMarks = array_fill(0, count($columns), '?');
        $questionMarksSeparedByComma = implode(",", $questionMarks);
        $query  = "INSERT INTO $table ( $columnsSeparedByComma ) VALUES ( $questionMarksSeparedByComma )";
        $sth = $this->conn->prepare($query);
        return   $sth->execute(array_values($data));
    }

    public function runMigrations()
    {
        $migrationsDir =  base_dir('/database/migrations');
        $migrationTableFile = "0-create-table-migration.sql";

        $this->run(file_get_contents($migrationsDir . DIRECTORY_SEPARATOR .  $migrationTableFile));
        $files = scandir($migrationsDir);

        foreach ($files as $file) {
            if ($file == $migrationTableFile || $file == '.' || $file == '..') {
                continue;
            }
            if ($this->first('select id from migration where name = ?', [$file])) {
                continue;
            }
            $this->run(file_get_contents($migrationsDir . DIRECTORY_SEPARATOR .  $file));
            $this->insert('migration', ['name' => $file]);
        }
    }

    public function dropTables()
    {
        $tables = $this->query('SHOW TABLES',mode:PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $this->run("DROP TABLE IF EXISTS " . $table);
        }
    }
}
