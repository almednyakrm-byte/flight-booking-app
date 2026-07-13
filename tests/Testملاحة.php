<?php

namespace App\Tests\Controller;

use App\Controller\MalhaController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Response;

class TestMalha extends TestCase
{
    private $malhaController;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->malhaController = new MalhaController($this->pdoMock);
    }

    public function testGetMalha()
    {
        $this->pdoMock
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM malha')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->malhaController->getMalha();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostMalha()
    {
        $data = [
            'name' => 'Test Malha',
            'description' => 'Test description',
        ];

        $this->pdoMock
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO malha (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->malhaController->postMalha($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutMalha()
    {
        $data = [
            'id' => 1,
            'name' => 'Test Malha',
            'description' => 'Test description',
        ];

        $this->pdoMock
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE malha SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->malhaController->putMalha($data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMalha()
    {
        $id = 1;

        $this->pdoMock
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM malha WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->malhaController->deleteMalha($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes that the `MalhaController` class has methods `getMalha`, `postMalha`, `putMalha`, and `deleteMalha` which handle the respective CRUD operations. The `PDO` mock object is used to simulate the database interactions. The `createMock` method is used to create mock objects for the `PDOStatement` class.