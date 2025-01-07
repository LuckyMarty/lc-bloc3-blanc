<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


function connectDB()
{
  $host = $_ENV['DB_HOST'];
  $user = $_ENV['DB_USER'];
  $password = $_ENV['DB_PASSWORD'];
  $database = $_ENV['DB_DATABASE'];

  $conn = new mysqli($host, $user, $password, $database);

  if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
  } else {
    return $conn;
  }
}
