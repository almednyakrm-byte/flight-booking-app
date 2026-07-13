<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testملاحة_الجوية extends TestCase
{
    private MockObject $pdo;
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }

    public function testGetRequest(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM ملاحة_الجوية')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Test'],
            ]);

        $result = $this->getRequest('GET', '/ملاحة_الجوية');
        $this->assertEquals(200, $result['status']);
        $this->assertEquals([['id' => 1, 'name' => 'Test']], $result['data']);
    }

    public function testPostRequest(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO ملاحة_الجوية (name) VALUES (:name)')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([':name' => 'Test']);

        $this->pdoStatement
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->postRequest('POST', '/ملاحة_الجوية', ['name' => 'Test']);
        $this->assertEquals(201, $result['status']);
        $this->assertEquals(['message' => 'Created successfully'], $result['data']);
    }

    public function testPutRequest(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE ملاحة_الجوية SET name = :name WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([':id' => 1, ':name' => 'Test']);

        $this->pdoStatement
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->putRequest('PUT', '/ملاحة_الجوية/1', ['name' => 'Test']);
        $this->assertEquals(200, $result['status']);
        $this->assertEquals(['message' => 'Updated successfully'], $result['data']);
    }

    public function testDeleteRequest(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM ملاحة_الجوية WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $this->pdoStatement
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->deleteRequest('DELETE', '/ملاحة_الجوية/1');
        $this->assertEquals(200, $result['status']);
        $this->assertEquals(['message' => 'Deleted successfully'], $result['data']);
    }

    private function getRequest(string $method, string $uri): array
    {
        // Implement request logic here
    }

    private function postRequest(string $method, string $uri, array $data): array
    {
        // Implement request logic here
    }

    private function putRequest(string $method, string $uri, array $data): array
    {
        // Implement request logic here
    }

    private function deleteRequest(string $method, string $uri): array
    {
        // Implement request logic here
    }
}