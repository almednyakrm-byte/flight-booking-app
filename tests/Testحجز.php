<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testحجز extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetحجز()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM حجز')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'test']]);

        $result = $this->getحجز($this->pdo);
        $this->assertEquals([['id' => 1, 'name' => 'test']], $result);
    }

    public function testPostحجز()
    {
        $data = ['name' => 'test'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO حجز (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->postحجز($this->pdo, $data);
        $this->assertTrue($result);
    }

    public function testPutحجز()
    {
        $id = 1;
        $data = ['name' => 'test'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE حجز SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->putحجز($this->pdo, $id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteحجز()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM حجز WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteحجز($this->pdo, $id);
        $this->assertTrue($result);
    }

    private function getحجز(PDO $pdo)
    {
        $stmt = $pdo->prepare('SELECT * FROM حجز');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postحجز(PDO $pdo, array $data)
    {
        $stmt = $pdo->prepare('INSERT INTO حجز (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    private function putحجز(PDO $pdo, int $id, array $data)
    {
        $stmt = $pdo->prepare('UPDATE حجز SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteحجز(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('DELETE FROM حجز WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}