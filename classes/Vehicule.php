<?php

require_once('database/db.php');

class Vehicule
{
    private $conn;

    public function __construct($conn = null)
    {
        $this->conn = $conn ?: $this->connectDB();
    }

    private function connectDB()
    {
        return connectDB();
    }

    public function getVehicules()
    {
        $result = $this->conn->query("SELECT v.id, v.nb_identification, v.marque, v.modele, v.annee, c.nom AS client 
                                      FROM vehicules v 
                                      JOIN clients c ON v.client_id = c.id");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getVehiculeById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM vehicules WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addVehicule($nb_identification, $marque, $modele, $annee, $client_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO vehicules (nb_identification, marque, modele, annee, client_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $nb_identification, $marque, $modele, $annee, $client_id);
        return $stmt->execute();
    }

    public function editVehicule($id, $nb_identification, $marque, $modele, $annee, $client_id)
    {
        $stmt = $this->conn->prepare("UPDATE vehicules SET nb_identification = ?, marque = ?, modele = ?, annee = ?, client_id = ? WHERE id = ?");
        $stmt->bind_param("sssiii", $nb_identification, $marque, $modele, $annee, $client_id, $id);
        return $stmt->execute();
    }

    public function deleteVehicule($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM vehicules WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}