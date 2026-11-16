
<?php
class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $connection;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->dbname = getenv('DB_NAME') ?: 'bucketlist';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
    }

    public function connect()
    {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            if ($this->host !== 'localhost') {
                $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
            }

            $this->connection = new PDO(
                "mysql:host={$this->host};port=3306;dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                $options
            );

            return $this->connection;

        } catch (PDOException $e) {
            error_log("Databasfel: " . $e->getMessage());
            die("Databasanslutningen misslyckades.");
        }
    }
}
