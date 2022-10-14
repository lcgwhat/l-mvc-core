<?php
/**
 * @author: liuchg
 *
 */

namespace app\core\db;


use app\core\Application;

class Database
{
    /**
     * @var $pdo \PDO
     */
    public $pdo;
    public function __construct($config)
    {
        $dsn = $config['dns'];
        $user = $config['user'];
        $password = $config['password'];
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationTable();
        $this->getAppliedMigrations();
        $files = scandir(Application::$ROOTPATH.'/migrations');
        var_dump($files);die;
    }

    public function createMigrationTable()
    {
        $this->pdo->exec("
            create table if not exists migrations (
                id int AUTO_INCREMENT PRIMARY_KEY,
                migration  varchar(255),
                batch  int(10),
                create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            )Engine=INNODB;
        ");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("select migrations from migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
}
