<?php

namespace app\core;

use PDO;

class Database
{
    public PDO $pdo;

    public function __construct($config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, pdo::ERRMODE_EXCEPTION);
    }


    public function applyMigrations()
    {
        $this->createMigrationTable();
        $appliedMigration = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigration = \array_diff($files, $appliedMigration);
        foreach ($toApplyMigration as $migration) {
            if ($migration === '.' || $migration === "..") {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);

            $this->log("Applying migration $migration");
            $instance = new $className();
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log('All Migrations are applied');
        }
    }


    public function createMigrationTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )   ENGINE=INNODB;
        ");
    }


    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }


    public function saveMigrations($migrations)
    {
        $str = implode(',', array_map(fn ($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }


    public function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }


    protected function log($message)
    {
        echo '[' . \date("Y-m-d H:i:s") . '] - ' .  $message . \PHP_EOL;
    }
}
