<?php

$host = 'db';
$db = 'task_manager';
$user = 'root';
$pass = 'root';

class DB
{
    private $db_client = null;

    public function __construct(string $host, string $db, string $user, string $pass)
    {
        $this->connect($host, $db, $user, $pass);
    }

    public function get_data_base(): mysqli
    {
        if ($this->db_client === null || is_bool($this->db_client)) {
            throw new Exception('Database not connected');
        }

        return $this->db_client;
    }

    private function connect(string $host, string $db, string $user, string $pass): void
    {
        $client = mysqli_connect($host, $user, $pass, $db);

        if (!$client) {
            die("Ошибка подключения к базе данных: " . mysqli_connect_error());
        }

        $client->set_charset("utf8");

        $this->db_client = $client;
    }
}

$data_base = new DB($host, $db, $user, $pass);
