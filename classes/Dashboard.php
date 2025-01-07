<?php

require_once('database/db.php');

class Dashboard
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

    public function getTotalClients()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS total_clients FROM clients");
        $row = $result->fetch_assoc();
        return $row['total_clients'];
    }

    public function getTotalVehicules()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS total_vehicules FROM vehicules");
        $row = $result->fetch_assoc();
        return $row['total_vehicules'];
    }

    public function getTotalRendezvous()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS total_rendezvous FROM rendezvous");
        $row = $result->fetch_assoc();
        return $row['total_rendezvous'];
    }
}
