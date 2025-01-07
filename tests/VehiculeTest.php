<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../classes/Vehicule.php');

class VehiculeTest extends TestCase
{
    private $vehicule;
    private $mockConn;

    protected function setUp(): void
    {
        $this->mockConn = $this->createMock(mysqli::class);

        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockStmt->method('bind_param')->willReturn(true);
        $mockStmt->method('execute')->willReturn(true);

        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->vehicule = new Vehicule($this->mockConn);
    }

    public function testAddVehiculeSuccess()
    {
        $result = $this->vehicule->addVehicule('123456789012', 'Toyota', 'Corolla', 2020, 1);
        $this->assertTrue($result);
    }

    public function testAddVehiculeNbIdentificationTooLong()
    {
        $result = $this->vehicule->addVehicule('1234567890123', 'Toyota', 'Corolla', 2020, 1);
        $this->assertEquals("Le numéro d'identification ne doit pas dépasser 12 caractères.", $result);
    }

    public function testGetVehicules()
    {
        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_all')->willReturn([
            ['id' => 1, 'nb_identification' => '123456789012', 'marque' => 'Toyota', 'modele' => 'Corolla', 'annee' => 2020, 'client_id' => 1]
        ]);

        $this->mockConn->method('query')->willReturn($mockResult);

        $result = $this->vehicule->getVehicules();
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('123456789012', $result[0]['nb_identification']);
    }

    public function testEditVehiculeSuccess()
    {
        $result = $this->vehicule->editVehicule(1, '123456789012', 'Toyota', 'Corolla', 2020, 1);
        $this->assertTrue($result);
    }

    public function testEditVehiculeNbIdentificationTooLong()
    {
        $result = $this->vehicule->editVehicule(1, '1234567890123', 'Toyota', 'Corolla', 2020, 1);
        $this->assertEquals("Le numéro d'identification ne doit pas dépasser 12 caractères.", $result);
    }

    public function testDeleteVehiculeSuccess()
    {
        $result = $this->vehicule->deleteVehicule(1);
        $this->assertTrue($result);
    }
}