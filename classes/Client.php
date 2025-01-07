<?php

require_once('database/db.php');

class Client
{
    private $conn;

    public function __construct()
    {
        $this->conn = $this->connectDB();
    }

    private function connectDB()
    {
        return connectDB();
    }

    public function getClients()
    {
        $result = $this->conn->query("SELECT id, nom FROM clients");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
